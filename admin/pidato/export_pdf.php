<?php
require_once '../../config/db.php';
require_once '../../vendor/autoload.php';
require_once '../../core/helper.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Ambil data pidato berdasarkan ID
$id = $_GET['id'] ?? null;
if (!$id) exit('ID tidak ditemukan');

$stmt = $pdo->prepare("
    SELECT p.*, a.judul AS judul_agenda, a.tanggal, a.waktu, a.lokasi
    FROM pidato p
    LEFT JOIN agenda a ON a.id = p.id_agenda
    WHERE p.id = ?
");
$stmt->execute([$id]);
$pidato = $stmt->fetch();

if (!$pidato) exit('Data pidato tidak ditemukan');

// Konversi logo ke base64
$pathLogo = '../../assets/images/pancasila.png';
$logoBase64 = '';

// Cek apakah file ada
if (file_exists($pathLogo)) {
    $logoData = base64_encode(file_get_contents($pathLogo));
    $logoBase64 = 'data:image/png;base64,' . $logoData;
}

// Siapkan HTML-nya
$html = '
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    .title { text-align: center; font-weight: bold; font-size: 18px; }
    .content { margin-top: 20px; text-align: justify; }
    .info { margin-top: 20px; font-size: 14px;}
    hr.border { border-top: 3px groove #000; margin: 10px 0; }
    .logo { width: 60px; margin-bottom: 10px; }
</style>

<div style="text-align:center;">
    <img src="' . $logoBase64 . '" class="logo" />
    <div class="title">SAMBUTAN BUPATI BULUNGAN PADA<br>' . strtoupper(htmlspecialchars($pidato['judul_agenda'])) . '</div>
</div>

<hr class="border" />

<div class="info">
    <strong>Tanggal Acara:</strong> ' . format_tanggal($pidato['tanggal']) . '<br>
    <strong>Waktu:</strong> ' . htmlspecialchars($pidato['waktu']) . '<br>
    <strong>Tempat:</strong> ' . htmlspecialchars($pidato['lokasi']) . '
</div>

<div class="content">' . $pidato['isi'] . '</div>
';

// Set opsi dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->setPaper('A5', 'portrait');
$dompdf->loadHtml($html);
$dompdf->render();

// Output PDF
$dompdf->stream('pidato_bupati.pdf', ['Attachment' => false]);
exit;