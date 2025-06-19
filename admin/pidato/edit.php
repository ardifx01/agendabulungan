<?php
require_once '../../config/db.php';
require_once '../../core/helper.php';
require_once '../../core/flash.php';

$id = $_GET['id'] ?? null;
if (!$id) redirect('index.php');

// Ambil data pidato berdasarkan ID
$stmt = $pdo->prepare("SELECT * FROM pidato WHERE id = ?");
$stmt->execute([$id]);
$pidato = $stmt->fetch();

if (!$pidato) {
    set_flash('error', 'Data tidak ditemukan.');
    redirect('index.php');
}

// Ambil agenda yang tersedia hari ini dan ke depan
$stmt = $pdo->query("SELECT id, judul, tanggal FROM agenda WHERE tanggal >= CURDATE() ORDER BY tanggal ASC");
$agendaList = $stmt->fetchAll();

// Proses update jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_agenda = $_POST['id_agenda'] ?? null;
    $isi = $_POST['isi'] ?? null;

    if ($id_agenda && $isi) {
        $stmt = $pdo->prepare("UPDATE pidato SET id_agenda = ?, isi = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$id_agenda, $isi, $id]);

        set_flash('success', 'Pidato berhasil diperbarui.');
        redirect('index.php');
    } else {
        set_flash('error', 'Semua field wajib diisi.');
    }
}
?>

<?php include '../partials/header.php'; ?>
<script src="https://cdn.tiny.cloud/1/7hnbmr9w3fq7e6ra9rccibu64vxgw1bw3516rhssju9agu45/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#isi',
        plugins: 'lists link table code',
        toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist | link table code',
        height: 300
    });
</script>

<div class="container mx-auto px-4 py-6">
    <div class="bg-white p-6 rounded shadow-md max-w-3xl mx-auto">
        <h2 class="text-2xl font-semibold mb-4">Edit Pidato</h2>
        <?php show_flash(); ?>
        <form method="POST">
            <div class="mb-4">
                <label for="id_agenda" class="block font-medium">Pilih Agenda</label>
                <select name="id_agenda" id="id_agenda" class="w-full border px-3 py-2 rounded" required>
                    <option value="">-- Pilih Agenda --</option>
                    <?php foreach ($agendaList as $agenda): ?>
                        <option value="<?= $agenda['id'] ?>" <?= $agenda['id'] == $pidato['id_agenda'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($agenda['judul']) ?> (<?= format_tanggal($agenda['tanggal']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4">
                <label for="isi" class="block font-medium">Isi Pidato</label>
                <textarea name="isi" id="isi"><?= htmlspecialchars($pidato['isi']) ?></textarea>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
                <a href="index.php" class="text-gray-600 hover:underline">Kembali</a>
            </div>
        </form>
    </div>
</div>

<?php include '../partials/footer.php'; ?>