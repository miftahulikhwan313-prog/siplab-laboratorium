<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';

$code = $_GET['code'] ?? '';

try {
    $stmt = $pdo->prepare('DELETE FROM items WHERE ItemCode = ?');
    $stmt->execute([$code]);

    if ($stmt->rowCount() > 0) {
        header('Location: list.php?msg=deleted');
    } else {
        header('Location: list.php?msg=not_found');
    }

    exit;
} catch (PDOException $e) {
    header('Location: list.php?msg=error_delete');
    exit;
}
