<?php
require_once '../../../config/db.php';

header('Content-Type: application/json');

// Validasi parameter
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Parameter id tidak valid']);
    exit;
}

$id = (int) $_GET['id'];

// Ambil data agenda
$query = $pdo->prepare("
    SELECT 
        a.id, a.judul, a.lokasi, a.tanggal, a.waktu, a.undangan,
        p.nama AS nama_pejabat, p.jabatan, p.unit_kerja
    FROM agenda a
    LEFT JOIN pejabat p ON a.pejabat_id = p.id
    WHERE a.id = :id
");
$query->execute(['id' => $id]);
$agenda = $query->fetch(PDO::FETCH_ASSOC);

if (!$agenda) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
    exit;
}

// Ambil peliput
$peliput = $pdo->prepare("
    SELECT peliput.id, peliput.nama, peliput.jabatan, peliput.unit_kerja 
    FROM agenda_peliput 
    JOIN peliput ON agenda_peliput.peliput_id = peliput.id 
    WHERE agenda_peliput.agenda_id = :id
");
$peliput->execute(['id' => $id]);
$agenda['peliput'] = $peliput->fetchAll(PDO::FETCH_ASSOC);

// Ambil protokoler
$protokoler = $pdo->prepare("
    SELECT protokoler.id, protokoler.nama, protokoler.jabatan, protokoler.unit_kerja 
    FROM agenda_protokoler 
    JOIN protokoler ON agenda_protokoler.protokoler_id = protokoler.id 
    WHERE agenda_protokoler.agenda_id = :id
");
$protokoler->execute(['id' => $id]);
$agenda['protokoler'] = $protokoler->fetchAll(PDO::FETCH_ASSOC);

// Response JSON
echo json_encode([
    'status' => 'success',
    'data' => $agenda
], JSON_PRETTY_PRINT);