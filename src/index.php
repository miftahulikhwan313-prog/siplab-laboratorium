<?php
declare(strict_types=1);

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/layout.php';

$items = $pdo->query(
    'SELECT ItemCode, Name, Category, Location, ItemCondition, Quantity
     FROM items
     WHERE Quantity > 0
     ORDER BY Name'
)->fetchAll();

app_header('Katalog Barang Tersedia');
?>

<h2>Katalog Barang Tersedia</h2>

<p>
    <a class="button" href="items/list.php">Lihat Daftar Barang</a>
</p>



<?php if (count($items) === 0): ?>
    <p class="muted">
        Belum ada barang yang tersedia.
    </p>
<?php else: ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 1rem; margin-top: 1rem;">
        <?php foreach ($items as $item): ?>
            <article style="border: 1px solid #ddd; border-left: 6px solid #14756e; padding: 1rem; border-radius: 6px; background: #fff;">
                <h3 style="margin: 0 0 0.5rem; color: #14756e;">
                    <?= e($item['Name']) ?>
                </h3>

                <p style="margin: 0.25rem 0;">
                    <strong>Kode:</strong> <?= e($item['ItemCode']) ?>
                </p>

                <p style="margin: 0.25rem 0;">
                    <strong>Kategori:</strong> <?= e($item['Category'] ?? '-') ?>
                </p>

                <p style="margin: 0.25rem 0;">
                    <strong>Lokasi:</strong> <?= e($item['Location'] ?? '-') ?>
                </p>

                <p style="margin: 0.25rem 0;">
                    <strong>Kondisi:</strong> <?= e($item['ItemCondition'] ?? '-') ?>
                </p>

                <p style="margin: 0.25rem 0;">
                    <strong>Stok:</strong> <?= (int) $item['Quantity'] ?>
                </p>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php
app_footer();
