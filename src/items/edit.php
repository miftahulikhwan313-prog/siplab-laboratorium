<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../layout.php';

$code = $_GET['code'] ?? '';

$stmt = $pdo->prepare('SELECT * FROM items WHERE ItemCode = ?');
$stmt->execute([$code]);
$item = $stmt->fetch();

if (!$item) {
    header('Location: list.php?msg=not_found');
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name          = trim($_POST['name'] ?? '');
    $category      = trim($_POST['category'] ?? '');
    $location      = trim($_POST['location'] ?? '');
    $itemCondition = trim($_POST['item_condition'] ?? '');
    $quantity      = filter_var(
        $_POST['quantity'] ?? 0,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 0]]
    );

    if ($name === '' || $quantity === false) {
        $error = 'Nama barang dan stok wajib diisi dengan benar.';
    } else {
        try {
            $stmt = $pdo->prepare(
                'UPDATE items
                 SET Name = ?, Category = ?, Location = ?, ItemCondition = ?, Quantity = ?
                 WHERE ItemCode = ?'
            );

            $stmt->execute([
                $name,
                $category !== '' ? $category : null,
                $location !== '' ? $location : null,
                $itemCondition !== '' ? $itemCondition : null,
                $quantity,
                $code,
            ]);

            header('Location: list.php?msg=updated');
            exit;
        } catch (PDOException $e) {
            $error = 'Data gagal diperbarui.';
        }
    }
}

app_header('Ubah Barang');
?>

<h2>Ubah Barang</h2>

<p class="muted">
    Kode Barang: <strong><?= e($item['ItemCode']) ?></strong>
</p>

<?php if ($error !== null): ?>
    <div class="error"><?= e($error) ?></div>
<?php endif; ?>

<form method="post">
    <label>
        Nama Barang
        <input type="text" name="name" value="<?= e($item['Name']) ?>" required>
    </label>

    <label>
        Kategori
        <input type="text" name="category" value="<?= e($item['Category'] ?? '') ?>">
    </label>

    <label>
        Lokasi
        <input type="text" name="location" value="<?= e($item['Location'] ?? '') ?>">
    </label>

    <label>
        Kondisi
        <input type="text" name="item_condition" value="<?= e($item['ItemCondition'] ?? '') ?>">
    </label>

    <label>
        Stok
        <input type="number" name="quantity" min="0" value="<?= (int) $item['Quantity'] ?>" required>
    </label>

    <div class="form-action">
        <button type="submit">Simpan Perubahan</button>
        <a class="button button-secondary" href="list.php">Batal</a>
    </div>
</form>

<?php
app_footer();
