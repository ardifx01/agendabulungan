<?php
require_once '../../config/db.php';
require_once '../../config/auth.php';
require_once '../../config/constants.php';
require_once '../../core/session.php';
require_once '../../core/helper.php';
require_once '../../core/flash.php';
require_once '../../core/functions.php';

if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: ../dashboard.php');
    exit;
}

if (!is_logged_in()) {
    redirect('../login.php');
}

// Ambil data peliput
$cari = $_GET['cari'] ?? '';
$query = "SELECT * FROM peliput WHERE nama LIKE :cari OR unit_kerja LIKE :cari ORDER BY nama ASC";
$stmt = $pdo->prepare($query);
$stmt->execute(['cari' => "%$cari%"]);
$peliput = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/sidebar.php'; ?>

<main class="p-6 ml-64">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Data Peliput</h1>
        <a href="tambah.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Tambah Peliput
        </a>
    </div>

    <!-- Form Pencarian -->
    <form method="GET" class="mb-4">
        <input type="text" name="cari" placeholder="Cari nama atau unit kerja..." value="<?= e($cari) ?>" class="px-3 py-2 border rounded w-64">
        <button type="submit" class="ml-2 px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Cari</button>
    </form>

    <?php show_flash(); ?>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded shadow">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-3 py-2 border">No</th>
                    <th class="px-3 py-2 border">Nama</th>
                    <th class="px-3 py-2 border">Jabatan</th>
                    <th class="px-3 py-2 border">Unit Kerja</th>
                    <th class="px-3 py-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($peliput) > 0): ?>
                    <?php $no = 1; foreach ($peliput as $row): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 border text-center"><?= $no++ ?></td>
                            <td class="px-3 py-2 border"><?= e($row['nama']) ?></td>
                            <td class="px-3 py-2 border"><?= e($row['jabatan']) ?: '-' ?></td>
                            <td class="px-3 py-2 border"><?= e($row['unit_kerja']) ?: '-' ?></td>
                            <td class="px-3 py-2 border text-center">
                                <a href="detail.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline text-sm">Detail</a> |
                                <a href="edit.php?id=<?= $row['id'] ?>" class="text-yellow-600 hover:underline text-sm">Edit</a> |
                                <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus data ini?')" class="text-red-600 hover:underline text-sm">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500 italic">Data tidak ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include '../partials/footer.php'; ?>