<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library Book Order System</title>
    <style>
        body { font-family: sans-serif; margin: 40px; }
        table { border-collapse: collapse; width: 100%; max-width: 600px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        .available { color: green; font-weight: bold; }
        .borrowed { color: red; font-weight: bold; }
        a.btn { padding: 5px 10px; background: #007bff; color: white; text-decoration: none; border-radius: 3px; }
    </style>
</head>
<body>
    <h2>Library Catalog</h2>

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
            <?php foreach ($books as $book): ?>
                <tr>
                    <td><?= htmlspecialchars($book['title']) ?></td>
                    <td><?= htmlspecialchars($book['author']) ?></td>
                    <td>
                        <?php if ($book['is_borrowed']): ?>
                            <span class="borrowed">Checked Out</span>
                        <?php else: ?>
                            <span class="available">Available</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="index.php?action=toggle&id=<?= $book['id'] ?>" class="btn">
                            <?= $book['is_borrowed'] ? 'Return' : 'Borrow' ?>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>