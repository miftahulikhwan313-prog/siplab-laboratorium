<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../layout.php';

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
                'INSERT INTO borrowers (Name, Email, Phone)
                 VALUES (?, ?, ?)'
            );

            $stmt->execute([
                $name,
                $email !== '' ? $email : null,
                $phone !== '' ? $phone : null,
            ]);

            header('Location: list.php?msg=created');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $error = 'Email sudah terdaftar.';
            } else {
                $error = 'Data gagal disimpan.';
            }
        }
    }
}

app_header('Tambah Peminjam');
?>

<h2>Tambah Peminjam Baru</h2>

<?php if ($error !== null): ?>
    <div class="error"><?= e($error) ?></div>
<?php endif; ?>

<form method="post">
    <label>
        Nama
        <input type="text" name="name" required>
    </label>

    <label>
        Email
        <input type="email" name="email" placeholder="Contoh: nama@example.com">
    </label>

    <label>
        Telepon
        <input type="number" name="phone" placeholder="Contoh: 081234567890">
    </label>

    <div class="form-action">
        <button type="submit">Simpan</button>
        <a class="button button-secondary" href="list.php">Batal</a>
    </div>
</form>

<?php
app_footer();
