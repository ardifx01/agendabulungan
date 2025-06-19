<?php
require_once '../../config/db.php';
require_once '../../config/auth.php';
require_once '../../core/functions.php';
require_once '../../core/flash.php';

if (!is_logged_in() || !is_admin()) {
    set_flash('error', 'Anda tidak memiliki akses.');
    redirect('../dashboard');
}

$title = 'Arsip Pidato';

// Paginasi
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$today = date('Y-m-d');

// Hitung total data
$stmtCount = $pdo->prepare("SELECT COUNT(*) FROM pidato p JOIN agenda a ON p.id_agenda = a.id WHERE a.tanggal < ?");
$stmtCount->execute([$today]);
$totalData = $stmtCount->fetchColumn();
$totalPages = ceil($totalData / $limit);

// Ambil data pidato
$stmt = $pdo->prepare("
    SELECT p.*, a.judul AS agenda_judul, a.tanggal
    FROM pidato p
    JOIN agenda a ON p.id_agenda = a.id
    WHERE a.tanggal < ?
    ORDER BY a.tanggal DESC
    LIMIT $limit OFFSET $offset
");
$stmt->execute([$today]);
$pidatos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/sidebar.php'; ?>

<div class="p-4 sm:ml-64">
    <div class="mt-14">
        <h1 class="text-2xl font-bold mb-4"><?= $title ?></h1>
        <?= show_flash(); ?>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 border">No</th>
                        <th class="px-4 py-2 border">Agenda</th>
                        <th class="px-4 py-2 border">Tanggal</th>
                        <th class="px-4 py-2 border">Judul Pidato</th>
                        <th class="px-4 py-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($pidatos) > 0): ?>
                        <?php foreach ($pidatos as $index => $row): ?>
                            <tr>
                                <td class="px-4 py-2 border"><?= $offset + $index + 1 ?></td>
                                <td class="px-4 py-2 border"><?= htmlspecialchars($row['agenda_judul']) ?></td>
                                <td class="px-4 py-2 border"><?= format_tanggal($row['tanggal']) ?></td>
                                <td class="px-4 py-2 border"><?= htmlspecialchars($row['judul']) ?></td>
                                <td class="px-4 py-2 border text-center">
                                    <a href="detail.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline">Detail</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4">Tidak ada data pidato.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Navigasi Halaman -->
        <div class="mt-4">
            <?php if ($totalPages > 1): ?>
                <div class="flex justify-center space-x-2">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?= $i ?>" class="px-3 py-1 border rounded <?= $i === $page ? 'bg-blue-500 text-white' : 'bg-white text-blue-500' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>