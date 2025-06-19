<?php
require_once '../../../config/db.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

// Validasi input
if (!isset($input['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Parameter id wajib disertakan.'
    ]);
    exit;
}

$id = $input['id'];

try {
    $stmt = $pdo->prepare("DELETE FROM keberadaan WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount()) {
        echo json_encode([
            'success' => true,
            'message' => 'Data keberadaan berhasil dihapus.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Data tidak ditemukan atau sudah dihapus sebelumnya.'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ]);
}