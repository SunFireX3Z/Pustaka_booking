<?php

namespace App\Controllers;
use App\Models\PeminjamanModel;

use App\Models\DendaModel;

class Denda extends BaseController
{
    public function index()
    {
        $dendaModel = new DendaModel();

        // Mengambil data denda beserta nama user
        $data['denda'] = $dendaModel
            ->select('denda.*, user.nama as nama_user, peminjaman.tanggal_kembali, peminjaman.tanggal_dikembalikan')
            ->join('user', 'user.id = denda.id_user')
            ->join('peminjaman', 'peminjaman.id_pinjam = denda.id_pinjam')
            ->orderBy('denda.id_denda', 'DESC')
            ->findAll();

        return view('pages/admin/denda', $data);
    }

    public function bayar($id_denda)
    {
        $dendaModel = new DendaModel();
        $dendaModel->update($id_denda, [
            'status' => 'sudah bayar',
            'tanggal_bayar' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/denda')->with('success', 'Status denda berhasil diubah menjadi Lunas.');
    }

    public function delete($id_denda)
    {
        $dendaModel = new DendaModel();
        $denda = $dendaModel->find($id_denda);

        if ($denda) {
            // Sebaiknya, hanya denda yang sudah lunas yang bisa dihapus untuk menjaga integritas data.
            // Pengecekan ini memberikan lapisan keamanan tambahan di sisi server.
            if ($denda['status'] !== 'sudah bayar') {
                return redirect()->to('/denda')->with('error', 'Hanya denda yang sudah lunas yang dapat dihapus.');
            }

            if ($dendaModel->delete($id_denda)) {
                return redirect()->to('/denda')->with('success', 'Data denda berhasil dihapus.');
            } else {
                return redirect()->to('/denda')->with('error', 'Gagal menghapus data denda.');
            }
        } else {
            return redirect()->to('/denda')->with('error', 'Data denda tidak ditemukan.');
        }
    }
}