<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Darren's Library Catalog</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            padding: 40px 20px;
            margin: 0;
        }
        .container {
            max-width: 750px;
            margin: 0 auto;
            background: #ffffff;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        h2 { margin-top: 0; color: #1a1a1a; font-size: 1.5rem; }

        /* Form css */
        .add-form {
            display: flex;
            gap: 10px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }
        .add-form input, .modal-body input {
            flex: 1;
            min-width: 140px;
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        .add-form input:focus, .modal-body input:focus {
            outline: none;
            border-color: #007bff;
        }

        /* Table css */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #f1f3f5; font-size: 14px; color: #555; }
        tr:hover { background-color: #fafafa; }

        /* Status badge */
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        .available { background: #e6f4ea; color: #1e7e34; }
        .borrowed { background: #fce8e6; color: #d93025; }

        /* Actions & Buttons */
        .actions { display: flex; gap: 6px; }
        .btn {
            padding: 6px 12px;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 13px;
            border: none;
            cursor: pointer;
            display: inline-block;
        }
        .btn-primary { background: #007bff; }
        .btn-primary:hover { background: #0056b3; }
        .btn-warning { background: #ffc107; color: #212529; }
        .btn-warning:hover { background: #e0a800; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #bd2130; }
        .btn-toggle { background: #6c757d; }
        .btn-toggle:hover { background: #5a6268; }
        .btn-secondary { background: #e2e8f0; color: #4a5568; }
        .btn-secondary:hover { background: #cbd5e0; }

        /* Modal Popup css */
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.4);
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .modal-header { font-size: 1.2rem; font-weight: bold; margin-bottom: 15px; }
        .modal-body { display: flex; flex-direction: column; gap: 12px; }
        .modal-footer { display: flex; justify-content: flex-end; gap: 8px; margin-top: 15px; }
    </style>
</head>

<body>

    <div class="container">
        <h2>Darren's Library Catalog</h2>

        <!-- create book -->
        <form action="index.php?action=create" method="POST" class="add-form">
            <input type="text" name="title" placeholder="Book Title" required>
            <input type="text" name="author" placeholder="Author" required>
            <button type="submit" class="btn btn-primary">Add Book</button>
        </form>

        <!-- book table -->
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($books)): ?>
                    <?php foreach ($books as $book): ?>
                        <tr>
                            <td><?= htmlspecialchars($book['title']) ?></td>
                            <td><?= htmlspecialchars($book['author']) ?></td>
                            <td>
                                <?php if ($book['is_borrowed']): ?>
                                    <span class="badge borrowed">Checked Out</span>
                                <?php else: ?>
                                    <span class="badge available">Available</span>
                                <?php endif; ?>
                            </td>
                            <td class="actions">
                                <!-- Borrow return button -->
                                <a href="index.php?action=toggle&id=<?= $book['id'] ?>" class="btn btn-toggle">
                                    <?= $book['is_borrowed'] ? 'Return' : 'Borrow' ?>
                                </a>
                                <!-- edit button -->
                                <button type="button"
                                        class="btn btn-warning"
                                        onclick="openEditModal(<?= $book['id'] ?>, '<?= htmlspecialchars($book['title'], ENT_QUOTES) ?>', '<?= htmlspecialchars($book['author'], ENT_QUOTES) ?>')">
                                    Edit
                                </button>
                                <!--delete button -->
                                <a href="index.php?action=delete&id=<?= $book['id'] ?>"
                                              class="btn btn-danger">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center; color: #888;">No books found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- edit popup -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">Edit Book</div>
            <form action="index.php?action=update" method="POST">
                <input type="hidden" name="id" id="edit-id">
                <div class="modal-body">
                    <div>
                        <label style="font-size: 12px; font-weight: bold; color: #555;">Title</label>
                        <input type="text" name="title" id="edit-title" required style="width: 100%; margin-top: 4px;">
                    </div>
                    <div>
                        <label style="font-size: 12px; font-weight: bold; color: #555;">Author</label>
                        <input type="text" name="author" id="edit-author" required style="width: 100%; margin-top: 4px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, title, author) {
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-title').value = title;
            document.getElementById('edit-author').value = author;
            document.getElementById('editModal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target === modal) {
                closeEditModal();
            }
        }
    </script>

</body>
</html>