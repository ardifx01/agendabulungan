<?php
// Pastikan session dimulai hanya sekali
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Fungsi untuk set flash message
function set_flash($key, $message) {
    $_SESSION['flash'][$key] = $message;
}

// Fungsi untuk ambil dan hapus flash message
function get_flash($key) {
    if (isset($_SESSION['flash'][$key])) {
        $message = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $message;
    }
    return null;
}
