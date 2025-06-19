<?php
require_once '../../vendor/autoload.php';
require_once '../../config/db.php';

use Spatie\Browsershot\Browsershot;

// Ambil data
$stmt = $pdo->query("SELECT a.*, p.nama AS pejabat_nama 
                     FROM agenda a 
                     JOIN pejabat p ON a.pejabat_id = p.id 
                     ORDER BY a.tanggal ASC");
$agendas = $stmt->fetchAll();

function get_relasi($pdo, $agenda_id, $table, $field) {
    $stmt = $pdo->prepare("SELECT $field.nama FROM agenda_$table 
                           JOIN $field ON agenda_$table.{$field}_id = $field.id 
                           WHERE agenda_id = ?");
    $stmt->execute([$agenda_id]);
    return implode(', ', $stmt->fetchAll(PDO::FETCH_COLUMN));
}

// Konversi gambar ke base64
$logo = base64_encode(file_get_contents('../../assets/images/logobulungan.png'));
$foto = base64_encode(file_get_contents('../../assets/images/bupatiwabup.png'));

// Buat HTML (mirip PDF)
$html = '
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; margin: 40px; }
        .header { display: flex; justify-content: space-between; align-items: center; }
        .header img { height: 60px; }
        .title { text-align: center; font-size: 14px; font-weight: bold; line-height: 1.5; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th {
            background-color: #e97700;
            color: #fff;
            padding: 6px;
            border: 1px solid #000;
        }
        td {
            background-color: #fff3e0;
            padding: 6px;
            border: 1px solid #000;
            vertical-align: top;
        }
    </style>
</head>
<body>

<div class="header">
    <img src="data:image/png;base64,' . $logo . '">
    <div class="title">
        AGENDA KEGIATAN PEMERINTAH DAERAH<br>
        KABUPATEN BULUNGAN
    </div>
    <img src="data:image/png;base64,' . $foto . '">
</div>

<table>
    <thead>
        <tr>
            <th>Nomor</th>
            <th>Nama Agenda</th>
            <th>Tanggal</th>
            <th>Waktu</th>
            <th>Peliput</th>
            <th>Protokoler</th>
            <th>Pejabat</th>
        </tr>
    </thead>
    <tbody>';

$no = 1;
foreach ($agendas as $row) {
    $peliput = get_relasi($pdo, $row['id'], 'peliput', 'peliput');
    $protokoler = get_relasi($pdo, $row['id'], 'protokoler', 'protokoler');

    $html .= '<tr>
        <td>' . $no++ . '</td>
        <td>' . htmlspecialchars($row['judul']) . '</td>
        <td>' . htmlspecialchars($row['tanggal']) . '</td>
        <td>' . htmlspecialchars($row['waktu']) . '</td>
        <td>' . $peliput . '</td>
        <td>' . $protokoler . '</td>
        <td>' . htmlspecialchars($row['pejabat_nama']) . '</td>
    </tr>';
}

$html .= '</tbody></table></body></html>';

// Simpan sebagai gambar PNG
$tmpFile = __DIR__ . '/agenda_export.png';

Browsershot::html($html)
    ->windowSize(1200, 800)
    ->showBackground()
    ->save($tmpFile);

// Tampilkan ke browser
header('Content-Type: image/png');
readfile($tmpFile);
unlink($tmpFile);
exit;