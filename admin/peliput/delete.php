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
    set_flash('error', 'ID peliput tidak valid.');
    redirect('index.php');
}

// Ambil data peliput
$stmt = $pdo->prepare("SELECT * FROM peliput WHERE id = :id");
$stmt->execute(['id' => $id]);
$peliput = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$peliput) {
    set_flash('error', 'Data peliput tidak ditemukan.');
    redirect('index.php');
}

// Hapus data dari database
$stmt = $pdo->prepare("DELETE FROM peliput WHERE id = :id");
$stmt->execute(['id' => $id]);

set_flash('success', 'Data peliput berhasil dihapus.');
redirect('index.php');