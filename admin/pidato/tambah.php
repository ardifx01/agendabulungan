<?php
require_once '../../config/db.php';
require_once '../../config/auth.php';
require_once '../../core/functions.php';
require_once '../../core/flash.php';
require_once '../../core/helper.php';

$agendas = $pdo->query("SELECT id, judul, tanggal FROM agenda WHERE tanggal >= CURDATE() ORDER BY tanggal ASC")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_agenda = $_POST['id_agenda'] ?? '';
    $isi = $_POST['isi'] ?? '';

    if (!$id_agenda || !$isi) {
        set_flash('error', 'Mohon lengkapi semua data.');
    } else {
        // Ambil data agenda
        $stmt = $pdo->prepare("SELECT judul, tanggal FROM agenda WHERE id = ?");
        $stmt->execute([$id_agenda]);
        $agenda = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($agenda) {
            $judul = $agenda['judul'];
            $tanggal = $agenda['tanggal'];

            // Simpan pidato
            $stmt = $pdo->prepare("INSERT INTO pidato (id_agenda, isi, tanggal, created_at) VALUES (?, ?, ?, NOW())");
            $success = $stmt->execute([$id_agenda, $isi, $tanggal]);

            if ($success) {
                set_flash('success', 'Pidato berhasil disimpan.');
                header('Location: index.php');
                exit;
            } else {
                set_flash('error', 'Gagal menyimpan pidato.');
            }
        } else {
            set_flash('error', 'Agenda tidak ditemukan.');
        }
    }
}
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/sidebar.php'; ?>

<div class="p-4 sm:ml-64">
    <div class="p-4 mt-14">
        <h1 class="text-2xl font-bold mb-4">Tambah Pidato</h1>
        <?php show_flash(); ?>
        <form method="post">
            <div class="mb-4">
                <label for="id_agenda" class="block mb-1">Pilih Agenda</label>
                <select name="id_agenda" id="id_agenda" class="w-full border px-3 py-2 rounded" required>
                    <option value="">-- Pilih Agenda --</option>
                    <?php foreach ($agendas as $agenda): ?>
                        <option value="<?= $agenda['id'] ?>">
                            <?= $agenda['judul'] ?> (<?= format_tanggal($agenda['tanggal']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="isi" class="block mb-1">Isi Pidato</label>
                <textarea name="isi" id="isi" rows="10" class="w-full border px-3 py-2 rounded"></textarea>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
        </form>
    </div>
</div>

<script src="https://cdn.tiny.cloud/1/7hnbmr9w3fq7e6ra9rccibu64vxgw1bw3516rhssju9agu45/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#isi',
        height: 300,
        menubar: false,
        plugins: 'lists link image code',
        toolbar: 'undo redo | formatselect | bold italic underline | bullist numlist | link image | code',
    });
</script>

<?php include '../partials/footer.php'; ?>