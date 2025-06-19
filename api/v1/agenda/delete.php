<?php
require_once '../../../config/db.php'; // koneksi ke database
header('Content-Type: application/json');

// Cek metode
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(['message' => 'Metode tidak diizinkan']);
    exit;
}

// Ambil ID dari parameter query
parse_str(file_get_contents("php://input"), $_DELETE);
$id = isset($_DELETE['id']) ? intval($_DELETE['id']) : 0;

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['message' => 'ID tidak valid']);
    exit;
}

// Cek apakah data agenda ada
$stmt = $pdo->prepare("SELECT * FROM agenda WHERE id = ?");
$stmt->execute([$id]);
$agenda = $stmt->fetch();

if (!$agenda) {
    http_response_code(404);
    echo json_encode(['message' => 'Data tidak ditemukan']);
    exit;
}

try {
    // Hapus peliput dan protokoler terkait
    $pdo->beginTransaction();
    
    $pdo->prepare("DELETE FROM agenda_peliput WHERE agenda_id = ?")->execute([$id]);
    $pdo->prepare("DELETE FROM agenda_protokoler WHERE agenda_id = ?")->execute([$id]);

    // Hapus agenda
    $pdo->prepare("DELETE FROM agenda WHERE id = ?")->execute([$id]);
    
    $pdo->commit();
    echo json_encode(['message' => 'Data berhasil dihapus']);
} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['message' => 'Gagal menghapus data', 'error' => $e->getMessage()]);
}
?>