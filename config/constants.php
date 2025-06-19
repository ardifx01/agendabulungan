<?php
// Base URL aplikasi (sesuaikan dengan folder jika tidak di root)
define('BASE_URL', 'http://localhost/agendabulungan/');

// Nama aplikasi
define('APP_NAME', 'Agenda Bulungan');

// Path ke folder uploads (untuk menyimpan file)
define('UPLOAD_PATH', __DIR__ . '/../admin/uploads/');
define('UPLOAD_URL', BASE_URL . 'admin/uploads/');

// Path ke folder assets
define('ASSETS_URL', BASE_URL . 'assets/');

// Default timezone
date_default_timezone_set('Asia/Makassar');
?>
