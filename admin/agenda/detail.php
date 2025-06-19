<?php
require_once '../../config/db.php';
require_once '../../config/auth.php';
require_once '../../core/session.php';
require_once '../../core/flash.php';
require_once '../../core/helper.php';
require_once '../../core/functions.php';

require_login();

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT a.*, p.nama AS pejabat_nama 
                       FROM agenda a 
                       JOIN pejabat p ON a.pejabat_id = p.id 
                       WHERE a.id = ?");
$stmt->execute([$id]);
$agenda = $stmt->fetch();

if (!$agenda) {
    set_flash('error', 'Agenda tidak ditemukan.');
    header('Location: index.php');
    exit;
}

// Ambil peliput terkait
$stmt = $pdo->prepare("SELECT peliput.nama 
                       FROM agenda_peliput 
                       JOIN peliput ON agenda_peliput.peliput_id = peliput.id 
                       WHERE agenda_peliput.agenda_id = ?");
$stmt->execute([$id]);
$peliput = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Ambil protokoler terkait
$stmt = $pdo->prepare("SELECT protokoler.nama 
                       FROM agenda_protokoler 
                       JOIN protokoler ON agenda_protokoler.protokoler_id = protokoler.id 
                       WHERE agenda_protokoler.agenda_id = ?");
$stmt->execute([$id]);
$protokoler = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/sidebar.php'; ?>

<main class="p-6 ml-64">
    <h1 class="text-2xl font-bold mb-4">Detail Agenda</h1>

    <div class="bg-white p-6 rounded shadow">
        <table class="table-auto w-full">
            <tr><td class="font-semibold w-48">Judul</td><td>: <?= e($agenda['judul']) ?></td></tr>
            <tr><td class="font-semibold">Tanggal</td><td>: <?= e($agenda['tanggal']) ?></td></tr>
            <tr><td class="font-semibold">Waktu</td><td>: <?= e($agenda['waktu']) ?></td></tr>
            <tr><td class="font-semibold">Lokasi</td><td>: <?= e($agenda['lokasi']) ?></td></tr>
            <tr><td class="font-semibold">Pejabat</td><td>: <?= e($agenda['pejabat_nama']) ?></td></tr>
            <tr><td class="font-semibold">Peliput</td><td>: <?= $peliput ? implode(', ', $peliput) : '-' ?></td></tr>
            <tr><td class="font-semibold">Protokoler</td><td>: <?= $protokoler ? implode(', ', $protokoler) : '-' ?></td></tr>
            <tr>
                <td class="font-semibold">File Undangan</td>
                <td>: 
                    <?php if ($agenda['undangan']): ?>
                        <a href="../uploads/agenda/<?= e($agenda['undangan']) ?>" target="_blank" class="text-blue-600 underline">Lihat File</a>
                    <?php else: ?>
                        Tidak ada
                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <div class="mt-6">
            <a href="index.php" class="bg-gray-600 text-white px-4 py-2 rounded">Kembali</a>
        </div>
    </div>
</main>

<?php include '../partials/footer.php'; ?>