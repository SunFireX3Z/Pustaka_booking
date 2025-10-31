<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Laporan Perpustakaan</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- ApexCharts -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .sidebar-is-collapsed #sidebar { width: 4rem; }
    .sidebar-is-collapsed #sidebar .sidebar-text,
    .sidebar-is-collapsed #sidebar .sidebar-logo-text { display: none; }
    .sidebar-is-collapsed #sidebar .sidebar-menu-title { text-align: center; }
    .sidebar-is-collapsed #sidebar .sidebar-menu-item i { margin-right: 0; }
    .sidebar-is-collapsed #main-content { margin-left: 4rem; }
    .sidebar-is-collapsed #sidebar .sidebar-menu-item { justify-content: center; }
     ::-webkit-scrollbar { width: 6px; height: 6px; }
     ::-webkit-scrollbar-track { background: transparent; }
     ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
     ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
  </style>
  <style>
    .card-item {
      opacity: 0;
      transform: translateY(20px);
      transition: opacity 0.4s ease-out, transform 0.4s ease-out;
    }
    .card-item.is-visible {
      opacity: 1;
      transform: translateY(0);
    }
  </style>
  <script>
    if (localStorage.getItem('sidebarCollapsed') === 'true') {
      document.documentElement.classList.add('sidebar-is-collapsed');
    }
  </script>
</head>
<body class="bg-gray-100 flex">

  <?php
    $current_page = 'laporan'; // Set halaman aktif
    echo view('pages/admin/template/sidebar', ['current_page' => $current_page]);
  ?>

  <!-- Main content -->
  <div id="main-content" class="flex-1 flex flex-col ml-64 transition-all duration-300">
    <header class="bg-white shadow-sm p-4 flex justify-between items-center sticky top-0 z-30">
      <div class="flex items-center">
        <button id="sidebar-toggle" class="text-gray-500 hover:text-gray-700 focus:outline-none mr-4"><i class="fas fa-bars text-lg"></i></button>
        <h1 class="text-xl font-semibold">Laporan Perpustakaan</h1>
      </div>
      <?= view('pages/admin/template/header_user_profile'); ?>
    </header>

    <main class="p-6">
      <div class="w-full max-w-7xl mx-auto" id="laporanContent">
        <div class="card-item bg-white p-4 rounded-lg shadow-sm mb-6">
        <div>
          <h2 class="text-2xl font-bold text-green-600">Cetak Laporan</h2>
          <p class="text-sm text-gray-500 mt-1">Pilih jenis laporan yang ingin Anda cetak.</p>
        </div>
      </div>

        <!-- Placeholder untuk pilihan laporan -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"> <!-- Tambahkan card-item ke setiap kartu laporan -->
        <!-- Laporan Peminjaman -->
        <div class="card-item bg-white rounded-lg shadow-sm p-6 flex flex-col items-center text-center hover:shadow-lg transition-shadow">
            <i class="fas fa-file-invoice text-4xl text-blue-500 mb-4"></i>
            <h3 class="font-semibold text-lg text-gray-800">Laporan Peminjaman</h3>
            <p class="text-sm text-gray-500 mt-1 mb-4">Cetak laporan data peminjaman buku.</p>
            <button class="mt-auto bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 w-full">Cetak</button>
        </div>
        <!-- Laporan Anggota -->
        <div class="card-item bg-white rounded-lg shadow-sm p-6 flex flex-col items-center text-center hover:shadow-lg transition-shadow">
            <i class="fas fa-file-alt text-4xl text-green-500 mb-4"></i>
            <h3 class="font-semibold text-lg text-gray-800">Laporan Data Anggota</h3>
            <p class="text-sm text-gray-500 mt-1 mb-4">Cetak laporan semua data anggota.</p>
            <button class="mt-auto bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 w-full">Cetak</button>
        </div>
        <!-- Laporan Stok Buku -->
        <div class="card-item bg-white rounded-lg shadow-sm p-6 flex flex-col items-center text-center hover:shadow-lg transition-shadow">
            <i class="fas fa-file-export text-4xl text-purple-500 mb-4"></i>
            <h3 class="font-semibold text-lg text-gray-800">Laporan Stok Buku</h3>
            <p class="text-sm text-gray-500 mt-1 mb-4">Cetak laporan ketersediaan stok buku.</p>
            <button class="mt-auto bg-purple-500 text-white px-4 py-2 rounded-md hover:bg-purple-600 w-full">Cetak</button>
        </div>
      </div>
      </div>
    </main>
  </div>

  <script>
    document.getElementById('sidebar-toggle').addEventListener('click', () => {
      document.documentElement.classList.toggle('sidebar-is-collapsed');
      localStorage.setItem('sidebarCollapsed', document.documentElement.classList.contains('sidebar-is-collapsed'));
    });
  </script>
  <script>
    // Animasi staggered untuk kartu
    document.addEventListener('DOMContentLoaded', () => {
      const cards = document.querySelectorAll('#laporanContent .card-item');
      cards.forEach((card, index) => {
        setTimeout(() => {
          card.classList.add('is-visible');
        }, index * 100); // Delay 100ms untuk setiap kartu
      });
    });
  </script>
</body>
</html>