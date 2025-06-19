<?php
require_once '../../vendor/autoload.php';
require_once '../../config/db.php';

use Dompdf\Dompdf;

// Ambil data agenda & pejabat
$stmt = $pdo->query("SELECT a.*, p.nama AS pejabat_nama
                     FROM agenda a
                     JOIN pejabat p ON a.pejabat_id = p.id
                     ORDER BY a.tanggal ASC");
$agendas = $stmt->fetchAll();

// Ambil peliput & protokoler per agenda
function get_relasi($pdo, $agenda_id, $table, $field) {
    $query = "SELECT $field.nama FROM agenda_$table 
              JOIN $field ON agenda_$table.{$field}_id = $field.id 
              WHERE agenda_$table.agenda_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$agenda_id]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Gambar base64
$logoBase64 = base64_encode(file_get_contents('../../assets/images/logobulungan.png'));
$fotoBase64 = base64_encode(file_get_contents('../../assets/images/bupatiwabup.png'));

// HTML
$html = '
<style>
    body { font-family: sans-serif; font-size: 12px; }
    .header { display: flex; justify-content: space-between; align-items: center; }
    .header img { height: 60px; }
    .title { text-align: center; font-size: 14px; font-weight: bold; line-height: 1.5; margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
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

<div class="header">
    <img src="data:image/png;base64,' . $logoBase64 . '">
    <div class="title">
        AGENDA KEGIATAN PEMERINTAH DAERAH<br>
        KABUPATEN BULUNGAN
    </div>
    <img src="data:image/png;base64,' . $fotoBase64 . '">
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
            <th>Pejabat Yang Menghadiri</th>
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
        <td>' . implode(', ', $peliput) . '</td>
        <td>' . implode(', ', $protokoler) . '</td>
        <td>' . htmlspecialchars($row['pejabat_nama']) . '</td>
    </tr>';
}

$html .= '</tbody></table>';

// Output PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('agenda_bulungan.pdf', ['Attachment' => false]);
exit;