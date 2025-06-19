<?php
require_once '../../../config/db.php';
header('Content-Type: application/json');

// Ambil input JSON
$input = json_decode(file_get_contents('php://input'), true);

// Validasi input
if (!isset($input['id'], $input['pejabat_id'], $input['status'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Data wajib (id, pejabat_id, status) harus diisi.'
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE keberadaan 
                           SET pejabat_id = ?, status = ?, updated_at = NOW()
                           WHERE id = ?");
    $stmt->execute([
        $input['pejabat_id'],
        $input['status'],
        $input['id']
    ]);

    if ($stmt->rowCount()) {
        echo json_encode([
            'success' => true,
            'message' => 'Data keberadaan berhasil diperbarui.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Tidak ada perubahan data atau ID tidak ditemukan.'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ]);
}