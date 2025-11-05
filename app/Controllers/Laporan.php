<?php

namespace App\Controllers;

use App\Models\PeminjamanModel;
use App\Models\BukuModel;
use App\Models\UserModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

class Laporan extends BaseController
{
    public function index()
    {
        return view('pages/admin/laporan');
    }

    /**
     * Mengambil data buku yang akan digunakan untuk laporan.
     */
    private function getBukuData()
    {
        $bukuModel = new BukuModel();
        return $bukuModel->select('buku.*, GROUP_CONCAT(kategori.nama_kategori SEPARATOR ", ") as kategori_nama')
            ->join('buku_kategori', 'buku_kategori.buku_id = buku.id', 'left')
            ->join('kategori', 'kategori.id_kategori = buku_kategori.kategori_id', 'left')
            ->groupBy('buku.id')
            ->orderBy('buku.judul_buku', 'ASC')
            ->findAll();
    }

    /**
     * Mengambil data anggota (member) yang akan digunakan untuk laporan.
     */
    private function getAnggotaData()
    {
        $userModel = new UserModel();
        // Mengambil data dengan role_id = 2 (Member)
        return $userModel->where('role_id', 2)
            ->orderBy('nama', 'ASC')
            ->findAll();
    }

    /**
     * Mengambil data peminjaman yang akan digunakan untuk laporan.
     */
    private function getPeminjamanData()
    {
        $peminjamanModel = new PeminjamanModel();
        return $peminjamanModel
            ->select('peminjaman.*, user.nama as nama_user, GROUP_CONCAT(buku.judul_buku SEPARATOR ", ") as judul_buku')
            ->join('user', 'user.id = peminjaman.id_user')
            ->join('detail_peminjaman', 'detail_peminjaman.id_pinjam = peminjaman.id_pinjam', 'left')
            ->join('buku', 'buku.id = detail_peminjaman.id_buku', 'left')
            ->groupBy('peminjaman.id_pinjam')
            ->orderBy('peminjaman.tanggal_pinjam', 'DESC')
            ->findAll();
    }


    /**
     * Generate Laporan Buku dalam format Excel.
     */
    public function bukuExcel()
    {
        $bukuData = $this->getBukuData();
        $profile = get_web_profile();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set Judul
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'Laporan Data Buku - ' . ($profile['nama_aplikasi'] ?? 'Perpustakaan'));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Set Header Tabel
        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Judul Buku');
        $sheet->setCellValue('C3', 'Pengarang');
        $sheet->setCellValue('D3', 'Penerbit');
        $sheet->setCellValue('E3', 'Tahun');
        $sheet->setCellValue('F3', 'ISBN');
        $sheet->setCellValue('G3', 'Stok');
        $sheet->setCellValue('H3', 'Kategori');
        $sheet->getStyle('A3:H3')->getFont()->setBold(true);

        // Isi Data
        $row = 4;
        foreach ($bukuData as $key => $buku) {
            $sheet->setCellValue('A' . $row, $key + 1);
            $sheet->setCellValue('B' . $row, $buku['judul_buku']);
            $sheet->setCellValue('C' . $row, $buku['pengarang']);
            $sheet->setCellValue('D' . $row, $buku['penerbit']);
            $sheet->setCellValue('E' . $row, $buku['tahun_terbit']);
            $sheet->setCellValue('F' . $row, $buku['isbn']);
            $sheet->setCellValue('G' . $row, $buku['stok']);
            $sheet->setCellValue('H' . $row, $buku['kategori_nama'] ?? 'Tidak ada kategori');
            $row++;
        }

        // Atur lebar kolom otomatis
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Tulis file dan kirim ke browser
        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan_data_buku_' . date('Y-m-d') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }

    /**
     * Generate Laporan Buku dalam format PDF untuk dicetak.
     */

    public function bukuPdf()
    {
        // 🔹 Naikkan limit memory & waktu sebelum Dompdf mulai render
        ini_set('memory_limit', '512M');   // Maksimal 512 MB RAM
        set_time_limit(300);               // Maksimal 300 detik (5 menit)

        $data['buku'] = $this->getBukuData();
        $data['title'] = "Laporan Data Buku";
        $data['web_profile'] = get_web_profile(); // Menambahkan data profil web

        // Render view ke dalam HTML
        $html = view('pages/admin/laporan/laporan_buku_pdf', $data);

        // Konfigurasi Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Output PDF ke browser
        $dompdf->stream('laporan_data_buku.pdf', ['Attachment' => false]);
    }

    /**
     * Generate Laporan Anggota dalam format Excel.
     */
    public function anggotaExcel()
    {
        $anggotaData = $this->getAnggotaData();
        $profile = get_web_profile();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set Judul
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'Laporan Data Anggota - ' . ($profile['nama_aplikasi'] ?? 'Perpustakaan'));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Set Header Tabel
        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Nama');
        $sheet->setCellValue('C3', 'Email');
        $sheet->setCellValue('D3', 'NISN');
        $sheet->setCellValue('E3', 'Kelas');
        $sheet->setCellValue('F3', 'Jenis Kelamin');
        $sheet->setCellValue('G3', 'No. HP');
        $sheet->setCellValue('H3', 'Tanggal Bergabung');
        $sheet->getStyle('A3:H3')->getFont()->setBold(true);

        // Isi Data
        $row = 4;
        foreach ($anggotaData as $key => $anggota) {
            $sheet->setCellValue('A' . $row, $key + 1);
            $sheet->setCellValue('B' . $row, $anggota['nama']);
            $sheet->setCellValue('C' . $row, $anggota['email']);
            $sheet->setCellValue('D' . $row, $anggota['nisn'] ?? '-');
            $sheet->setCellValue('E' . $row, $anggota['kelas'] ?? '-');
            $sheet->setCellValue('F' . $row, $anggota['jenis_kelamin'] ?? '-');
            $sheet->setCellValue('G' . $row, $anggota['no_hp'] ?? '-');
            $sheet->setCellValue('H' . $row, date('d M Y', strtotime($anggota['tanggal_input'])));
            $row++;
        }

        // Atur lebar kolom otomatis
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Tulis file dan kirim ke browser
        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan_data_anggota_' . date('Y-m-d') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }

    /**
     * Generate Laporan Anggota dalam format PDF untuk dicetak.
     */
    public function anggotaPdf()
    {
        $data['anggota'] = $this->getAnggotaData();
        $data['title'] = "Laporan Data Anggota";
        $data['web_profile'] = get_web_profile(); // Menambahkan data profil web

        // Menggunakan view baru dengan desain yang telah diperbarui
        $html = view('pages/admin/laporan/laporan_anggota_pdf', $data);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        // Mengubah orientasi kertas menjadi Portrait
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('laporan_data_anggota.pdf', ['Attachment' => 0]);
    }

    /**
     * Generate Laporan Peminjaman dalam format Excel.
     */
    public function peminjamanExcel()
    {
        $peminjamanData = $this->getPeminjamanData();
        $profile = get_web_profile();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set Judul
        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A1', 'Laporan Data Peminjaman - ' . ($profile['nama_aplikasi'] ?? 'Perpustakaan'));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Set Header Tabel
        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'ID Pinjam');
        $sheet->setCellValue('C3', 'Nama Anggota');
        $sheet->setCellValue('D3', 'Judul Buku');
        $sheet->setCellValue('E3', 'Tgl Pinjam');
        $sheet->setCellValue('F3', 'Tgl Kembali');
        $sheet->setCellValue('G3', 'Tgl Dikembalikan');
        $sheet->setCellValue('H3', 'Status');
        $sheet->setCellValue('I3', 'Total Denda');
        $sheet->getStyle('A3:I3')->getFont()->setBold(true);

        // Isi Data
        $row = 4;
        foreach ($peminjamanData as $key => $pinjam) {
            $sheet->setCellValue('A' . $row, $key + 1);
            $sheet->setCellValue('B' . $row, '#' . $pinjam['id_pinjam']);
            $sheet->setCellValue('C' . $row, $pinjam['nama_user']);
            $sheet->setCellValue('D' . $row, $pinjam['judul_buku'] ?? 'Buku tidak ditemukan');
            $sheet->setCellValue('E' . $row, date('d M Y', strtotime($pinjam['tanggal_pinjam'])));
            $sheet->setCellValue('F' . $row, date('d M Y', strtotime($pinjam['tanggal_kembali'])));
            $sheet->setCellValue('G' . $row, $pinjam['tanggal_dikembalikan'] ? date('d M Y', strtotime($pinjam['tanggal_dikembalikan'])) : '-');
            $sheet->setCellValue('H' . $row, ucfirst($pinjam['status']));
            $sheet->setCellValue('I' . $row, 'Rp ' . number_format($pinjam['total_denda'], 0, ',', '.'));
            $row++;
        }

        // Atur lebar kolom otomatis
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Tulis file dan kirim ke browser
        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan_data_peminjaman_' . date('Y-m-d') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }

    /**
     * Generate Laporan Peminjaman dalam format PDF untuk dicetak.
     */
    public function peminjamanpdf()
    {
        $data['peminjaman'] = $this->getPeminjamanData();
        $data['title'] = "Laporan Data Peminjaman";
        $data['web_profile'] = get_web_profile(); // Sama kayak laporan anggota

        // View-nya pun biar konsisten
        $html = view('pages/admin/laporan/laporan_peminjaman_pdf', $data);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        // HARUS sama orientasi-nya
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('laporan_data_peminjaman.pdf', ['Attachment' => 0]);
    }
}