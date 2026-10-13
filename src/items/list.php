<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../layout.php';

$items = $pdo->query(
    'SELECT ItemCode, Name, Category, Location, ItemCondition, Quantity
     FROM items
     ORDER BY Name'
)->fetchAll();

app_header('Daftar Barang');
?>

<h2>Daftar Barang</h2>

<p>
    <a class="button" href="create.php">Tambah Barang Baru</a>
</p>

<table>
    <thead>
        <tr>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Lokasi</th>
            <th>Kondisi</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= e($item['ItemCode']) ?></td>
                <td><?= e($item['Name']) ?></td>
                <td><?= e($item['Category'] ?? '-') ?></td>
                <td><?= e($item['Location'] ?? '-') ?></td>
                <td><?= e($item['ItemCondition'] ?? '-') ?></td>
                <td><?= (int) $item['Quantity'] ?></td>
                <td>
                    <a href="edit.php?code=<?= urlencode($item['ItemCode']) ?>">Edit</a>
                    |
                    <a
                        href="delete.php?code=<?= urlencode($item['ItemCode']) ?>"
                        onclick="return confirm('Hapus barang <?= e($item['ItemCode']) ?>?')"
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
