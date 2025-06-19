<?php
require_once('../../../config/db.php');
header('Content-Type: application/json');

$response = ['success' => false, 'message' => '', 'data' => null];

// Ambil dan validasi input JSON
$input = json_decode(file_get_contents("php://input"), true);

if (!$input) {
    $response['message'] = 'Input tidak valid';
    echo json_encode($response);
    exit;
}

$judul       = $input['judul'] ?? null;
$lokasi      = $input['lokasi'] ?? null;
$tanggal     = $input['tanggal'] ?? null;
$waktu       = $input['waktu'] ?? null;
$pejabat_id  = $input['pejabat_id'] ?? null;
$undangan    = $input['undangan'] ?? null; // bisa kosong/null

// Validasi sederhana
if (!$judul || !$tanggal) {
    $response['message'] = 'Judul dan Tanggal wajib diisi';
    echo json_encode($response);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO agenda (judul, lokasi, tanggal, waktu, pejabat_id, undangan) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$judul, $lokasi, $tanggal, $waktu, $pejabat_id, $undangan]);

    $response['success'] = true;
    $response['message'] = 'Agenda berhasil disimpan';
    $response['data'] = [
        'id' => $pdo->lastInsertId(),
        'judul' => $judul,
        'lokasi' => $lokasi,
        'tanggal' => $tanggal,
        'waktu' => $waktu,
        'pejabat_id' => $pejabat_id,
        'undangan' => $undangan
    ];
} catch (PDOException $e) {
    $response['message'] = 'Gagal menyimpan data: ' . $e->getMessage();
}

echo json_encode($response);