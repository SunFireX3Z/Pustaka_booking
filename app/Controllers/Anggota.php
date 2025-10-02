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

        $rules = [
            'nama'     => 'required|min_length[3]',
            'email'    => 'required|valid_email|is_unique[user.email]',
            'password' => 'required|min_length[6]',
            'role_id'  => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/anggota')->withInput()
                                             ->with('validation', $validation)
                                             ->with('show_modal', 'add');
        }

        $imageName = 'default.jpg';
        $croppedImage = $this->request->getPost('cropped_image');

        if ($croppedImage) {
            // Decode base64 string
            $imgData = str_replace('data:image/jpeg;base64,', '', $croppedImage);
            $imgData = str_replace(' ', '+', $imgData);
            $decodedImage = base64_decode($imgData);

            // Generate a random name and save the file
            $imageName = uniqid() . '.jpg';
            file_put_contents(FCPATH . 'uploads/' . $imageName, $decodedImage);
        }

        $userModel = new UserModel();
        $userModel->save([
            'nama'          => $this->request->getPost('nama'),
            'email'         => $this->request->getPost('email'),
            'password'      => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'image'         => $imageName,
            'role_id'       => $this->request->getPost('role_id'),
            'is_active'     => 1,
            'tanggal_input' => date('Y-m-d H:i:s'),
        ]);

        session()->setFlashdata('success', 'Anggota baru berhasil ditambahkan.');
        return redirect()->to('/anggota');
    }

    public function update($id)
    {
        $userModel = new UserModel();
        $rules = [
            'nama'  => 'required|min_length[3]',
            'email' => "required|valid_email|is_unique[user.email,id,{$id}]",
            'role_id'  => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/anggota')->withInput()
                                             ->with('validation', $this->validator)
                                             ->with('show_modal', 'edit')
                                             ->with('edit_id', $id);
        }

        $anggota = $userModel->find($id);
        $imageName = $anggota['image'];
        $croppedImage = $this->request->getPost('cropped_image');

        if ($croppedImage) {
            // Decode base64 string
            $imgData = str_replace('data:image/jpeg;base64,', '', $croppedImage);
            $imgData = str_replace(' ', '+', $imgData);
            $decodedImage = base64_decode($imgData);

            // Generate a random name and save the file
            $imageName = uniqid() . '.jpg';
            file_put_contents(FCPATH . 'uploads/' . $imageName, $decodedImage);

            // Hapus gambar lama jika bukan default
            if ($anggota['image'] !== 'default.jpg' && file_exists(FCPATH . 'uploads/' . $anggota['image'])) {
                unlink(FCPATH . 'uploads/' . $anggota['image']);
            }
        }

        $userModel->update($id, [
            'nama' => $this->request->getPost('nama'), 
            'email' => $this->request->getPost('email'),
            'role_id' => $this->request->getPost('role_id'),
            'image' => $imageName
        ]);
        session()->setFlashdata('success', 'Data anggota berhasil diperbarui.');
        return redirect()->to('/anggota');
    }

    public function delete($id)
    {
        $userModel = new UserModel();
        $anggota = $userModel->find($id);

        if ($anggota) {
            // Hapus gambar jika bukan default
            if ($anggota['image'] !== 'default.jpg' && file_exists(FCPATH . 'uploads/' . $anggota['image'])) {
                unlink(FCPATH . 'uploads/' . $anggota['image']);
            }
            $userModel->delete($id);
        }
        session()->setFlashdata('success', 'Anggota berhasil dihapus.');
        return redirect()->to('/anggota');
    }
}