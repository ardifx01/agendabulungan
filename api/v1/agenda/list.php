<?php
require_once '../../../config/db.php';

// Set header JSON
header('Content-Type: application/json');

try {
    $stmt = $pdo->query("
        SELECT 
            a.id,
            a.judul,
            a.lokasi,
            a.tanggal,
            a.waktu,
            a.undangan,
            p.nama AS nama_pejabat
        FROM agenda a
        LEFT JOIN pejabat p ON a.pejabat_id = p.id
        ORDER BY a.tanggal ASC, a.waktu ASC
    ");

    $agendas = [];
    while ($agenda = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $agenda_id = $agenda['id'];

        // Ambil peliput
        $peliputStmt = $pdo->prepare("
            SELECT pl.nama 
            FROM agenda_peliput ap 
            JOIN peliput pl ON ap.peliput_id = pl.id 
            WHERE ap.agenda_id = ?
        ");
        $peliputStmt->execute([$agenda_id]);
        $peliput = $peliputStmt->fetchAll(PDO::FETCH_COLUMN);

        // Ambil protokoler
        $protokolerStmt = $pdo->prepare("
            SELECT pr.nama 
            FROM agenda_protokoler ap 
            JOIN protokoler pr ON ap.protokoler_id = pr.id 
            WHERE ap.agenda_id = ?
        ");
        $protokolerStmt->execute([$agenda_id]);
        $protokoler = $protokolerStmt->fetchAll(PDO::FETCH_COLUMN);

        $agenda['peliput'] = $peliput;
        $agenda['protokoler'] = $protokoler;

        $agendas[] = $agenda;
    }

    echo json_encode([
        'status' => 'success',
        'data' => $agendas
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}