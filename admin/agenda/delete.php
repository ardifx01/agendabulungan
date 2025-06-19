<?php
require_once '../../config/db.php';
require_once '../../config/auth.php';
require_once '../../core/session.php';
require_once '../../core/flash.php';
require_once '../../core/functions.php';

require_login();

$id = $_GET['id'] ?? 0;

// Ambil data agenda untuk cek file undangan
$stmt = $pdo->prepare("SELECT * FROM agenda WHERE id = ?");
$stmt->execute([$id]);
$agenda = $stmt->fetch();

if (!$agenda) {
    set_flash('error', 'Data agenda tidak ditemukan.');
    header('Location: index.php');
    exit;
}

// Hapus file undangan jika ada
if (!empty($agenda['undangan']) && file_exists('../uploads/agenda/' . $agenda['undangan'])) {
    unlink('../uploads/agenda/' . $agenda['undangan']);
}

// Hapus relasi peliput dan protokoler
$pdo->prepare("DELETE FROM agenda_peliput WHERE agenda_id = ?")->execute([$id]);
$pdo->prepare("DELETE FROM agenda_protokoler WHERE agenda_id = ?")->execute([$id]);

// Hapus agenda utama
$pdo->prepare("DELETE FROM agenda WHERE id = ?")->execute([$id]);

set_flash('success', 'Agenda berhasil dihapus.');
header('Location: index.php');
exit;