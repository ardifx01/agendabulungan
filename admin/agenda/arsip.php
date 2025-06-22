<?php
require_once '../../config/db.php';
require_once '../../core/functions.php';
require_once '../../config/auth.php';
require_once '../../core/helper.php';

require_login();

// Ambil semua agenda yang tanggalnya sudah lewat
$sql = "SELECT agenda.*, pejabat.nama AS nama_pejabat
        FROM agenda
        LEFT JOIN pejabat ON agenda.pejabat_id = pejabat.id
        WHERE agenda.tanggal < CURDATE()
        ORDER BY agenda.tanggal DESC";

$stmt = $pdo->query($sql);
$agendas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fungsi ambil data peliput dan protokoler
function getPeliput($pdo, $agenda_id) {
    $stmt = $pdo->prepare("SELECT nama FROM peliput 
        JOIN agenda_peliput ON peliput.id = agenda_peliput.peliput_id 
        WHERE agenda_peliput.agenda_id = ?");
    $stmt->execute([$agenda_id]);
    return implode(', ', $stmt->fetchAll(PDO::FETCH_COLUMN));
}

function getProtokoler($pdo, $agenda_id) {
    $stmt = $pdo->prepare("SELECT nama FROM protokoler 
        JOIN agenda_protokoler ON protokoler.id = agenda_protokoler.protokoler_id 
        WHERE agenda_protokoler.agenda_id = ?");
    $stmt->execute([$agenda_id]);
    return implode(', ', $stmt->fetchAll(PDO::FETCH_COLUMN));
}

include '../partials/header.php';
include '../partials/sidebar.php';
?>

<div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Arsip Agenda</h1>
    <table class="min-w-full border text-sm">
        <thead class="bg-orange-600 text-white">
            <tr>
                <th class="border px-2 py-1">No</th>
                <th class="border px-2 py-1">Judul</th>
                <th class="border px-2 py-1">Tanggal</th>
                <th class="border px-2 py-1">Waktu</th>
                <th class="border px-2 py-1">Lokasi</th>
                <th class="border px-2 py-1">Pejabat</th>
                <th class="border px-2 py-1">Peliput</th>
                <th class="border px-2 py-1">Protokoler</th>
                <th class="border px-2 py-1">Undangan</th>
                <th class="border px-2 py-1">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($agendas as $agenda): ?>
            <tr class="border hover:bg-orange-50">
                <td class="border px-2 py-1"><?= $no++ ?></td>
                <td class="border px-2 py-1"><?= e($agenda['judul']) ?></td>
                <td class="border px-2 py-1"><?= e(date('d-m-Y', strtotime($agenda['tanggal']))) ?></td>
                <td class="border px-2 py-1"><?= e(substr($agenda['waktu'], 0, 5)) ?></td>
                <td class="border px-2 py-1"><?= e($agenda['lokasi']) ?></td>
                <td class="border px-2 py-1"><?= e($agenda['nama_pejabat']) ?></td>
                <td class="border px-2 py-1"><?= e(getPeliput($pdo, $agenda['id'])) ?></td>
                <td class="border px-2 py-1"><?= e(getProtokoler($pdo, $agenda['id'])) ?></td>
                <td class="border px-2 py-1">
                    <?php if (!empty($agenda['undangan'])): ?>
                        <a href="../uploads/agenda/<?= e($agenda['undangan']) ?>" class="text-blue-500 underline" target="_blank">Lihat</a>
                    <?php else: ?>
                        <span class="text-gray-400">-</span>
                    <?php endif; ?>
                </td>
                <td class="border px-2 py-1">
                    <a href="detail.php?id=<?= $agenda['id'] ?>" class="text-blue-500 hover:underline">Detail</a>
                    <a href="edit.php?id=<?= $agenda['id'] ?>" class="text-green-600 hover:underline ml-1">Edit</a>
                    <a href="delete.php?id=<?= $agenda['id'] ?>" class="text-red-600 hover:underline ml-1"
                       onclick="return confirm('Yakin ingin menghapus agenda ini?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../partials/footer.php'; ?>
