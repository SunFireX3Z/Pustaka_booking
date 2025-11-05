<?php

namespace App\Controllers;

use App\Models\WebProfileModel;
use App\Models\UserModel;

class ProfileWeb extends BaseController
{
    protected $webProfileModel;
    protected $userModel;

    public function __construct()
    {
        $this->webProfileModel = new WebProfileModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Ambil data profil, atau buat data default jika belum ada
        $profile = $this->webProfileModel->find(1);
        if (!$profile) {
            // Data default jika tabel kosong
            $defaultData = [
                'id' => 1,
                'nama_instansi' => 'Perpustakaan Umum',
                'nama_aplikasi' => 'Pustaka Booking',
                'max_buku_pinjam' => 3,
                'max_hari_pinjam' => 7,
                'logo' => 'default_logo.png'
            ];
            $this->webProfileModel->insert($defaultData);
            $profile = $this->webProfileModel->find(1);
        }

        // Ambil data admin (user dengan role_id = 1)
        $adminUser = $this->userModel->where('role_id', 1)->first();

        $data = [
            'title' => 'Profil Website',
            'profile' => $profile,
            'admin_user' => $adminUser,
            'validation' => \Config\Services::validation()
        ];

        return view('pages/admin/profileweb', $data);
    }

    public function update()
    {
        $rules = $this->validate([
            'nama_aplikasi'   => 'required',
            'nama_instansi'   => 'required',
            'alamat'          => 'permit_empty|string',
            'kabupaten_kota'  => 'permit_empty|string',
            'npwp'            => 'permit_empty|string',
            'nama_penanggung_jawab'    => 'permit_empty|string',
            'jabatan_penanggung_jawab' => 'permit_empty|string',
            'nama_penandatangan_mou'   => 'permit_empty|string',
            'jabatan_penandatangan_mou'=> 'permit_empty|string',
            'denda_per_hari'  => 'required|integer|greater_than_equal_to[0]',
            'max_buku_pinjam' => 'required|integer|greater_than[0]',
            'max_hari_pinjam' => 'required|integer|greater_than[0]',
            'logo'           => 'max_size[logo,1024]|is_image[logo]|mime_in[logo,image/jpg,image/jpeg,image/png,image/webp]',
            'banner_image' => 'max_size[banner_image,2048]|is_image[banner_image]|mime_in[banner_image,image/jpg,image/jpeg,image/png,image/webp]'
        ]);

        if (!$rules) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $profileModel = new WebProfileModel();
        $oldProfile = $profileModel->find(1);

        $logoName = $oldProfile['logo'] ?? 'default_logo.png';
        $bannerName = $oldProfile['banner_image'] ?? 'default_banner.png';

        // Handle upload logo
        $logoFile = $this->request->getFile('logo');
        if ($logoFile && $logoFile->isValid() && !$logoFile->hasMoved()) {
            $logoName = $logoFile->getRandomName();
            $logoFile->move(ROOTPATH . 'public/uploads/profile', $logoName);
            // Hapus logo lama jika ada dan bukan file default
            if (!empty($oldProfile['logo']) && $oldProfile['logo'] !== 'default_logo.png' && file_exists(ROOTPATH . 'public/uploads/profile/' . $oldProfile['logo'])) {
                unlink(ROOTPATH . 'public/uploads/profile/' . $oldProfile['logo']);
            }
        }

        // Handle upload banner
        $bannerFile = $this->request->getFile('banner_image');
        if ($bannerFile && $bannerFile->isValid() && !$bannerFile->hasMoved()) {
            $bannerName = $bannerFile->getRandomName();
            $bannerFile->move(ROOTPATH . 'public/uploads/profile', $bannerName);
            // Hapus banner lama jika ada
            if (!empty($oldProfile['banner_image']) && file_exists(ROOTPATH . 'public/uploads/profile/' . $oldProfile['banner_image'])) {
                unlink(ROOTPATH . 'public/uploads/profile/' . $oldProfile['banner_image']);
            }
        }

        $data = [
            'nama_aplikasi' => $this->request->getPost('nama_aplikasi'),
            'nama_instansi' => $this->request->getPost('nama_instansi'),
            'alamat' => $this->request->getPost('alamat'),
            'kabupaten_kota' => $this->request->getPost('kabupaten_kota'),
            'npwp' => $this->request->getPost('npwp'),
            'nama_penanggung_jawab' => $this->request->getPost('nama_penanggung_jawab'),
            'jabatan_penanggung_jawab' => $this->request->getPost('jabatan_penanggung_jawab'),
            'nama_penandatangan_mou' => $this->request->getPost('nama_penandatangan_mou'),
            'jabatan_penandatangan_mou' => $this->request->getPost('jabatan_penandatangan_mou'),
            'denda_per_hari' => $this->request->getPost('denda_per_hari'),
            'max_buku_pinjam' => $this->request->getPost('max_buku_pinjam'),
            'max_hari_pinjam' => $this->request->getPost('max_hari_pinjam'),
            'logo' => $logoName, // Nama file logo (baru atau lama)
            'banner_image' => $bannerName // Nama file banner (baru atau lama)
        ];

        $profileModel->update(1, $data);

        session()->setFlashdata('success', 'Profil web berhasil diperbarui.');
        return redirect()->to('/profile-web');
    }
}