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

$id = $_GET['id'] ?? null;
if (!$id) {
    set_flash('error', 'ID protokoler tidak valid.');
    redirect('index.php');
}

// Ambil data protokoler
$stmt = $pdo->prepare("SELECT * FROM protokoler WHERE id = :id");
$stmt->execute(['id' => $id]);
$protokoler = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$protokoler) {
    set_flash('error', 'Data protokoler tidak ditemukan.');
    redirect('index.php');
}
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/sidebar.php'; ?>

<main class="p-6 ml-64">
    <h1 class="text-2xl font-bold mb-6">Detail Protokoler</h1>

    <div class="bg-white rounded shadow p-6 max-w-xl">
        <table class="w-full text-sm text-gray-700">
            <tr>
                <td class="py-2 font-medium w-1/3">Nama</td>
                <td class="py-2"><?= e($protokoler['nama']) ?></td>
            </tr>
            <tr>
                <td class="py-2 font-medium">Jabatan</td>
                <td class="py-2"><?= e($protokoler['jabatan']) ?: '-' ?></td>
            </tr>
            <tr>
                <td class="py-2 font-medium">Unit Kerja</td>
                <td class="py-2"><?= e($protokoler['unit_kerja']) ?: '-' ?></td>
            </tr>
            <tr>
                <td class="py-2 font-medium">Tanggal Input</td>
                <td class="py-2"><?= format_tanggal(substr($protokoler['created_at'], 0, 10)) ?></td>
            </tr>
        </table>

        <div class="mt-6">
            <a href="edit.php?id=<?= $protokoler['id'] ?>" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 mr-2">Edit</a>
            <a href="index.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Kembali</a>
        </div>
    </div>
</main>

<?php include '../partials/footer.php'; ?>