<?php
require_once '../../config/db.php';
require_once '../../config/auth.php';
require_once '../../core/functions.php';
require_login();

$page_title = "Data Keberadaan Pejabat";
include '../partials/header.php';
include '../partials/sidebar.php';

// Ambil data keberadaan dan join dengan data pejabat
$stmt = $pdo->query("
    SELECT k.id, p.nama AS nama_pejabat, k.status, k.updated_at 
    FROM keberadaan k 
    JOIN pejabat p ON k.pejabat_id = p.id 
    ORDER BY k.updated_at DESC
");
$keberadaan = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="p-4 sm:ml-64">
    <div class="p-4 rounded-lg mt-14">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">Data Keberadaan Pejabat</h1>
            <a href="tambah.php" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Tambah Data
            </a>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left font-semibold">No</th>
                        <th class="px-4 py-2 text-left font-semibold">Nama Pejabat</th>
                        <th class="px-4 py-2 text-left font-semibold">Status</th>
                        <th class="px-4 py-2 text-left font-semibold">Terakhir Diperbarui</th>
                        <th class="px-4 py-2 text-left font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($keberadaan as $index => $data): ?>
                        <tr>
                            <td class="px-4 py-2"><?= $index + 1 ?></td>
                            <td class="px-4 py-2"><?= e($data['nama_pejabat']) ?></td>
                            <td class="px-4 py-2"><?= e($data['status']) ?></td>
                            <td class="px-4 py-2"><?= e($data['updated_at']) ?></td>
                            <td class="px-4 py-2">
                                <a href="edit.php?id=<?= $data['id'] ?>" class="text-blue-600 hover:underline">Edit</a>
                                |
                                <a href="delete.php?id=<?= $data['id'] ?>" class="text-red-600 hover:underline" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    <?php if (empty($keberadaan)): ?>
                        <tr>
                            <td colspan="5" class="px-4 py-2 text-center text-gray-500">Belum ada data keberadaan.</td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>