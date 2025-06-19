<?php
require_once '../../config/db.php';
require_once '../../core/helper.php';
require_once '../../core/flash.php';
require_once '../../config/auth.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    set_flash('error', 'ID tidak ditemukan.');
    redirect('index.php');
}

$stmt = $pdo->prepare("SELECT * FROM keberadaan WHERE id = ?");
$stmt->execute([$id]);
$keberadaan = $stmt->fetch();

if (!$keberadaan) {
    set_flash('error', 'Data tidak ditemukan.');
    redirect('index.php');
}

$pejabat = $pdo->query("SELECT id, nama FROM pejabat ORDER BY nama ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pejabat_id = $_POST['pejabat_id'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE keberadaan SET pejabat_id = ?, status = ? WHERE id = ?");
    $stmt->execute([$pejabat_id, $status, $id]);

    set_flash('success', 'Data keberadaan berhasil diperbarui.');
    redirect('index.php');
}
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/sidebar.php'; ?>

<div class="p-6 sm:ml-64">
    <div class="p-4 bg-white rounded-lg shadow">
        <h2 class="text-2xl font-semibold mb-4">Edit Keberadaan</h2>

        <?php show_flash(); ?>

        <form method="POST" class="space-y-4">
            <div>
                <label for="pejabat_id" class="block font-medium">Pejabat</label>
                <select name="pejabat_id" id="pejabat_id" class="w-full border px-3 py-2 rounded" required>
                    <option value="">-- Pilih Pejabat --</option>
                    <?php foreach ($pejabat as $row): ?>
                        <option value="<?= $row['id'] ?>" <?= $row['id'] == $keberadaan['pejabat_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($row['nama']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="status" class="block font-medium">Status</label>
                <select name="status" id="status" class="w-full border px-3 py-2 rounded" required>
                    <?php foreach (['Hadir', 'Dinas Luar', 'Sakit', 'Lainnya'] as $opt): ?>
                        <option value="<?= $opt ?>" <?= $keberadaan['status'] == $opt ? 'selected' : '' ?>>
                            <?= $opt ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="flex justify-between items-center mt-4">
                <a href="index.php" class="text-blue-600 hover:underline">Kembali</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

<?php include '../partials/footer.php'; ?>
