<?php
require_once '../../config/db.php';
require_once '../../config/auth.php';
require_once '../../config/constants.php';
require_once '../../core/session.php';
require_once '../../core/helper.php';
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
    set_flash('error', 'ID pejabat tidak valid.');
    redirect('index.php');
}

$stmt = $pdo->prepare("SELECT * FROM pejabat WHERE id = :id");
$stmt->execute(['id' => $id]);
$pejabat = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pejabat) {
    set_flash('error', 'Data pejabat tidak ditemukan.');
    redirect('index.php');
}
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/sidebar.php'; ?>

<main class="p-6 ml-64">
    <h1 class="text-2xl font-bold mb-6">Detail Pejabat</h1>

    <div class="bg-white rounded shadow p-6 max-w-xl">
        <div class="flex items-center space-x-4 mb-4">
            <?php if ($pejabat['foto']): ?>
                <img src="<?= BASE_URL . 'admin/uploads/pejabat/' . $pejabat['foto'] ?>" class="w-24 h-24 object-cover rounded-full border" alt="Foto">
            <?php else: ?>
                <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center text-gray-500">Tidak Ada Foto</div>
            <?php endif; ?>
            <div>
                <h2 class="text-xl font-semibold"><?= e($pejabat['nama']) ?></h2>
                <p class="text-gray-500"><?= e($pejabat['jabatan']) ?></p>
            </div>
        </div>

        <table class="w-full text-sm text-gray-700">
            <tr>
                <td class="py-2 font-medium w-1/3">NIP</td>
                <td class="py-2"><?= e($pejabat['nip']) ?: '-' ?></td>
            </tr>
            <tr>
                <td class="py-2 font-medium">Unit Kerja</td>
                <td class="py-2"><?= e($pejabat['unit_kerja']) ?: '-' ?></td>
            </tr>
            <tr>
                <td class="py-2 font-medium">Kontak</td>
                <td class="py-2"><?= e($pejabat['kontak']) ?: '-' ?></td>
            </tr>
            <tr>
                <td class="py-2 font-medium">Email</td>
                <td class="py-2"><?= e($pejabat['email']) ?: '-' ?></td>
            </tr>
            <tr>
                <td class="py-2 font-medium">Tanggal Input</td>
                <td class="py-2"><?= format_tanggal(substr($pejabat['created_at'], 0, 10)) ?></td>
            </tr>
        </table>

        <div class="mt-6">
            <a href="edit.php?id=<?= $pejabat['id'] ?>" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 mr-2">Edit</a>
            <a href="index.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Kembali</a>
        </div>
    </div>
</main>

<?php include '../partials/footer.php'; ?>