<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../layout.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM borrowers WHERE BorrowerID = ?');
$stmt->execute([$id]);
$borrower = $stmt->fetch();

if (!$borrower) {
    header('Location: list.php?msg=not_found');
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if ($name === '') {
        $error = 'Nama peminjam wajib diisi.';
    } elseif ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $error = 'Format email tidak valid.';
    } else {
        try {
            $stmt = $pdo->prepare(
                'UPDATE borrowers
                 SET Name = ?, Email = ?, Phone = ?
                 WHERE BorrowerID = ?'
            );

            $stmt->execute([
                $name,
                $email !== '' ? $email : null,
                $phone !== '' ? $phone : null,
                $id,
            ]);

            header('Location: list.php?msg=updated');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $error = 'Email sudah terdaftar.';
            } else {
                $error = 'Data gagal diperbarui.';
            }
        }
    }
}

app_header('Ubah Peminjam');
?>

<h2>Ubah Peminjam</h2>

<p class="muted">
    ID Peminjam: <strong><?= (int) $borrower['BorrowerID'] ?></strong>
</p>

<?php if ($error !== null): ?>
    <div class="error"><?= e($error) ?></div>
<?php endif; ?>

<form method="post">
    <label>
        Nama
        <input type="text" name="name" value="<?= e($borrower['Name']) ?>" required>
    </label>

    <label>
        Email
        <input type="email" name="email" value="<?= e($borrower['Email'] ?? '') ?>">
    </label>

    <label>
        Telepon
        <input type="text" name="phone" value="<?= e($borrower['Phone'] ?? '') ?>">
    </label>

    <div class="form-action">
        <button type="submit">Simpan Perubahan</button>
        <a class="button button-secondary" href="list.php">Batal</a>
    </div>
</form>

<?php
app_footer();
