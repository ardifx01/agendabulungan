<?php
require_once '../config/db.php';
require_once '../config/auth.php';
require_once '../config/constants.php';
require_once '../core/session.php';
require_once '../core/flash.php';

if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (login($username, $password)) {
        header('Location: dashboard/index.php');
        exit;
    } else {
        set_flash('error', 'Username atau password salah.');
        header('Location: login.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - <?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen justify-between">

    <main class="flex-grow flex items-center justify-center">
        <div class="w-full max-w-sm bg-white p-6 rounded shadow text-center">
            <img src="<?= ASSETS_URL ?>images/logobulungan.png" alt="Logo Bulungan" class="w-24 h-24 mx-auto mb-3">
            <h1 class="text-xl font-bold text-gray-800 mb-1">AGENDA BULUNGAN</h1>
            <p class="text-sm text-gray-500 mb-5">Silakan login untuk masuk ke sistem</p>

            <?php show_flash(); ?>

            <form method="POST" action="">
                <div class="mb-4 text-left">
                    <label for="username" class="block mb-1 font-medium">Username</label>
                    <input type="text" name="username" id="username" required class="w-full px-3 py-2 border rounded">
                </div>
                <div class="mb-4 text-left">
                    <label for="password" class="block mb-1 font-medium">Password</label>
                    <input type="password" name="password" id="password" required class="w-full px-3 py-2 border rounded">
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Login</button>
            </form>
        </div>
    </main>

    <footer class="text-center text-sm text-gray-500 py-4">
        <?php include 'partials/footer.php'; ?>
    </footer>

</body>
</html>