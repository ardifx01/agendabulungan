<?php
require_once '../../config/db.php';
require_once '../../core/helper.php';
require_once '../../core/flash.php';

$id = $_GET['id'] ?? null;
if (!$id) redirect('index.php');

// Ambil data pidato beserta data agenda-nya
$stmt = $pdo->prepare("
    SELECT p.*, a.judul AS judul_agenda, a.tanggal AS tanggal_agenda
    FROM pidato p
    LEFT JOIN agenda a ON a.id = p.id_agenda
    WHERE p.id = ?
");
$stmt->execute([$id]);
$pidato = $stmt->fetch();

if (!$pidato) {
    set_flash('error', 'Data tidak ditemukan.');
    redirect('index.php');
}
?>

<?php include '../partials/header.php'; ?>

<div class="container mx-auto px-4 py-6">
    <div class="bg-white p-6 rounded shadow-md max-w-4xl mx-auto">
        <h2 class="text-2xl font-semibold mb-4">Detail Pidato</h2>

        <div class="mb-4">
            <label class="block font-medium text-gray-700">Judul Agenda:</label>
            <p class="text-gray-800"><?= htmlspecialchars($pidato['judul_agenda']) ?></p>
        </div>

        <div class="mb-4">
            <label class="block font-medium text-gray-700">Tanggal Agenda:</label>
            <p class="text-gray-800"><?= format_tanggal($pidato['tanggal_agenda']) ?></p>
        </div>

        <div class="mb-4">
            <label class="block font-medium text-gray-700">Isi Pidato:</label>
            <div class="prose max-w-none">
                <?= $pidato['isi'] ?>
            </div>
        </div>

        <a href="index.php" class="inline-block mt-4 text-blue-600 hover:underline">Kembali</a>
        <a href="export_pdf.php?id=<?= $pidato['id']; ?>" target="_blank" class="inline-block mt-4 bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded">
    Export PDF
</a>

    </div>
</div>

<?php include '../partials/footer.php'; ?>