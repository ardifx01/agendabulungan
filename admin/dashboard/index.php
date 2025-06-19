<?php
require_once '../../config/db.php';
require_once '../../config/auth.php';
require_once '../../config/constants.php';
require_once '../../core/session.php';
require_once '../../core/helper.php';
require_once '../../core/functions.php';

if (!is_logged_in()) {
    redirect('login.php');
}

$user = current_user();

// Ambil statistik jumlah data
$totalAgenda = $pdo->query("SELECT COUNT(*) FROM agenda")->fetchColumn();
$totalPejabat = $pdo->query("SELECT COUNT(*) FROM pejabat")->fetchColumn();
$totalPeliput = $pdo->query("SELECT COUNT(*) FROM peliput")->fetchColumn();
$totalPidato = $pdo->query("SELECT COUNT(*) FROM pidato")->fetchColumn();

// Ambil agenda hari ini
$tanggalHariIni = date('Y-m-d');
$stmt = $pdo->prepare("SELECT * FROM agenda WHERE tanggal = :tanggal ORDER BY waktu ASC");
$stmt->execute(['tanggal' => $tanggalHariIni]);
$agendaHariIni = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/sidebar.php'; ?>

<main class="p-6 ml-64">
    <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
    <p class="mb-6 text-gray-600">Selamat datang, <strong><?= e($user['username']) ?></strong>. Hari ini <?= format_tanggal($tanggalHariIni) ?>.</p>

    <!-- Statistik -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-100 text-blue-800 p-4 rounded shadow">
            <div class="text-sm">Total Agenda</div>
            <div class="text-2xl font-bold"><?= $totalAgenda ?></div>
        </div>
        <div class="bg-green-100 text-green-800 p-4 rounded shadow">
            <div class="text-sm">Jumlah Pejabat</div>
            <div class="text-2xl font-bold"><?= $totalPejabat ?></div>
        </div>
        <div class="bg-yellow-100 text-yellow-800 p-4 rounded shadow">
            <div class="text-sm">Jumlah Peliput</div>
            <div class="text-2xl font-bold"><?= $totalPeliput ?></div>
        </div>
        <div class="bg-purple-100 text-purple-800 p-4 rounded shadow">
            <div class="text-sm">Jumlah Pidato</div>
            <div class="text-2xl font-bold"><?= $totalPidato ?></div>
        </div>
    </div>

    <!-- Agenda Hari Ini -->
    <div class="bg-white rounded shadow p-4">
        <h2 class="text-lg font-semibold mb-3">Agenda Hari Ini</h2>

        <?php if (count($agendaHariIni) > 0): ?>
            <ul class="space-y-2">
                <?php foreach ($agendaHariIni as $agenda): ?>
                    <li class="border-b pb-2">
                        <div class="font-medium text-gray-800"><?= e($agenda['judul']) ?></div>
                        <div class="text-sm text-gray-500">
                            <?= $agenda['waktu'] ?> @ <?= e($agenda['lokasi']) ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-sm text-gray-500">Tidak ada agenda hari ini.</p>
        <?php endif; ?>
    </div>
</main>

<?php include '../partials/footer.php'; ?>
