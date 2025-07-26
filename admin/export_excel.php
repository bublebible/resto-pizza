<?php
// Panggil file koneksi
require_once 'config/database.php';

// Dapatkan tanggal dari parameter URL
$tanggal_mulai = $_GET['tanggal_mulai'] ?? date('Y-m-d');
$tanggal_akhir = $_GET['tanggal_akhir'] ?? date('Y-m-d');
$tanggal_akhir_query = $tanggal_akhir . ' 23:59:59';

// Ambil data yang akan diexport
$stmt_detail = $koneksi->prepare("
    SELECT * FROM pesanan 
    WHERE status_pesanan = 'Selesai' AND tanggal_pesanan BETWEEN ? AND ? 
    ORDER BY tanggal_pesanan ASC
");
$stmt_detail->execute([$tanggal_mulai, $tanggal_akhir_query]);
$laporan_data = $stmt_detail->fetchAll();

// Atur header untuk memberitahu browser bahwa ini adalah file Excel
$filename = "Laporan_Pendapatan_" . $tanggal_mulai . "_sampai_" . $tanggal_akhir . ".xls";
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");

// Mulai membuat konten file Excel (dalam format tabel HTML)
$output = "
    <h2>Laporan Pendapatan</h2>
    <p>Periode: {$tanggal_mulai} sampai {$tanggal_akhir}</p>
    <table border='1'>
        <thead>
            <tr>
                <th>ID Pesanan</th>
                <th>Tanggal</th>
                <th>Nama Pelanggan</th>
                <th>Total Harga</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
";

$total_pendapatan = 0;
foreach ($laporan_data as $data) {
    $total_pendapatan += $data['total_harga'];
    $output .= "
        <tr>
            <td>#{$data['id']}</td>
            <td>".date('d M Y, H:i', strtotime($data['tanggal_pesanan']))."</td>
            <td>".htmlspecialchars($data['nama_pelanggan'])."</td>
            <td>".number_format($data['total_harga'], 0, ',', '.')."</td>
            <td>".htmlspecialchars($data['status_pesanan'])."</td>
        </tr>
    ";
}

$output .= "
        <tr>
            <td colspan='3' style='font-weight:bold; text-align:right;'>TOTAL</td>
            <td style='font-weight:bold;'>".number_format($total_pendapatan, 0, ',', '.')."</td>
            <td></td>
        </tr>
    </tbody>
    </table>
";

// Cetak output ke browser
echo $output;
exit;
?>