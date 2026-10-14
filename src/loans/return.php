<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../layout.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loanId = filter_var(
        $_POST['loan_id'] ?? 0,
        FILTER_VALIDATE_INT
    );

    if ($loanId === false || $loanId <= 0) {
        $error = 'Peminjaman wajib dipilih.';
    } else {
        try {
            $pdo->beginTransaction();

            // Ambil peminjaman yang masih aktif.
            $stmt = $pdo->prepare(
                'SELECT LoanID, ItemCode
                 FROM loans
                 WHERE LoanID = ? AND ReturnDate IS NULL'
            );
            $stmt->execute([$loanId]);
            $loan = $stmt->fetch();

            if (!$loan) {
                throw new RuntimeException('Peminjaman tidak ditemukan atau sudah dikembalikan.');
            }

            // Tambah stok barang.
            $updateItem = $pdo->prepare(
                'UPDATE items
                 SET Quantity = Quantity + 1
                 WHERE ItemCode = ?'
            );
            $updateItem->execute([$loan['ItemCode']]);

            // Tandai peminjaman sudah dikembalikan.
            $updateLoan = $pdo->prepare(
                'UPDATE loans
                 SET ReturnDate = CURDATE()
                 WHERE LoanID = ?'
            );
            $updateLoan->execute([$loanId]);

            $pdo->commit();

            header('Location: return.php?msg=updated');
            exit;
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $error = 'Pengembalian gagal diproses.';
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $error = $e->getMessage();
        }
    }
}

$activeLoans = $pdo->query(
    'SELECT loans.LoanID,
            borrowers.Name AS BorrowerName,
            items.Name AS ItemName,
            items.ItemCode,
            loans.LoanDate
     FROM loans
     JOIN borrowers ON loans.BorrowerID = borrowers.BorrowerID
     JOIN items ON loans.ItemCode = items.ItemCode
     WHERE loans.ReturnDate IS NULL
     ORDER BY loans.LoanDate DESC, loans.LoanID DESC'
)->fetchAll();

app_header('Pengembalian Barang');
?>

<h2>Pengembalian Barang</h2>

<?php if ($error !== null): ?>
    <div class="error"><?= e($error) ?></div>
<?php endif; ?>

<h3>Peminjaman yang Belum Dikembalikan</h3>

<?php if (count($activeLoans) === 0): ?>
    <p class="muted">
        Tidak ada peminjaman aktif.
    </p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Peminjam</th>
                <th>Barang</th>
                <th>Kode Barang</th>
                <th>Tanggal Pinjam</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($activeLoans as $loan): ?>
                <tr>
                    <td><?= (int) $loan['LoanID'] ?></td>
                    <td><?= e($loan['BorrowerName']) ?></td>
                    <td><?= e($loan['ItemName']) ?></td>
                    <td><?= e($loan['ItemCode']) ?></td>
                    <td><?= e($loan['LoanDate']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<h3>Proses Pengembalian</h3>

<form method="post">
    <label>
        Pilih Peminjaman Aktif
        <select name="loan_id" required>
            <option value="">-- Pilih Peminjaman --</option>
            <?php foreach ($activeLoans as $loan): ?>
                <option value="<?= (int) $loan['LoanID'] ?>">
                    #<?= (int) $loan['LoanID'] ?> - <?= e($loan['BorrowerName']) ?> - <?= e($loan['ItemName']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <div class="form-action">
        <button type="submit">Proses Pengembalian</button>
        <a class="button button-secondary" href="borrow.php">Ke Halaman Peminjaman</a>
    </div>
</form>

<?php
app_footer();
