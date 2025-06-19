<?php
require_once '../../../config/db.php';
header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Parameter id wajib disertakan.'
    ]);
    exit;
}

$id = $_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT k.id, k.status, k.updated_at, 
                                  p.nama AS pejabat_nama
                           FROM keberadaan k
                           JOIN pejabat p ON k.pejabat_id = p.id
                           WHERE k.id = ?");
    $stmt->execute([$id]);
    $data = $stmt->fetch();

    if (!$data) {
        echo json_encode([
            'success' => false,
            'message' => 'Data keberadaan tidak ditemukan.'
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ]);
}