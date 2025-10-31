<?php
namespace App\Controllers;

use App\Models\RoleModel;
use App\Models\UserModel;

class Anggota extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $roleModel = new RoleModel();
        $data['anggota'] = $userModel
            ->select('user.*, role.role')
            ->join('role', 'role.id = user.role_id', 'left')
            ->orderBy('user.id', 'DESC')
            ->findAll();
        $data['roles'] = $roleModel->findAll();
        $data['validation'] = \Config\Services::validation();

        return view('pages/admin/anggota', $data);
    }

    public function create()
    {
        helper('form');
        $validation = \Config\Services::validation();

        // Menambahkan aturan validasi untuk field sekolah
        $rules = [
            'nama'     => 'required|min_length[3]',
            'email'    => 'required|valid_email|is_unique[user.email]',
            'password' => 'required|min_length[6]',
            'role_id'  => 'required|integer',
            'nis'      => 'permit_empty|numeric',
            'nisn'     => 'permit_empty|numeric',
            'kelas'    => 'permit_empty|string',
            'jurusan'  => 'permit_empty|string',
            'no_hp'         => 'permit_empty|numeric',
            'tanggal_lahir' => 'permit_empty|valid_date',
            'jenis_kelamin' => 'permit_empty|in_list[Laki-laki,Perempuan]',
            'nik'           => 'permit_empty|numeric|min_length[16]|max_length[16]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                                             ->with('validation', $validation)
                                             ->with('show_modal', 'add');
        }

        $imageName = 'default.png';
        $croppedImage = $this->request->getPost('cropped_image');

        if ($croppedImage) {
            // Decode base64 string
            list($type, $data) = explode(';', $croppedImage);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);

            // Generate a random name and save the file
            $imageName = uniqid() . '.jpg';
            file_put_contents('uploads/' . $imageName, $data);
        }

        $userModel = new UserModel();
        $userModel->insert([
            'nama'          => $this->request->getPost('nama'),
            'email'         => $this->request->getPost('email'),
            'password'      => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'image'         => $imageName,
            'role_id'       => $this->request->getPost('role_id'),
            // Menambahkan field sekolah
            'nis'           => $this->request->getPost('nis'),
            'nisn'          => $this->request->getPost('nisn'),
            'kelas'         => $this->request->getPost('kelas'),
            'jurusan'       => $this->request->getPost('jurusan'),
            'no_hp'         => $this->request->getPost('no_hp'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'nik'           => $this->request->getPost('nik'),
            'is_active'     => 1,
            'tanggal_input' => date('Y-m-d H:i:s'),
        ]);

        session()->setFlashdata('success', 'Anggota baru berhasil ditambahkan.');
        return redirect()->to('/anggota');
    }

    public function update($id)
    {
        $userModel = new UserModel();
        
        // Menambahkan aturan validasi untuk field sekolah
        $rules = [
            'nama'  => 'required|min_length[3]',
            'email' => "required|valid_email|is_unique[user.email,id,{$id}]",
            'role_id'  => 'required|integer',
            'nis'      => 'permit_empty|numeric',
            'nisn'     => 'permit_empty|numeric',
            'kelas'    => 'permit_empty|string',
            'jurusan'  => 'permit_empty|string',
            'no_hp'         => 'permit_empty|numeric',
            'tanggal_lahir' => 'permit_empty|valid_date',
            'jenis_kelamin' => 'permit_empty|in_list[Laki-laki,Perempuan]',
            'nik'           => 'permit_empty|numeric|min_length[16]|max_length[16]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                                             ->with('validation', $this->validator)
                                             ->with('show_modal', 'edit')
                                             ->with('edit_id', $id);
        }

        $anggota = $userModel->find($id);
        $imageName = $anggota['image'];
        $croppedImage = $this->request->getPost('cropped_image');

        if ($croppedImage) {
            // Hapus gambar lama jika bukan default
            if ($anggota['image'] !== 'default.png' && file_exists('uploads/' . $anggota['image'])) {
                unlink('uploads/' . $anggota['image']);
            }

            // Decode base64 string
            list($type, $data) = explode(';', $croppedImage);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);

            // Generate a random name and save the file
            $imageName = uniqid() . '.jpg';
            file_put_contents('uploads/' . $imageName, $data);
        }

        $dataToSave = [
            'nama' => $this->request->getPost('nama'), 
            'email' => $this->request->getPost('email'),
            'role_id' => $this->request->getPost('role_id'),
            // Menambahkan field sekolah
            'nis'           => $this->request->getPost('nis'),
            'nisn'          => $this->request->getPost('nisn'),
            'kelas'         => $this->request->getPost('kelas'),
            'jurusan'       => $this->request->getPost('jurusan'),
            'no_hp'         => $this->request->getPost('no_hp'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'nik'           => $this->request->getPost('nik'),
            'image' => $imageName
        ];

        $userModel->update($id, $dataToSave);

        // Cek apakah user yang diupdate adalah user yang sedang login
        if ($id == session()->get('user_id')) {
            $roleModel = new RoleModel();
            $roleData = $roleModel->find($dataToSave['role_id']);

            // Buat data sesi baru dari data yang baru saja disimpan
            $newSessionData = [
                'user_id'    => $id,
                'nama'       => $dataToSave['nama'],
                'email'      => $dataToSave['email'],
                'image'      => $dataToSave['image'],
                'role_id'    => $dataToSave['role_id'],
                'role'       => $roleData['role'] ?? 'member',
                'isLoggedIn' => true,
            ];
            // Set ulang data sesi
            session()->set($newSessionData);
        }

        session()->setFlashdata('success', 'Data anggota berhasil diperbarui.');
        return redirect()->to('/anggota');
    }

    public function delete($id)
    {
        $userModel = new UserModel();
        $anggota = $userModel->find($id);

        if ($anggota) {
            // Hapus gambar jika bukan default
            if ($anggota['image'] !== 'default.png' && file_exists('uploads/' . $anggota['image'])) {
                unlink('uploads/' . $anggota['image']);
            }
            $userModel->delete($id);
        }
        session()->setFlashdata('success', 'Anggota berhasil dihapus.');
        return redirect()->to('/anggota');
    }
}