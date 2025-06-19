<?php
require_once '../../config/db.php';
require_once '../../config/auth.php';
require_once '../../config/constants.php';
require_once '../../core/session.php';
require_once '../../core/helper.php';
require_once '../../core/flash.php';
require_once '../../core/functions.php';

require_admin();

// Ambil data semua user
$stmt = $pdo->query("SELECT * FROM users ORDER BY username ASC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/sidebar.php'; ?>

<main class="p-6 ml-64">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Manajemen User</h1>
        <a href="tambah.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Tambah User</a>
    </div>

    <?php show_flash(); ?>

    <div class="overflow-x-auto bg-white shadow rounded">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-2">No</th>
                    <th class="px-4 py-2">Nama</th>
                    <th class="px-4 py-2">Username</th>
                    <th class="px-4 py-2">Role</th>
                    <th class="px-4 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $i => $user): ?>
                <tr class="border-t">
                    <td class="px-4 py-2"><?= $i + 1 ?></td>
                    <td class="px-4 py-2"><?= e($user['nama_lengkap']) ?></td>
                    <td class="px-4 py-2"><?= e($user['username']) ?></td>
                    <td class="px-4 py-2"><?= e(ucfirst($user['role'])) ?></td>
                    <td class="px-4 py-2 text-center space-x-2">
                        <a href="edit.php?id=<?= $user['id'] ?>" class="text-blue-600 hover:underline text-sm">Edit</a>
                        <a href="delete.php?id=<?= $user['id'] ?>" class="text-red-600 hover:underline text-sm" onclick="return confirm('Hapus user ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</main>

<?php include '../partials/footer.php'; ?>