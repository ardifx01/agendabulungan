<?php
require_once '../../config/db.php';

$tanggal_hari_ini = date('Y-m-d');

// Ambil data agenda hari ini
$stmt = $pdo->prepare("SELECT a.*, p.nama AS pejabat_nama 
                       FROM agenda a 
                       JOIN pejabat p ON a.pejabat_id = p.id 
                       WHERE a.tanggal = ? 
                       ORDER BY a.waktu ASC");
$stmt->execute([$tanggal_hari_ini]);
$agendas = $stmt->fetchAll();

function get_relasi($pdo, $agenda_id, $table, $field) {
    $stmt = $pdo->prepare("SELECT $field.nama FROM agenda_$table 
                           JOIN $field ON agenda_$table.{$field}_id = $field.id 
                           WHERE agenda_id = ?");
    $stmt->execute([$agenda_id]);
    return implode(', ', $stmt->fetchAll(PDO::FETCH_COLUMN));
}

// Base64 gambar
$logo = base64_encode(file_get_contents('../../assets/images/logobulungan.png'));
$foto = base64_encode(file_get_contents('../../assets/images/bupatiwabup.png'));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Agenda Harian</title>
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
        @media print {
            button { display: none; }
        }
    </style>
</head>
<body>

<button onclick="window.print()">🖨️ Cetak Halaman Ini</button>

<div class="header">
    <img src="data:image/png;base64,<?= $logo ?>">
    <div class="title">
        AGENDA KEGIATAN PEMERINTAH DAERAH<br>
        KABUPATEN BULUNGAN
    </div>
    <img src="data:image/png;base64,<?= $foto ?>">
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
    <tbody>
        <?php
        $no = 1;
        foreach ($agendas as $row):
            $peliput = get_relasi($pdo, $row['id'], 'peliput', 'peliput');
            $protokoler = get_relasi($pdo, $row['id'], 'protokoler', 'protokoler');
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['judul']) ?></td>
            <td><?= $row['tanggal'] ?></td>
            <td><?= $row['waktu'] ?></td>
            <td><?= $peliput ?></td>
            <td><?= $protokoler ?></td>
            <td><?= htmlspecialchars($row['pejabat_nama']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>