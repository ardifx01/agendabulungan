<?php
require_once '../../config/db.php';
require_once '../../config/auth.php';
require_once '../../core/session.php';
require_once '../../core/flash.php';
require_once '../../core/helper.php';
require_once '../../core/functions.php';

require_login();

$pejabat = $pdo->query("SELECT * FROM pejabat ORDER BY nama ASC")->fetchAll();
$peliput = $pdo->query("SELECT * FROM peliput ORDER BY nama ASC")->fetchAll();
$protokoler = $pdo->query("SELECT * FROM protokoler ORDER BY nama ASC")->fetchAll();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $tanggal = $_POST['tanggal'];
    $waktu = $_POST['waktu'];
    $lokasi = $_POST['lokasi'];
    $pejabat_id = $_POST['pejabat_id'];
    $peliput_ids = $_POST['peliput_id'] ?? [];
    $protokoler_ids = $_POST['protokoler_id'] ?? [];

    $undangan = null;

    // Validasi
    if (!$judul || !$tanggal || !$waktu || !$lokasi || !$pejabat_id) {
        $errors[] = 'Semua field wajib diisi!';
    }

    // Upload file undangan (optional)
    if (!empty($_FILES['undangan']['name'])) {
        $allowed = ['jpg','jpeg','png','pdf','docx'];
        $file_name = $_FILES['undangan']['name'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $errors[] = "Format file undangan tidak didukung.";
        } else {
            $undangan = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['undangan']['tmp_name'], '../uploads/agenda/' . $undangan);
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO agenda (judul, tanggal, waktu, lokasi, pejabat_id, undangan) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$judul, $tanggal, $waktu, $lokasi, $pejabat_id, $undangan]);
        $agenda_id = $pdo->lastInsertId();

        // Insert ke tabel relasi peliput
        foreach ($peliput_ids as $pid) {
            $stmt = $pdo->prepare("INSERT INTO agenda_peliput (agenda_id, peliput_id) VALUES (?, ?)");
            $stmt->execute([$agenda_id, $pid]);
        }

        // Insert ke tabel relasi protokoler
        foreach ($protokoler_ids as $pid) {
            $stmt = $pdo->prepare("INSERT INTO agenda_protokoler (agenda_id, protokoler_id) VALUES (?, ?)");
            $stmt->execute([$agenda_id, $pid]);
        }

        set_flash('success', 'Agenda berhasil ditambahkan.');
        header('Location: index.php');
        exit;
    }
}
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/sidebar.php'; ?>

<main class="p-6 ml-64">
    <h1 class="text-2xl font-bold mb-4">Tambah Agenda</h1>

    <?php if ($errors): ?>
        <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
            <ul class="list-disc pl-5">
                <?php foreach ($errors as $error): ?>
                    <li><?= e($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow space-y-4">
        <div>
            <label class="block font-semibold">Judul</label>
            <input type="text" name="judul" class="w-full border p-2 rounded" required>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold">Tanggal</label>
                <input type="date" name="tanggal" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label class="block font-semibold">Waktu</label>
                <input type="time" name="waktu" class="w-full border p-2 rounded" required>
            </div>
        </div>
        <div>
            <label class="block font-semibold">Lokasi</label>
            <input type="text" name="lokasi" class="w-full border p-2 rounded" required>
        </div>
        <div>
            <label class="block font-semibold">Pejabat</label>
            <select name="pejabat_id" class="w-full border p-2 rounded">
                <option value="">-- Pilih Pejabat --</option>
                <?php foreach ($pejabat as $p): ?>
                    <option value="<?= $p['id'] ?>"><?= e($p['nama']) ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div>
            <label class="block font-semibold">Peliput</label>
            <select name="peliput_id[]" multiple class="w-full border p-2 rounded">
                <?php foreach ($peliput as $p): ?>
                    <option value="<?= $p['id'] ?>"><?= e($p['nama']) ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div>
            <label class="block font-semibold">Protokoler</label>
            <select name="protokoler_id[]" multiple class="w-full border p-2 rounded">
                <?php foreach ($protokoler as $p): ?>
                    <option value="<?= $p['id'] ?>"><?= e($p['nama']) ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div>
            <label class="block font-semibold">File Undangan (opsional)</label>
            <input type="file" name="undangan" class="w-full border p-2 rounded">
        </div>
        <div class="text-right">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
        </div>
    </form>
</main>

<?php include '../partials/footer.php'; ?>