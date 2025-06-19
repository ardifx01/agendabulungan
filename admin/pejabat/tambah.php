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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama       = $_POST['nama'] ?? '';
    $nip        = $_POST['nip'] ?? '';
    $jabatan    = $_POST['jabatan'] ?? '';
    $unit_kerja = $_POST['unit_kerja'] ?? '';
    $kontak     = $_POST['kontak'] ?? '';
    $email      = $_POST['email'] ?? '';
    $foto       = '';

    // Upload foto jika ada
    if (!empty($_FILES['foto']['name'])) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $fotoName = 'pejabat_' . time() . '.' . $ext;
        $uploadDir = '../uploads/pejabat/';
        $uploadPath = $uploadDir . $fotoName;

        // Pastikan folder upload ada
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadPath)) {
            $foto = $fotoName;
        }
    }

    // Simpan ke database
    $stmt = $pdo->prepare("INSERT INTO pejabat (nama, nip, jabatan, unit_kerja, kontak, email, foto)
                           VALUES (:nama, :nip, :jabatan, :unit_kerja, :kontak, :email, :foto)");
    $stmt->execute([
        'nama'       => $nama,
        'nip'        => $nip,
        'jabatan'    => $jabatan,
        'unit_kerja' => $unit_kerja,
        'kontak'     => $kontak,
        'email'      => $email,
        'foto'       => $foto
    ]);

    set_flash('success', 'Data pejabat berhasil ditambahkan.');
    redirect('index.php');
}
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/sidebar.php'; ?>

<main class="p-6 ml-64">
    <h1 class="text-2xl font-bold mb-4">Tambah Pejabat</h1>

    <form method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow max-w-2xl">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block mb-1 font-medium">Nama</label>
                <input type="text" name="nama" required class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-medium">NIP</label>
                <input type="text" name="nip" class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-medium">Jabatan</label>
                <input type="text" name="jabatan" class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-medium">Unit Kerja</label>
                <input type="text" name="unit_kerja" class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-medium">Kontak</label>
                <input type="text" name="kontak" class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-medium">Email</label>
                <input type="email" name="email" class="w-full border px-3 py-2 rounded">
            </div>
            <div class="md:col-span-2">
                <label class="block mb-1 font-medium">Foto (opsional)</label>
                <input type="file" name="foto" accept="image/*" class="w-full border px-3 py-2 rounded">
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <a href="index.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded mr-2">Batal</a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Simpan</button>
        </div>
    </form>
</main>

<?php include '../partials/footer.php'; ?>