<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System (MVC)</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f4f6f8;
            color: #333;
            padding: 40px 20px;
            margin: 0;
        }
        .container {
            max-width: 750px;
            margin: 0 auto;
            background: #ffffff;
            padding: 28px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        h2 { margin-top: 0; color: #1a1a1a; font-size: 1.5rem; }

        /* Form & Filter Container */
        .add-form {
            display: flex;
            gap: 10px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }
        .add-form input, .add-form select, .action-select {
            padding: 9px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            background-color: #fff;
            color: #374151;
            outline: none;
            transition: border-color 0.2s;
        }
        .add-form input { flex: 1; min-width: 140px; }
        .add-form input:focus, .add-form select:focus, .action-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
        }

        /* Table Styling */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px 14px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        th { background-color: #f9fafb; font-size: 13px; text-transform: uppercase; color: #6b7280; letter-spacing: 0.05em; }
        tr:hover { background-color: #f9fafb; }

        /* Status Badge */
        .badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }
        .available { background: #dcfce7; color: #15803d; }
        .borrowed { background: #fee2e2; color: #b91c1c; }

        /* Buttons & Dropdown Action */
        .btn-add {
            padding: 9px 16px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-add:hover { background: #1d4ed8; }

        .action-select {
            cursor: pointer;
            padding: 6px 10px;
            font-size: 13px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Library Catalog</h2>

        <!-- Form Tambah Buku dengan Pilihan Status via Dropdown -->
        <form action="index.php?action=create" method="POST" class="add-form">
            <input type="text" name="title" placeholder="Book Title" required>
            <input type="text" name="author" placeholder="Author" required>

            <!-- Dropdown Status Awal -->
            <select name="is_borrowed">
                <option value="0">Available</option>
                <option value="1">Checked Out</option>
            </select>

            <button type="submit" class="btn-add">+ Add Book</button>
        </form>

        <!-- Tabel Buku -->
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th style="width: 140px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($books)): ?>
                    <?php foreach ($books as$book): ?>
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
                            <td>
                                <!-- Dropdown Aksi (Borrow/Return & Delete) -->
                                <select class="action-select" onchange="handleAction(this, '<?= $book['id'] ?>')">
                                    <option value="" disabled selected>Select Action</option>
                                    <option value="toggle">
                                        <?= $book['is_borrowed'] ? 'Mark as Return' : 'Mark as Borrow' ?>
                                    </option>
                                    <option value="delete">Delete Book</option>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center; color: #9ca3af; padding: 20px;">No books available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Script Sederhana untuk Navigasi Dropdown Aksi -->
    <script>
        function handleAction(selectElement, id) {
            const action = selectElement.value;

            if (action === 'delete') {
                if (confirm('Are you sure you want to delete this book?')) {
                    window.location.href = `index.php?action=delete&id=${id}`;
                } else {
                    selectElement.value = ""; // Reset dropdown jika dibatalkan
                }
            } else if (action === 'toggle') {
                window.location.href = `index.php?action=toggle&id=${id}`;
            }
        }
    </script>

</body>
</html>