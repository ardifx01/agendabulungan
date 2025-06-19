<?php
if (!function_exists('set_flash')) {
    function set_flash($type, $message) {
        $_SESSION['flash'][$type] = $message;
    }
}

if (!function_exists('show_flash')) {
    function show_flash() {
        if (!empty($_SESSION['flash'])) {
            foreach ($_SESSION['flash'] as $type => $message) {
                echo "<div class='p-3 mb-3 text-sm text-white rounded bg-" . ($type === 'success' ? 'green' : 'red') . "-600'>{$message}</div>";
            }
            unset($_SESSION['flash']);
        }
    }
}
