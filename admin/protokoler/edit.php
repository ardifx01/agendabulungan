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

// Ambil data
$stmt = $pdo->prepare("SELECT * FROM protokoler WHERE id = :id");
$stmt->execute(['id' => $id]);
$protokoler = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$protokoler) {
    set_flash('error', 'Data protokoler tidak ditemukan.');
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama       = $_POST['nama'] ?? '';
    $jabatan    = $_POST['jabatan'] ?? '';
    $unit_kerja = $_POST['unit_kerja'] ?? '';

    $stmt = $pdo->prepare("UPDATE protokoler SET nama = :nama, jabatan = :jabatan, unit_kerja = :unit_kerja WHERE id = :id");
    $stmt->execute([
        'nama'       => $nama,
        'jabatan'    => $jabatan,
        'unit_kerja' => $unit_kerja,
        'id'         => $id
    ]);

    set_flash('success', 'Data protokoler berhasil diperbarui.');
    redirect('index.php');
}
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/sidebar.php'; ?>

<main class="p-6 ml-64">
    <h1 class="text-2xl font-bold mb-4">Edit Protokoler</h1>

    <form method="POST" class="bg-white p-6 rounded shadow max-w-2xl">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="block mb-1 font-medium">Nama</label>
                <input type="text" name="nama" value="<?= e($protokoler['nama']) ?>" required class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-medium">Jabatan</label>
                <input type="text" name="jabatan" value="<?= e($protokoler['jabatan']) ?>" class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-medium">Unit Kerja</label>
                <input type="text" name="unit_kerja" value="<?= e($protokoler['unit_kerja']) ?>" class="w-full border px-3 py-2 rounded">
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <a href="index.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded mr-2">Batal</a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Update</button>
        </div>
    </form>
</main>

<?php include '../partials/footer.php'; ?>