<?php
require_once '../../vendor/autoload.php';
require_once '../../config/db.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Ambil data agenda
$stmt = $pdo->query("SELECT a.*, p.nama AS pejabat_nama 
                     FROM agenda a 
                     JOIN pejabat p ON a.pejabat_id = p.id 
                     ORDER BY a.tanggal ASC");
$agendas = $stmt->fetchAll();

// Fungsi ambil peliput/protokoler
function get_relasi($pdo, $agenda_id, $table, $field) {
    $stmt = $pdo->prepare("SELECT $field.nama FROM agenda_$table 
                           JOIN $field ON agenda_$table.{$field}_id = $field.id 
                           WHERE agenda_id = ?");
    $stmt->execute([$agenda_id]);
    return implode(', ', $stmt->fetchAll(PDO::FETCH_COLUMN));
}

// Buat spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Judul
$sheet->setCellValue('A1', 'LAPORAN AGENDA KEGIATAN PEMERINTAH DAERAH');
$sheet->setCellValue('A2', 'KABUPATEN BULUNGAN');
$sheet->mergeCells('A1:G1')->mergeCells('A2:G2');
$sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A1:A2')->getAlignment()->setHorizontal('center');

// Header tabel
$headers = ['Nomor', 'Nama Agenda', 'Tanggal', 'Waktu', 'Peliput', 'Protokoler', 'Pejabat Yang Menghadiri'];
$sheet->fromArray($headers, null, 'A4');
$sheet->getStyle('A4:G4')->getFont()->setBold(true);
$sheet->getStyle('A4:G4')->getFill()->setFillType('solid')->getStartColor()->setARGB('FFE97700');
$sheet->getStyle('A4:G4')->getAlignment()->setHorizontal('center');

// Isi data
$rowNum = 5;
$no = 1;
foreach ($agendas as $agenda) {
    $sheet->setCellValue("A$rowNum", $no++);
    $sheet->setCellValue("B$rowNum", $agenda['judul']);
    $sheet->setCellValue("C$rowNum", $agenda['tanggal']);
    $sheet->setCellValue("D$rowNum", $agenda['waktu']);
    $sheet->setCellValue("E$rowNum", get_relasi($pdo, $agenda['id'], 'peliput', 'peliput'));
    $sheet->setCellValue("F$rowNum", get_relasi($pdo, $agenda['id'], 'protokoler', 'protokoler'));
    $sheet->setCellValue("G$rowNum", $agenda['pejabat_nama']);
    $rowNum++;
}

// Auto width
foreach (range('A', 'G') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Output ke browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="agenda_bulungan.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;