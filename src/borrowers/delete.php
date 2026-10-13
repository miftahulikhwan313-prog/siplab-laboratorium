<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';

$id = (int) ($_GET['id'] ?? 0);

try {
    $stmt = $pdo->prepare('DELETE FROM borrowers WHERE BorrowerID = ?');
    $stmt->execute([$id]);

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
