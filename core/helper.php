<?php

if (!function_exists('redirect')) {
    function redirect($url)
    {
        header("Location: $url");
        exit;
    }
}

if (!function_exists('current_user')) {
    function current_user()
    {
        return $_SESSION['user'] ?? null;
    }
}

if (!function_exists('is_admin')) {
    function is_admin()
    {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
    }
}

if (!function_exists('require_admin')) {
    function require_admin()
    {
        if (!is_admin()) {
            redirect('../dashboard/index.php');
        }
    }
}

if (!function_exists('is_operator')) {
    function is_operator()
    {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'operator';
    }
}

if (!function_exists('require_login')) {
    function require_login()
    {
        if (!isset($_SESSION['user'])) {
            redirect('../login.php');
        }
    }
}

if (!function_exists('format_tanggal')) {
    function format_tanggal($tanggal)
    {
        // Format default: 2025-06-20 → 20 Juni 2025
        $bulan = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];
        $tanggal = explode('-', $tanggal);
        return $tanggal[2] . ' ' . $bulan[$tanggal[1]] . ' ' . $tanggal[0];
    }
}