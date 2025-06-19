<?php
require_once '../../config/db.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

// Validasi input
if (!isset($input['pejabat_id'], $input['status_keberadaan'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Data wajib (pejabat_id, status_keberadaan) harus diisi.'
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO keberadaan (pejabat_id, status_keberadaan, waktu_update)
                           VALUES (?, ?, NOW())");
    $stmt->execute([
        $input['pejabat_id'],
        $input['status_keberadaan']
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Data keberadaan berhasil ditambahkan.',
        'id' => $pdo->lastInsertId()
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ]);
}