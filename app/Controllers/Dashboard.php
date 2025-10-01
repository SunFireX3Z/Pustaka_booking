<?php

namespace App\Controllers;

use App\Models\BukuModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $bukuModel = new BukuModel();

        // Data untuk card statistik
        $data['jumlahAnggota'] = $userModel->countAllResults();
        $data['stokBuku'] = $bukuModel->selectSum('stok')->first()['stok'] ?? 0;
        $data['dipinjam'] = $bukuModel->selectSum('dipinjam')->first()['dipinjam'] ?? 0;
        $data['dibooking'] = $bukuModel->selectSum('dibooking')->first()['dibooking'] ?? 0;

        // Data untuk tabel anggota & buku terbaru
        $data['users'] = $userModel->orderBy('tanggal_input', 'DESC')->limit(6)->find();
        $data['buku'] = $bukuModel->orderBy('id', 'DESC')->limit(4)->find();

        // Data untuk Grafik Tren Anggota Baru (Dinamis)
        $memberTrendData = $this->getMemberTrend();
        $data['memberTrendLabels'] = json_encode($memberTrendData['labels']);
        $data['memberTrendCounts'] = json_encode($memberTrendData['counts']);

        return view('dashboard', $data);
    }

    /**
     * Mengambil data tren pendaftaran anggota untuk 6 bulan terakhir.
     *
     * @return array
     */
    private function getMemberTrend(): array
    {
        $userModel = new UserModel();
        $monthlyData = [];

        // Inisialisasi 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $monthlyData[$month] = 0;
        }

        // Ambil data dari database
        $sixMonthsAgo = date('Y-m-01', strtotime('-5 months'));
        $result = $userModel
            ->select("COUNT(id) as count, DATE_FORMAT(tanggal_input, '%Y-%m') as month")
            ->where('tanggal_input >=', $sixMonthsAgo)
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->findAll();

        // Isi data dari hasil query
        foreach ($result as $row) {
            if (isset($monthlyData[$row['month']])) {
                $monthlyData[$row['month']] = (int) $row['count'];
            }
        }

        // Format nama bulan untuk label chart
        $labels = array_map(fn($m) => date('M Y', strtotime($m . '-01')), array_keys($monthlyData));

        return [
            'labels' => $labels,
            'counts' => array_values($monthlyData),
        ];
    }
}