<?php
require_once '../../config/db.php';
require_once '../../core/helper.php';
require_once '../../core/flash.php';

// Ambil data pejabat
$stmt = $pdo->query("SELECT id, nama, jabatan FROM pejabat ORDER BY nama ASC");
$pejabatList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pejabat_id = $_POST['pejabat_id'] ?? null;
    $status = $_POST['status'] ?? 'Hadir';

    if ($pejabat_id) {
        $stmt = $pdo->prepare("INSERT INTO keberadaan (pejabat_id, status) VALUES (?, ?)");
        $stmt->execute([$pejabat_id, $status]);

        set_flash('success', 'Data keberadaan berhasil ditambahkan.');
        redirect('index.php');
    } else {
        set_flash('error', 'Pejabat harus dipilih.');
    }
}
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/sidebar.php'; ?>

<div class="p-4 sm:ml-64">
  <div class="p-4 mt-14 bg-white rounded shadow-md max-w-xl mx-auto">
    <h1 class="text-xl font-bold mb-4">Tambah Data Keberadaan</h1>

    <?php show_flash(); ?>

    <form action="" method="post" class="space-y-4">
      <div>
        <label for="pejabat_id" class="block text-sm font-medium text-gray-700">Nama Pejabat</label>
        <select name="pejabat_id" id="pejabat_id" required class="w-full border px-3 py-2 rounded">
          <option value="">-- Pilih Pejabat --</option>
          <?php foreach ($pejabatList as $pejabat): ?>
            <option value="<?= $pejabat['id'] ?>">
              <?= htmlspecialchars($pejabat['nama']) ?> (<?= htmlspecialchars($pejabat['jabatan']) ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
        <select name="status" id="status" class="w-full border px-3 py-2 rounded">
          <option value="Hadir">Hadir</option>
          <option value="Dinas Luar">Dinas Luar</option>
          <option value="Sakit">Sakit</option>
          <option value="Lainnya">Lainnya</option>
        </select>
      </div>

      <div class="flex justify-between">
        <a href="index.php" class="inline-block bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Kembali</a>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
      </div>
    </form>
  </div>
</div>

<?php include '../partials/footer.php'; ?>