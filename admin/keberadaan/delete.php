<?php
require_once '../../config/db.php';
require_once '../../config/auth.php';
require_once '../../config/constants.php';
require_once '../../core/session.php';
require_once '../../core/flash.php';
require_once '../../core/functions.php';
require_once '../../core/helper.php';

if (!is_logged_in()) {
    redirect('../login.php');
}

$id = $_GET['id'] ?? null;
if (!$id) {
    set_flash('error', 'ID keberadaan tidak valid.');
    redirect('index.php');
}

// Cek apakah data ada
$stmt = $pdo->prepare("SELECT * FROM keberadaan WHERE id = :id");
$stmt->execute(['id' => $id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    set_flash('error', 'Data keberadaan tidak ditemukan.');
    redirect('index.php');
}

// Lakukan penghapusan
$stmt = $pdo->prepare("DELETE FROM keberadaan WHERE id = :id");
$stmt->execute(['id' => $id]);

set_flash('success', 'Data keberadaan berhasil dihapus.');
redirect('index.php');