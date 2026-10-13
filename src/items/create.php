<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../layout.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemCode      = trim($_POST['item_code'] ?? '');
    $name          = trim($_POST['name'] ?? '');
    $category      = trim($_POST['category'] ?? '');
    $location      = trim($_POST['location'] ?? '');
    $itemCondition = trim($_POST['item_condition'] ?? '');
    $quantity      = filter_var(
        $_POST['quantity'] ?? 0,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 0]]
    );

    if ($itemCode === '' || $name === '' || $quantity === false || strlen($itemCode) > 20) {
        $error = 'Kode barang maksimal 20 karakter, nama barang dan stok wajib diisi dengan benar.';
    } else {
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO items (ItemCode, Name, Category, Location, ItemCondition, Quantity)
                 VALUES (?, ?, ?, ?, ?, ?)'
            );

            $stmt->execute([
                $itemCode,
                $name,
                $category !== '' ? $category : null,
                $location !== '' ? $location : null,
                $itemCondition !== '' ? $itemCondition : null,
                $quantity,
            ]);

            header('Location: list.php?msg=created');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $error = 'Kode barang sudah terdaftar.';
            } else {
                $error = 'Data gagal disimpan.';
            }
        }
    }
}

app_header('Tambah Barang');
?>

<h2>Tambah Barang Baru</h2>

<?php if ($error !== null): ?>
    <div class="error"><?= e($error) ?></div>
<?php endif; ?>

<form method="post">
    <label>
        Kode Barang
        <input type="text" name="item_code" maxlength="20" required>
    </label>

    <label>
        Nama Barang
        <input type="text" name="name" required>
    </label>

    <label>
        Kategori
        <input type="text" name="category" placeholder="Contoh: Elektronik, Aksesori">
    </label>

    <label>
        Lokasi
        <input type="text" name="location" placeholder="Contoh: Lab Komputer">
    </label>

    <label>
        Kondisi
        <input type="text" name="item_condition" placeholder="Contoh: Baik, Rusak Ringan">
    </label>

    <label>
        Stok
        <input type="number" name="quantity" min="0" value="1" required>
    </label>

    <div class="form-action">
        <button type="submit">Simpan</button>
        <a class="button button-secondary" href="list.php">Batal</a>
    </div>
</form>

<?php
app_footer();
