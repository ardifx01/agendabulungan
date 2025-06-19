<?php
require_once '../../config/db.php';
require_once '../../core/functions.php';
require_once '../../config/auth.php';
require_once '../../core/helper.php';

if (!is_logged_in()) {
    redirect('../login.php');
}

$title = 'Daftar Pidato';
require_once '../partials/header.php';
require_once '../partials/sidebar.php';

// Ambil data pidato hanya dari agenda hari ini dan mendatang
$query = "
    SELECT p.id, a.judul AS acara, a.tanggal
    FROM pidato p
    JOIN agenda a ON p.id_agenda = a.id
    WHERE a.tanggal >= CURDATE()
    ORDER BY a.tanggal ASC
";
$stmt = $pdo->query($query);
$pidatos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="p-4 sm:ml-64">
    <div class="mt-10">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-2 md:gap-0 mb-4">
            <h1 class="text-2xl font-bold">Daftar Pidato</h1>
            <div class="flex gap-2">
                <a href="arsip.php" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Lihat Arsip</a>
                <a href="tambah.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Tambah Pidato</a>
            </div>
        </div>

        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium">No</th>
                        <th class="px-4 py-2 text-left text-sm font-medium">Acara</th>
                        <th class="px-4 py-2 text-left text-sm font-medium">Tanggal</th>
                        <th class="px-4 py-2 text-left text-sm font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if ($pidatos): ?>
                        <?php foreach ($pidatos as $i => $p): ?>
                            <tr>
                                <td class="px-4 py-2 text-sm"><?= $i + 1 ?></td>
                                <td class="px-4 py-2 text-sm"><?= htmlspecialchars($p['acara']) ?></td>
                                <td class="px-4 py-2 text-sm"><?= format_tanggal($p['tanggal']) ?></td>
                                <td class="px-4 py-2 text-sm space-x-2">
                                    <a href="detail.php?id=<?= $p['id'] ?>" class="text-blue-600 hover:underline">Detail</a>
                                    <a href="edit.php?id=<?= $p['id'] ?>" class="text-blue-600 hover:underline">Edit</a>
                                    <a href="delete.php?id=<?= $p['id'] ?>" class="text-red-600 hover:underline" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="px-4 py-2 text-sm text-center text-gray-500">Belum ada pidato untuk agenda hari ini atau mendatang.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../partials/footer.php'; ?>