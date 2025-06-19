<?php
require_once '../../config/db.php';
require_once '../../core/functions.php';
require_once '../../config/auth.php';
require_once '../../core/flash.php';
require_once '../../core/helper.php';

if (!is_logged_in() || !is_admin()) {
    set_flash('error', 'Anda tidak memiliki akses.');
    redirect('../dashboard');
}

$id = $_GET['id'] ?? null;
if (!$id) {
    set_flash('error', 'ID tidak ditemukan.');
    redirect('index.php');
}

try {
    $stmt = $pdo->prepare("DELETE FROM pidato WHERE id = ?");
    $stmt->execute([$id]);

    set_flash('success', 'Data pidato berhasil dihapus.');
} catch (PDOException $e) {
    set_flash('error', 'Gagal menghapus data: ' . $e->getMessage());
}

redirect('index.php');