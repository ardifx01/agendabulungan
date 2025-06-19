<?php
require_once '../../config/db.php';
require_once '../../config/auth.php';
require_once '../../config/constants.php';
require_once '../../core/session.php';
require_once '../../core/flash.php';
require_once '../../core/helper.php';
require_once '../../core/functions.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = $_POST['nama'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role     = $_POST['role'] ?? 'operator';

    if ($nama && $username && $password) {
        // Hash password
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (nama_lengkap, username, password, role) VALUES (:nama, :username, :password, :role)");
        $stmt->execute([
            'nama'     => $nama,
            'username' => $username,
            'password' => $hash,
            'role'     => $role
        ]);

        set_flash('success', 'User berhasil ditambahkan.');
        redirect('index.php');
    } else {
        set_flash('error', 'Semua field wajib diisi.');
    }
}
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/sidebar.php'; ?>

<main class="p-6 ml-64">
    <h1 class="text-2xl font-bold mb-4">Tambah User</h1>

    <?php show_flash(); ?>

    <form method="POST" class="bg-white p-6 rounded shadow max-w-xl">
        <div class="mb-4">
            <label class="block mb-1 font-medium">Nama</label>
            <input type="text" name="nama" required class="w-full border px-3 py-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-medium">Username</label>
            <input type="text" name="username" required class="w-full border px-3 py-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-medium">Password</label>
            <input type="password" name="password" required class="w-full border px-3 py-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-medium">Role</label>
            <select name="role" class="w-full border px-3 py-2 rounded">
                <option value="operator">Operator</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <div class="flex justify-end">
            <a href="index.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded mr-2">Batal</a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Simpan</button>
        </div>
    </form>
</main>

<?php include '../partials/footer.php'; ?>