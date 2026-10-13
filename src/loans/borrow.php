<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../layout.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $borrowerId = filter_var(
        $_POST['borrower_id'] ?? 0,
        FILTER_VALIDATE_INT
    );

    $itemCode = trim($_POST['item_code'] ?? '');

    if ($borrowerId === false || $borrowerId <= 0 || $itemCode === '') {
        $error = 'Peminjam dan barang wajib dipilih.';
    } else {
        try {
            $pdo->beginTransaction();

            // Cek apakah barang tersedia.
            $check = $pdo->prepare(
                'SELECT Quantity
                 FROM items
                 WHERE ItemCode = ? AND Quantity > 0'
            );
            $check->execute([$itemCode]);
            $availableItem = $check->fetch();

            if (!$availableItem) {
                throw new RuntimeException('Stok barang habis atau kode barang tidak ditemukan.');
            }

            /*
             * =====================================================
             * BUG DISENGAJA UNTUK SIMULASI QA
             * =====================================================
             *
             * Seharusnya di sini ada query untuk mengurangi stok:
             *
             * UPDATE items
             * SET Quantity = Quantity - 1
             * WHERE ItemCode = ? AND Quantity > 0;
             *
             * Namun query tersebut sengaja tidak dijalankan,
             * sehingga peminjaman tersimpan tetapi stok tidak berkurang.
             *
             * Bug ini akan ditemukan oleh QA pada tahap berikutnya.
             * Jangan diperbaiki dulu sampai Bagian 6.
             * =====================================================
             */

            // Simpan transaksi peminjaman.
            $insert = $pdo->prepare(
                'INSERT INTO loans (BorrowerID, ItemCode, LoanDate)
                 VALUES (?, ?, CURDATE())'
            );
            $insert->execute([$borrowerId, $itemCode]);

            $pdo->commit();

            header('Location: borrow.php?msg=created');
            exit;
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            if ($e->getCode() === '23000') {
                $error = 'Peminjam atau barang tidak valid.';
            } else {
                $error = 'Peminjaman gagal disimpan.';
            }
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $error = $e->getMessage();
        }
    }
}

$borrowers = $pdo->query(
    'SELECT BorrowerID, Name
     FROM borrowers
     ORDER BY Name'
)->fetchAll();

$items = $pdo->query(
    'SELECT ItemCode, Name, Quantity
     FROM items
     WHERE Quantity > 0
     ORDER BY Name'
)->fetchAll();

app_header('Peminjaman Barang');
?>

<h2>Peminjaman Barang</h2>

<?php if ($error !== null): ?>
    <div class="error"><?= e($error) ?></div>
<?php endif; ?>

<form method="post">
    <label>
        Peminjam
        <select name="borrower_id" required>
            <option value="">-- Pilih Peminjam --</option>
            <?php foreach ($borrowers as $borrower): ?>
                <option value="<?= (int) $borrower['BorrowerID'] ?>">
                    <?= e($borrower['Name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>
        Barang
        <select name="item_code" required>
            <option value="">-- Pilih Barang --</option>
            <?php foreach ($items as $item): ?>
                <option value="<?= e($item['ItemCode']) ?>">
                    <?= e($item['Name']) ?> (<?= e($item['ItemCode']) ?>) - Stok: <?= (int) $item['Quantity'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <div class="form-action">
        <button type="submit">Simpan Peminjaman</button>
        <a class="button button-secondary" href="return.php">Ke Halaman Pengembalian</a>
    </div>
</form>

<?php
app_footer();
