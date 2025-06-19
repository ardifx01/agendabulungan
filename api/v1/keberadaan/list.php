<?php
header('Content-Type: application/json');
require_once '../../../config/db.php';

try {
    $stmt = $pdo->prepare("
        SELECT 
            k.id,
            k.pejabat_id,
            p.nama AS nama_pejabat,
            p.jabatan,
            p.unit_kerja,
            k.status,
            k.updated_at
        FROM keberadaan k
        INNER JOIN pejabat p ON k.pejabat_id = p.id
        ORDER BY k.updated_at DESC
    ");
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data' => $data
    ], JSON_PRETTY_PRINT);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}