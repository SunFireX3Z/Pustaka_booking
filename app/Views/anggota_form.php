<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= isset($anggota) ? 'Edit Anggota' : 'Tambah Anggota' ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
  <style>
    .sidebar-is-collapsed #sidebar { width: 4rem; }
    .sidebar-is-collapsed #sidebar .sidebar-text { display: none; }
    .sidebar-is-collapsed #sidebar .sidebar-logo-text { display: none; }
    .sidebar-is-collapsed #sidebar .sidebar-menu-title { text-align: center; }
    .sidebar-is-collapsed #sidebar .sidebar-menu-item i { margin-right: 0; }
    .sidebar-is-collapsed #main-content { margin-left: 4rem; } /* 4rem sidebar + 1rem margin */
    .sidebar-is-collapsed #sidebar .sidebar-menu-item { justify-content: center; }
  </style>
  <script>
    if (localStorage.getItem('sidebarCollapsed') === 'true') {
      document.documentElement.classList.add('sidebar-is-collapsed');
    }
  </script>
</head>
<body class="bg-gray-100 flex">

  <?php
    $current_page = 'anggota';
    $active_class = 'bg-slate-800 text-white border-l-4 border-blue-500';
    $inactive_class = 'text-slate-400 hover:bg-slate-800 hover:text-white transition-colors duration-200 border-l-4 border-transparent';
  ?>

  <!-- Sidebar -->
  <aside id="sidebar" class="fixed top-0 left-0 flex h-screen w-64 flex-col bg-slate-900 text-gray-200 transition-all duration-300">
    <!-- Logo -->
    <div class="flex items-center gap-3 p-4">
      <a href="<?= base_url('dashboard') ?>" class="flex items-center gap-3">
        <i class="fas fa-book-reader text-4xl text-blue-400"></i>
        <div class="sidebar-logo-text">
          <span class="text-white font-bold text-xl block">Pustaka</span>
          <span class="text-slate-400 text-sm">App Perpustakaan</span>
        </div>
      </a>
    </div>

    <!-- Menu -->
    <nav class="flex-1 space-y-2 p-4">
      <h3 class="sidebar-menu-title px-3 text-xs font-semibold uppercase text-slate-500"><span class="sidebar-text">Menu Utama</span></h3>
      <div class="flex flex-col space-y-1">
        <a href="<?= base_url('dashboard') ?>" class="sidebar-menu-item flex items-center gap-3 rounded-md px-3 py-2 <?= $current_page === 'dashboard' ? $active_class : $inactive_class ?>">
          <i class="fas fa-tachometer-alt w-5 text-center"></i> <span class="sidebar-text">Dashboard</span>
        </a>
        <a href="<?= base_url('anggota') ?>" class="sidebar-menu-item flex items-center gap-3 rounded-md px-3 py-2 <?= $current_page === 'anggota' ? $active_class : $inactive_class ?>">
          <i class="fas fa-users w-5 text-center"></i> <span class="sidebar-text">Anggota</span>
        </a>
        <a href="<?= base_url('buku') ?>" class="sidebar-menu-item flex items-center gap-3 rounded-md px-3 py-2 <?= $current_page === 'buku' ? $active_class : $inactive_class ?>">
          <i class="fas fa-book w-5 text-center"></i> <span class="sidebar-text">Buku</span>
        </a>
        <a href="<?= base_url('kategori') ?>" class="sidebar-menu-item flex items-center gap-3 rounded-md px-3 py-2 <?= $current_page === 'kategori' ? $active_class : $inactive_class ?>">
          <i class="fas fa-tags w-5 text-center"></i> <span class="sidebar-text">Kategori</span>
        </a>
      </div>
    </nav>

    <!-- Logout -->
    <div class="p-4">
      <a href="<?= base_url('logout') ?>" class="sidebar-menu-item flex items-center gap-3 rounded-md px-3 py-2 <?= $inactive_class ?>">
        <i class="fas fa-sign-out-alt w-5 text-center"></i> <span class="sidebar-text">Logout</span>
      </a>
    </div>
  </aside>

  <!-- Main content -->
  <div id="main-content" class="flex-1 flex flex-col ml-64 transition-all duration-300">

    <!-- Header bar -->
    <header class="bg-white shadow-sm p-4 flex justify-between items-center">
      <div class="flex items-center">
        <button id="sidebar-toggle" class="text-gray-500 hover:text-gray-700 focus:outline-none mr-4"><i class="fas fa-bars text-lg"></i></button>
        <h1 class="text-xl font-semibold"><?= isset($anggota) ? 'Edit Anggota' : 'Tambah Anggota' ?></h1>
      </div>
      <div class="flex items-center space-x-3">
        <?php 
          $userImage = session()->get('image') ?? 'default.png';
          $userName  = session()->get('nama') ?? 'User';
        ?>
        <img src="<?= base_url('uploads/' . $userImage) ?>" alt="User" class="w-8 h-8 rounded-full">
        <span class="font-medium"><?= esc($userName) ?></span>
      </div>
    </header>

    <!-- Content -->
    <main class="p-6">
      <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow">
        <h2 class="text-2xl font-bold text-blue-600 mb-6"><?= isset($anggota) ? 'Form Edit Anggota' : 'Form Tambah Anggota' ?></h2>
        
        <?php if ($validation->getErrors()): ?>
          <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Terjadi Kesalahan!</strong>
            <span class="block sm:inline"><?= $validation->listErrors() ?></span>
          </div>
        <?php endif; ?>

        <form action="<?= isset($anggota) ? base_url('anggota/update/' . $anggota['id']) : base_url('anggota/create') ?>" method="post" id="anggotaForm">
          <input type="hidden" name="cropped_image" id="cropped_image">
          <?= csrf_field() ?>
          <div class="space-y-4">
            <div>
              <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
              <input type="text" name="nama" id="nama" value="<?= old('nama', $anggota['nama'] ?? '') ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
            </div>
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
              <input type="email" name="email" id="email" value="<?= old('email', $anggota['email'] ?? '') ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
            </div>
            <?php if (!isset($anggota)): ?>
            <div>
              <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
              <input type="password" name="password" id="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
            </div>
                        <?php endif; ?>
            <div>
              <label for="role_id" class="block text-sm font-medium text-gray-700">Peran</label>
              <select name="role_id" id="role_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                <option value="">Pilih Peran</option>
                <?php foreach($roles as $role): ?>
                  <option value="<?= $role['id'] ?>" <?= (old('role_id', $anggota['role_id'] ?? '') == $role['id']) ? 'selected' : '' ?>><?= esc($role['role']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Gambar Profil</label>
              <div class="mt-1 flex items-center space-x-4">
                <img id="image_preview" src="<?= base_url('uploads/' . ($anggota['image'] ?? 'default.jpg')) ?>" alt="Preview" class="w-20 h-20 rounded-full object-cover">
                <label for="image_input" class="cursor-pointer bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                  <span>Ubah Gambar</span>
                  <input id="image_input" name="image" type="file" class="sr-only" accept="image/*">
                </label>
              </div>
            </div>
          </div>
          <div class="flex justify-end mt-6 space-x-2">
            <a href="<?= base_url('anggota') ?>" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Simpan</button>
          </div>
        </form>
      </div>
    </main>
  </div>

  <!-- Modal Crop -->
  <div id="cropModal" class="fixed inset-0 bg-black bg-opacity-75 hidden items-center justify-center z-50">
    <div class="bg-white p-4 rounded-lg shadow-lg max-w-md w-full">
      <h3 class="text-lg font-medium mb-4">Potong Gambar</h3>
      <div>
        <img id="image_to_crop" src="" alt="Crop Preview">
      </div>
      <div class="flex justify-end space-x-2 mt-4">
        <button type="button" id="cancelCrop" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">Batal</button>
        <button type="button" id="cropButton" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Potong & Simpan</button>
      </div>
    </div>
  </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const imageInput = document.getElementById('image_input');
      const imagePreview = document.getElementById('image_preview');
      const cropModal = document.getElementById('cropModal');
      const imageToCrop = document.getElementById('image_to_crop');
      const cropButton = document.getElementById('cropButton');
      const cancelCrop = document.getElementById('cancelCrop');
      const croppedImageInput = document.getElementById('cropped_image');
      let cropper;

      imageInput.addEventListener('change', function (e) {
        const files = e.target.files;
        if (files && files.length > 0) {
          const reader = new FileReader();
          reader.onload = function (event) {
            imageToCrop.src = event.target.result;
            cropModal.classList.remove('hidden');
            cropModal.classList.add('flex');
            
            if (cropper) {
              cropper.destroy();
            }

            cropper = new Cropper(imageToCrop, {
              aspectRatio: 1,
              viewMode: 1,
              autoCropArea: 0.9,
            });
          };
          reader.readAsDataURL(files[0]);
        }
      });

      cropButton.addEventListener('click', function () {
        const canvas = cropper.getCroppedCanvas({
          width: 400,
          height: 400,
        });
        const croppedDataUrl = canvas.toDataURL('image/jpeg');
        imagePreview.src = croppedDataUrl;
        croppedImageInput.value = croppedDataUrl;
        
        cropModal.classList.add('hidden');
        cropper.destroy();
      });

      cancelCrop.addEventListener('click', function() {
        cropModal.classList.add('hidden');
        cropper.destroy();
        imageInput.value = ''; // Reset file input
      });

      // Sidebar Toggle
      const sidebar = document.getElementById('sidebar');
      const mainContent = document.getElementById('main-content');
      const sidebarToggle = document.getElementById('sidebar-toggle');

      sidebarToggle.addEventListener('click', () => {
        document.documentElement.classList.toggle('sidebar-is-collapsed');

        // Simpan status sidebar di localStorage
        const isCollapsed = document.documentElement.classList.contains('sidebar-is-collapsed');
        localStorage.setItem('sidebarCollapsed', isCollapsed);
      });
    });
  </script>
</body>
</html>