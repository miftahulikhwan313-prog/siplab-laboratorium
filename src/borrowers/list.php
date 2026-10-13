<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../layout.php';

$borrowers = $pdo->query(
    'SELECT BorrowerID, Name, Email, Phone
     FROM borrowers
     ORDER BY Name'
)->fetchAll();

app_header('Daftar Peminjam');
?>

<h2>Daftar Peminjam</h2>

<p>
    <a class="button" href="create.php">Tambah Peminjam Baru</a>
</p>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Telepon</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($borrowers as $borrower): ?>
            <tr>
                <td><?= (int) $borrower['BorrowerID'] ?></td>
                <td><?= e($borrower['Name']) ?></td>
                <td><?= e($borrower['Email'] ?? '-') ?></td>
                <td><?= e($borrower['Phone'] ?? '-') ?></td>
                <td>
                    <a href="edit.php?id=<?= (int) $borrower['BorrowerID'] ?>">Edit</a>
                    |
                    <a
                        href="delete.php?id=<?= (int) $borrower['BorrowerID'] ?>"
                        onclick="return confirm('Hapus peminjam <?= e($borrower['Name']) ?>?')"
                    >
                        Hapus
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
app_footer();
