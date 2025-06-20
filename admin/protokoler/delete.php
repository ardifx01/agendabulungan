<?php
require_once '../../config/db.php';
require_once '../../config/auth.php';
require_once '../../config/constants.php';
require_once '../../core/session.php';
require_once '../../core/flash.php';
require_once '../../core/functions.php';
require_once '../../core/helper.php';

if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: ../dashboard.php');
    exit;
}

if (!is_logged_in()) {
    redirect('../login.php');
}

$id = $_GET['id'] ?? null;
if (!$id) {
    set_flash('error', 'ID protokoler tidak valid.');
    redirect('index.php');
}

// Cek apakah data ada
$stmt = $pdo->prepare("SELECT * FROM protokoler WHERE id = :id");
$stmt->execute(['id' => $id]);
$protokoler = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$protokoler) {
    set_flash('error', 'Data protokoler tidak ditemukan.');
    redirect('index.php');
}

// Hapus data dari database
$stmt = $pdo->prepare("DELETE FROM protokoler WHERE id = :id");
$stmt->execute(['id' => $id]);

set_flash('success', 'Data protokoler berhasil dihapus.');
redirect('index.php');