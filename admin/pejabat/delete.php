<?php
require_once '../../config/db.php';
require_once '../../config/auth.php';
require_once '../../config/constants.php';
require_once '../../core/session.php';
require_once '../../core/flash.php';
require_once '../../core/helper.php';
require_once '../../core/functions.php';

if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: ../dashboard.php');
    exit;
}

if (!is_logged_in()) {
    redirect('../login.php');
}

$id = $_GET['id'] ?? null;
if (!$id) {
    set_flash('error', 'ID pejabat tidak valid.');
    redirect('index.php');
}

// Ambil data pejabat
$stmt = $pdo->prepare("SELECT * FROM pejabat WHERE id = :id");
$stmt->execute(['id' => $id]);
$pejabat = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pejabat) {
    set_flash('error', 'Data pejabat tidak ditemukan.');
    redirect('index.php');
}

// Hapus foto jika ada
if (!empty($pejabat['foto'])) {
    $fotoPath = 'admin/uploads/pejabat/' . $pejabat['foto'];
    if (file_exists($fotoPath)) {
        unlink($fotoPath);
    }
}

// Hapus dari database
$stmt = $pdo->prepare("DELETE FROM pejabat WHERE id = :id");
$stmt->execute(['id' => $id]);

set_flash('success', 'Data pejabat berhasil dihapus.');
redirect('index.php');