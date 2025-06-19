<?php
require_once '../../../config/db.php';
header('Content-Type: application/json');

// Periksa apakah method-nya POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => false, 'message' => 'Metode tidak diizinkan']);
    exit;
}

// Ambil data dari input
$data = json_decode(file_get_contents("php://input"), true);

// Validasi awal
if (!isset($data['id']) || !is_numeric($data['id'])) {
    http_response_code(400);
    echo json_encode(['status' => false, 'message' => 'ID agenda tidak valid']);
    exit;
}

$id = (int) $data['id'];
$judul = $data['judul'] ?? null;
$lokasi = $data['lokasi'] ?? null;
$tanggal = $data['tanggal'] ?? null;
$waktu = $data['waktu'] ?? null;
$pejabat_id = $data['pejabat_id'] ?? null;
$undangan = $data['undangan'] ?? null;

try {
    $stmt = $pdo->prepare("UPDATE agenda SET 
        judul = :judul,
        lokasi = :lokasi,
        tanggal = :tanggal,
        waktu = :waktu,
        pejabat_id = :pejabat_id,
        undangan = :undangan
        WHERE id = :id
    ");

    $stmt->execute([
        ':judul' => $judul,
        ':lokasi' => $lokasi,
        ':tanggal' => $tanggal,
        ':waktu' => $waktu,
        ':pejabat_id' => $pejabat_id,
        ':undangan' => $undangan,
        ':id' => $id
    ]);

    echo json_encode(['status' => true, 'message' => 'Data agenda berhasil diperbarui']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => false, 'message' => 'Gagal memperbarui data agenda', 'error' => $e->getMessage()]);
}