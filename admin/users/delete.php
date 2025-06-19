<?php
require_once '../../config/db.php';
require_once '../../config/auth.php';
require_once '../../core/flash.php';
require_once '../../core/helper.php';
require_once '../../core/functions.php';

require_admin();

$id = $_GET['id'] ?? null;
if (!$id) {
    set_flash('error', 'ID user tidak ditemukan.');
    redirect('index.php');
}

// Cegah admin menghapus dirinya sendiri
if ($_SESSION['user']['id'] == $id) {
    set_flash('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
    redirect('index.php');
}

// Hapus user
$stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
$stmt->execute(['id' => $id]);

set_flash('success', 'User berhasil dihapus.');
redirect('index.php');