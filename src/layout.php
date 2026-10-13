<?php
declare(strict_types=1);

/**
 * Fungsi bantu untuk menampilkan halaman HTML SIPLAB.
 * File ini dipakai oleh halaman barang, peminjam, dan fitur lain.
 */

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function flash_message(): ?string
{
    $msg = $_GET['msg'] ?? null;

    if ($msg === 'created') {
        return 'Data berhasil disimpan.';
    }

    if ($msg === 'updated') {
        return 'Data berhasil diperbarui.';
    }

    if ($msg === 'deleted') {
        return 'Data berhasil dihapus.';
    }

    if ($msg === 'not_found') {
        return 'Data tidak ditemukan.';
    }

    if ($msg === 'error_delete') {
        return 'Data tidak dapat dihapus karena sudah memiliki transaksi peminjaman.';
    }

    if ($msg === 'error_save') {
        return 'Data gagal disimpan.';
    }

    return null;
}

function app_header(string $title): void
{
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?> - SIPLAB</title>
    <style>
        :root {
            --primary: #14756e;
            --danger: #b03030;
            --border: #d8d8d8;
            --bg-soft: #f7f7f7;
        }

        body {
            font-family: system-ui, sans-serif;
            margin: 0;
            color: #2a2a2a;
            background: #fff;
        }

        header {
            background: var(--primary);
            color: #fff;
            padding: 1rem 1.5rem;
        }

        header h1 {
            margin: 0;
            font-size: 1.5rem;
        }

        header p {
            margin: 0.25rem 0 0;
            color: #eafffd;
        }

        nav {
            background: #0f5b56;
            padding: 0.75rem 1.5rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            font-weight: 600;
        }

        nav a:hover {
            text-decoration: underline;
        }

        main {
            padding: 1.5rem;
        }

        h2 {
            margin-top: 0;
            color: var(--primary);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th,
        td {
            border: 1px solid var(--border);
            padding: 0.55rem 0.7rem;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: var(--bg-soft);
        }

        .button,
        button {
            display: inline-block;
            background: var(--primary);
            color: #fff;
            border: 0;
            border-radius: 4px;
            padding: 0.5rem 0.9rem;
            text-decoration: none;
            cursor: pointer;
        }

        .button:hover,
        button:hover {
            opacity: 0.92;
        }

        .button-secondary {
            background: #666;
        }

        .alert {
            background: #e7f6f5;
            border: 1px solid #b8e2df;
            border-left: 6px solid var(--primary);
            padding: 0.8rem 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .error {
            background: #fdeeee;
            border: 1px solid #f0c1c1;
            border-left: 6px solid var(--danger);
            padding: 0.8rem 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            color: #7c2020;
        }

        form label {
            display: block;
            margin-bottom: 0.9rem;
        }

        form input,
        form select {
            width: min(420px, 100%);
            padding: 0.5rem;
            margin-top: 0.25rem;
            border: 1px solid var(--border);
            border-radius: 4px;
        }

        .form-action {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            margin-top: 1rem;
        }

        .muted {
            color: #666;
        }
    </style>
</head>
<body>
<header>
    <h1>SIPLAB</h1>
    <p>Sistem Inventaris dan Peminjaman Barang Laboratorium</p>
</header>
<nav>
    <a href="/">Katalog</a>
    <a href="/items/list.php">Barang</a>
    <a href="/borrowers/list.php">Peminjam</a>
    <a href="/loans/borrow.php">Peminjaman</a>
    <a href="/loans/return.php">Pengembalian</a>
</nav>

<main>
<?php
    $message = flash_message();

    if ($message !== null) {
        echo '<div class="alert">' . e($message) . '</div>';
    }
}

function app_footer(): void
{
    echo '</main></body></html>';
}
