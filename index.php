<?php
require_once 'config/db.php';

function format_tanggal($tgl) {
    return date('d/m/Y', strtotime($tgl));
}

$today = date('Y-m-d');

// Ambil 5 agenda hari ini dan yang akan datang
$stmt = $pdo->prepare("SELECT * FROM agenda WHERE tanggal >= ? ORDER BY tanggal ASC LIMIT 5");
$stmt->execute([$today]);
$agendas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil 3 keberadaan pejabat terbaru
$keberadaan = $pdo->query("SELECT k.*, p.nama, p.jabatan 
    FROM keberadaan k 
    JOIN pejabat p ON k.pejabat_id = p.id 
    ORDER BY k.updated_at DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Agenda Acara - Pemkab Bulungan</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      background-image: url('assets/images/background.jpg');
      background-size: cover;
      background-position: center;
    }
    .scrolling-text {
      animation: scroll-left 15s linear infinite;
      white-space: nowrap;
    }
    @keyframes scroll-left {
      0% { transform: translateX(100%); }
      100% { transform: translateX(-100%); }
    }
  </style>
</head>
<body class="min-h-screen flex flex-col text-white">
  <div class="bg-gradient-to-r from-green-500 to-teal-600 bg-opacity-90 py-4 shadow-lg text-center">
    <div class="flex justify-center items-center space-x-4">
      <img src="assets/images/logobulungan.png" alt="Logo Bulungan" class="h-16">
      <h1 class="text-3xl md:text-4xl font-bold uppercase tracking-wide">Agenda Acara Pemerintah Kabupaten Bulungan</h1>
    </div>
  </div>

  <main class="flex-1 bg-black bg-opacity-40 px-4 md:px-10 py-6 overflow-auto">
    <div class="max-w-5xl mx-auto">
      <h2 class="text-2xl font-bold mb-4 text-yellow-300">Agenda Hari Ini dan Mendatang</h2>
      <div class="grid gap-4">
        <?php if ($agendas): foreach ($agendas as $agenda): ?>
          <div class="bg-white bg-opacity-90 rounded-lg p-4 shadow-md text-black">
            <h3 class="text-lg font-semibold text-blue-700"><?= htmlspecialchars($agenda['judul']) ?></h3>
            <p><strong>Tanggal:</strong> <?= format_tanggal($agenda['tanggal']) ?></p>
            <p><strong>Waktu:</strong> <?= $agenda['waktu'] ?></p>
            <p><strong>Lokasi:</strong> <?= htmlspecialchars($agenda['lokasi']) ?></p>
          </div>
        <?php endforeach; else: ?>
          <p class="text-red-200">Tidak ada agenda tersedia.</p>
        <?php endif; ?>
      </div>

      <h2 class="text-2xl font-bold mt-10 mb-4 text-pink-200">Keberadaan Pejabat</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        <?php foreach ($keberadaan as $data): ?>
          <div class="bg-gradient-to-br from-blue-400 to-green-400 rounded-xl p-4 shadow-lg text-white">
            <h3 class="text-xl font-semibold"><?= htmlspecialchars($data['nama']) ?></h3>
            <p class="text-sm"><?= htmlspecialchars($data['jabatan']) ?></p>
            <p class="text-lg font-bold mt-2"><?= $data['status'] ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </main>

  <footer class="bg-gray-900 text-yellow-400 py-3 overflow-hidden whitespace-nowrap">
    <div class="scrolling-text text-lg text-center font-semibold tracking-wide">
      PEMERINTAH KABUPATEN BULUNGAN JALAN JELARAI RAYA KOTA TANJUNG SELOR, KABUPATEN BULUNGAN, KALIMANTAN UTARA
    </div>
  </footer>
</body>
</html>