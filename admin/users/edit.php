<?php
require_once '../../config/db.php';
require_once '../../config/auth.php';
require_once '../../config/constants.php';
require_once '../../core/session.php';
require_once '../../core/flash.php';
require_once '../../core/helper.php';
require_once '../../core/functions.php';

require_admin();

$id = $_GET['id'] ?? null;
if (!$id) {
    set_flash('error', 'ID user tidak ditemukan.');
    redirect('index.php');
}

// Ambil data user
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    set_flash('error', 'Data user tidak ditemukan.');
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = $_POST['nama_lengkap'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role     = $_POST['role'] ?? 'operator';

    if ($nama && $username) {
        if ($password) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET nama_lengkap = :nama_lengkap, username = :username, password = :password, role = :role WHERE id = :id");
            $stmt->execute([
                'nama_lengkap'     => $nama,
                'username' => $username,
                'password' => $hash,
                'role'     => $role,
                'id'       => $id
            ]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET nama_lengkap = :nama_lengkap, username = :username, role = :role WHERE id = :id");
            $stmt->execute([
                'nama_lengkap'     => $nama,
                'username' => $username,
                'role'     => $role,
                'id'       => $id
            ]);
        }

        set_flash('success', 'Data user berhasil diperbarui.');
        redirect('index.php');
    } else {
        set_flash('error', 'Nama dan Username wajib diisi.');
    }
}
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/sidebar.php'; ?>

<main class="p-6 ml-64">
    <h1 class="text-2xl font-bold mb-4">Edit User</h1>

    <?php show_flash(); ?>

    <form method="POST" class="bg-white p-6 rounded shadow max-w-xl">
        <div class="mb-4">
            <label class="block mb-1 font-medium">Nama</label>
            <input type="text" name="nama_lengkap" value="<?= e($user['nama_lengkap']) ?>" required class="w-full border px-3 py-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-medium">Username</label>
            <input type="text" name="username" value="<?= e($user['username']) ?>" required class="w-full border px-3 py-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-medium">Password Baru (kosongkan jika tidak diganti)</label>
            <input type="password" name="password" class="w-full border px-3 py-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-medium">Role</label>
            <select name="role" class="w-full border px-3 py-2 rounded">
                <option value="operator" <?= $user['role'] === 'operator' ? 'selected' : '' ?>>Operator</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <div class="flex justify-end">
            <a href="index.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded mr-2">Batal</a>
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">Simpan Perubahan</button>
        </div>
    </form>
</main>

<?php include '../partials/footer.php'; ?>