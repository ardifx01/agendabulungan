<?php
require_once '../../config/auth.php';
require_once '../../config/db.php';
require_once '../../core/helper.php';
require_once '../../core/functions.php';

require_login();
include '../partials/header.php';
include '../partials/sidebar.php';

// Fungsi ambil nama peliput
function getPeliputByAgenda($pdo, $agenda_id) {
    $stmt = $pdo->prepare("SELECT nama FROM peliput 
        JOIN agenda_peliput ON peliput.id = agenda_peliput.peliput_id 
        WHERE agenda_peliput.agenda_id = ?");
    $stmt->execute([$agenda_id]);
    return implode(', ', $stmt->fetchAll(PDO::FETCH_COLUMN));
}

// Fungsi ambil nama protokoler
function getProtokolerByAgenda($pdo, $agenda_id) {
    $stmt = $pdo->prepare("SELECT nama FROM protokoler 
        JOIN agenda_protokoler ON protokoler.id = agenda_protokoler.protokoler_id 
        WHERE agenda_protokoler.agenda_id = ?");
    $stmt->execute([$agenda_id]);
    return implode(', ', $stmt->fetchAll(PDO::FETCH_COLUMN));
}

// PAGINASI
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Query total
$total = $pdo->query("SELECT COUNT(*) FROM agenda WHERE tanggal >= CURDATE()")->fetchColumn();
$pages = ceil($total / $limit);

// Query agenda aktif (hari ini dan akan datang)
$stmt = $pdo->prepare("SELECT agenda.*, pejabat.nama as nama_pejabat 
    FROM agenda 
    LEFT JOIN pejabat ON agenda.pejabat_id = pejabat.id 
    WHERE tanggal >= CURDATE() 
    ORDER BY tanggal ASC, waktu ASC 
    LIMIT $start, $limit");
$stmt->execute();
$agenda = $stmt->fetchAll();
?>

<main class="p-6 sm:ml-64">
    <div class="mt-14">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">Agenda</h1>
            <a href="tambah.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Tambah Agenda</a>
        </div>

        <!-- Tombol aksi -->
        <div class="mb-4 flex flex-wrap gap-2">
            <a href="arsip.php" class="bg-gray-600 text-white px-3 py-1 rounded">Lihat Arsip</a>
            <a href="export_pdf.php" class="bg-red-600 text-white px-3 py-1 rounded">Export PDF</a>
            <a href="export_excel.php" class="bg-green-600 text-white px-3 py-1 rounded">Export Excel</a>
            <a href="export_gambar.php" class="bg-yellow-500 text-white px-3 py-1 rounded">Export Gambar</a>
            <a href="print.php" class="bg-purple-600 text-white px-3 py-1 rounded" target="_blank">Print Hari Ini</a>
            <a href="?filter=today" class="bg-blue-800 text-white px-3 py-1 rounded">Tampilkan Hari Ini</a>
        </div>

        <div class="overflow-x-auto bg-white rounded-xl shadow">
            <table class="min-w-full text-sm border border-gray-200">
                <thead class="bg-orange-700 text-white">
                    <tr>
                        <th class="p-2 border">No</th>
                        <th class="p-2 border">Judul</th>
                        <th class="p-2 border">Tanggal</th>
                        <th class="p-2 border">Waktu</th>
                        <th class="p-2 border">Lokasi</th>
                        <th class="p-2 border">Pejabat</th>
                        <th class="p-2 border">Peliput</th>
                        <th class="p-2 border">Protokoler</th>
                        <th class="p-2 border">Undangan</th>
                        <th class="p-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($agenda) > 0): ?>
                        <?php foreach ($agenda as $i => $row): ?>
                            <?php
                                $status = '';
                                $today = date('Y-m-d');
                                if ($row['tanggal'] == $today) $status = 'bg-green-100';
                                elseif ($row['tanggal'] == date('Y-m-d', strtotime('+1 day'))) $status = 'bg-yellow-100';
                                elseif ($row['tanggal'] == date('Y-m-d', strtotime('+2 day'))) $status = 'bg-blue-100';
                            ?>
                            <tr class="<?= $status ?>">
                                <td class="p-2 border text-center"><?= $start + $i + 1 ?></td>
                                <td class="p-2 border"><?= htmlspecialchars($row['judul']) ?></td>
                                <td class="p-2 border"><?= $row['tanggal'] ?></td>
                                <td class="p-2 border"><?= $row['waktu'] ?></td>
                                <td class="p-2 border"><?= $row['lokasi'] ?></td>
                                <td class="p-2 border"><?= $row['nama_pejabat'] ?></td>
                                <td class="p-2 border"><?= getPeliputByAgenda($pdo, $row['id']) ?></td>
                                <td class="p-2 border"><?= getProtokolerByAgenda($pdo, $row['id']) ?></td>
                                <td class="p-2 border">
                                    <?php if (!empty($row['undangan'])): ?>
                                        <a href="../uploads/agenda/<?= $row['undangan'] ?>" target="_blank" class="text-blue-500 underline">Lihat</a>
                                    <?php else: ?>
                                        <span class="text-gray-400 italic">-</span>
                                    <?php endif ?>
                                </td>
                                <td class="p-2 border whitespace-nowrap">
                                    <a href="detail.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline">Detail</a> |
                                    <a href="edit.php?id=<?= $row['id'] ?>" class="text-green-600 hover:underline">Edit</a> |
                                    <a href="delete.php?id=<?= $row['id'] ?>" class="text-red-600 hover:underline" onclick="return confirm('Yakin ingin menghapus agenda ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center p-4 text-gray-500">Belum ada agenda hari ini dan seterusnya.</td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>

        <!-- Navigasi Paginasi -->
        <div class="mt-4 flex justify-center gap-2">
            <?php for ($i = 1; $i <= $pages; $i++): ?>
                <a href="?page=<?= $i ?>" class="px-3 py-1 rounded border <?= ($i == $page) ? 'bg-blue-600 text-white' : 'bg-white text-gray-700' ?>">
                    <?= $i ?>
                </a>
            <?php endfor ?>
        </div>
    </div>
</main>

<?php include '../partials/footer.php'; ?>