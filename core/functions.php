<?php
if (!function_exists('e')) {
    function e($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('is_admin')) {
    function is_admin() {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
    }
}
