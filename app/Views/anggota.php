<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar Anggota</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .sidebar-is-collapsed #sidebar { width: 4rem; }
    .sidebar-is-collapsed #sidebar .sidebar-text { display: none; }
    .sidebar-is-collapsed #sidebar .sidebar-logo-text { display: none; }
    .sidebar-is-collapsed #sidebar .sidebar-menu-title { text-align: center; }
    .sidebar-is-collapsed #sidebar .sidebar-menu-item i { margin-right: 0; }
    .sidebar-is-collapsed #main-content { margin-left: 4rem; }
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
        <h1 class="text-xl font-semibold">Daftar Anggota</h1>
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
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-blue-600">Data Anggota Perpustakaan</h2>
        <a href="<?= base_url('anggota/new') ?>" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center">
          <i class="fas fa-plus mr-2"></i> Tambah Anggota
        </a>
      </div>

      <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full text-sm text-left">
          <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 uppercase text-xs">
            <tr>
              <th class="py-3 px-6 font-semibold">#</th>
              <th class="py-3 px-6 font-semibold">Gambar</th>
              <th class="py-3 px-6 font-semibold">Nama</th>
              <th class="py-3 px-6 font-semibold">Email</th>
              <th class="py-3 px-6 font-semibold">Peran</th>
              <th class="py-3 px-6 font-semibold">Tanggal Bergabung</th>
              <th class="py-3 px-6 font-semibold text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="text-gray-600">
            <?php $i=1; foreach($anggota as $u): ?>
            <tr class="border-b border-gray-200 hover:bg-gray-50">
              <td class="py-4 px-6"><?= $i++ ?></td>
              <td class="py-4 px-6">
                <img src="<?= base_url('uploads/' . esc($u['image'])) ?>" alt="Foto <?= esc($u['nama']) ?>" class="w-10 h-10 rounded-full object-cover">
              </td>
              <td class="py-4 px-6 font-medium"><?= esc($u['nama']) ?></td>
              <td class="py-4 px-6"><?= esc($u['email']) ?></td>
              <td class="py-4 px-6"><?= esc($u['role']) ?></td>
              <td class="py-4 px-6"><?= date('d F Y', strtotime($u['tanggal_input'])) ?></td>
              <td class="py-4 px-6 text-center">
                <a href="<?= base_url('anggota/edit/' . $u['id']) ?>" class="text-blue-500 hover:text-blue-700 mr-3"><i class="fas fa-edit"></i></a>
                <a href="<?= base_url('anggota/delete/' . $u['id']) ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Apakah Anda yakin ingin menghapus anggota ini?')"><i class="fas fa-trash"></i></a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    // Tampilkan notifikasi toast dengan SweetAlert2
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
    });

    <?php if (session()->getFlashdata('success')): ?>
      Toast.fire({
        icon: 'success',
        title: '<?= session()->getFlashdata('success') ?>'
      })
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
      Toast.fire({
        icon: 'error',
        title: '<?= session()->getFlashdata('error') ?>'
      })
    <?php endif; ?>

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
  </script>
</body>
</html>