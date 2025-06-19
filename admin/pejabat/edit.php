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
    set_flash('error', 'ID pejabat tidak valid.');
    redirect('index.php');
}

// Ambil data pejabat lama
$stmt = $pdo->prepare("SELECT * FROM pejabat WHERE id = :id");
$stmt->execute(['id' => $id]);
$pejabat = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pejabat) {
    set_flash('error', 'Data pejabat tidak ditemukan.');
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama       = $_POST['nama'] ?? '';
    $nip        = $_POST['nip'] ?? '';
    $jabatan    = $_POST['jabatan'] ?? '';
    $unit_kerja = $_POST['unit_kerja'] ?? '';
    $kontak     = $_POST['kontak'] ?? '';
    $email      = $_POST['email'] ?? '';
    $foto       = $pejabat['foto']; // default: tetap pakai foto lama

    // Proses upload foto baru jika ada
    if (!empty($_FILES['foto']['name'])) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $fotoName = 'pejabat_' . time() . '.' . $ext;
        $uploadDir = 'admin/uploads/pejabat/';
        $uploadPath = $uploadDir . $fotoName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadPath)) {
            // Hapus foto lama jika ada
            if (!empty($pejabat['foto']) && file_exists($uploadDir . $pejabat['foto'])) {
                unlink($uploadDir . $pejabat['foto']);
            }
            $foto = $fotoName;
        }
    }

    // Update data pejabat
    $stmt = $pdo->prepare("UPDATE pejabat SET nama = :nama, nip = :nip, jabatan = :jabatan,
                           unit_kerja = :unit_kerja, kontak = :kontak, email = :email, foto = :foto
                           WHERE id = :id");
    $stmt->execute([
        'nama'       => $nama,
        'nip'        => $nip,
        'jabatan'    => $jabatan,
        'unit_kerja' => $unit_kerja,
        'kontak'     => $kontak,
        'email'      => $email,
        'foto'       => $foto,
        'id'         => $id
    ]);

    set_flash('success', 'Data pejabat berhasil diperbarui.');
    redirect('index.php');
}
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/sidebar.php'; ?>

<main class="p-6 ml-64">
    <h1 class="text-2xl font-bold mb-4">Edit Pejabat</h1>

    <form method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow max-w-2xl">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block mb-1 font-medium">Nama</label>
                <input type="text" name="nama" value="<?= e($pejabat['nama']) ?>" required class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-medium">NIP</label>
                <input type="text" name="nip" value="<?= e($pejabat['nip']) ?>" class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-medium">Jabatan</label>
                <input type="text" name="jabatan" value="<?= e($pejabat['jabatan']) ?>" class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-medium">Unit Kerja</label>
                <input type="text" name="unit_kerja" value="<?= e($pejabat['unit_kerja']) ?>" class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-medium">Kontak</label>
                <input type="text" name="kontak" value="<?= e($pejabat['kontak']) ?>" class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-medium">Email</label>
                <input type="email" name="email" value="<?= e($pejabat['email']) ?>" class="w-full border px-3 py-2 rounded">
            </div>
            <div class="md:col-span-2">
                <label class="block mb-1 font-medium">Foto Baru (opsional)</label>
                <input type="file" name="foto" accept="image/*" class="w-full border px-3 py-2 rounded">
                <?php if ($pejabat['foto']): ?>
                    <img src="<?= BASE_URL . 'admin/uploads/pejabat/' . $pejabat['foto'] ?>" class="w-20 mt-2 rounded" alt="Foto lama">
                <?php endif; ?>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <a href="index.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded mr-2">Batal</a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Update</button>
        </div>
    </form>
</main>

<?php include '../partials/footer.php'; ?>