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
    /* Styling untuk placeholder pada input date */
    input[type="date"]::-webkit-calendar-picker-indicator {
      background: transparent;
      bottom: 0; color: transparent; cursor: pointer; height: auto; left: 0; position: absolute; right: 0; top: 0; width: auto;
    }
    input[type="date"]:not(:valid) {
      color: #9ca3af; /* text-gray-400 */
    }
    input[type="date"] {
      color: #374151; /* text-gray-700 */
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
        <div class="card-item relative bg-gradient-to-r from-blue-500 to-purple-600 text-white p-6 rounded-lg shadow-lg mb-8 overflow-hidden">
          <div class="absolute -right-10 -bottom-10">
              <i class="fas fa-file-invoice text-white/10 text-9xl transform -rotate-12"></i>
          </div>
          <div class="relative z-10">
              <h2 class="text-3xl font-bold">Pusat Laporan</h2>
              <p class="mt-1 text-blue-100">Pilih jenis laporan, filter berdasarkan tanggal, dan cetak dalam format PDF atau Excel.</p>
          </div>
        </div>

        <!-- Filter Laporan -->
        <div class="card-item bg-white p-6 rounded-lg shadow-sm mb-8">
          <h3 class="text-xl font-bold text-gray-800 border-b pb-3 mb-4">Filter Laporan</h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
              <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                  <i class="fas fa-calendar-alt text-gray-400"></i>
                </div>
                <input type="date" id="start_date" name="start_date" required class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
              </div>
            </div>
            <div>
              <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                  <i class="fas fa-calendar-alt text-gray-400"></i>
                </div>
                <input type="date" id="end_date" name="end_date" required class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
              </div>
            </div>
            <p class="text-xs text-gray-500 md:col-span-1">Filter tanggal akan diterapkan pada laporan transaksional seperti Laporan Peminjaman.</p>
          </div>
        </div>

        <!-- Pilihan Laporan -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <!-- Laporan Data Buku -->
          <div class="card-item bg-white rounded-xl shadow-md p-6 flex flex-col text-center hover:shadow-xl hover:scale-[1.02] transition-all">
              <div class="mx-auto bg-green-100 p-4 rounded-full mb-4"><i class="fas fa-book text-3xl text-green-600"></i></div>
              <h3 class="font-semibold text-lg text-gray-800">Laporan Data Buku</h3>
              <p class="text-sm text-gray-500 mt-1 mb-4 flex-grow">Cetak laporan semua data buku yang terdaftar di perpustakaan.</p>
              <div class="mt-auto grid grid-cols-2 gap-2">
                <a href="<?= base_url('laporan/buku-excel') ?>" onclick="applyDateFilter(event)" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 flex items-center justify-center gap-2 transition-colors">
                  <i class="fas fa-file-excel"></i> Excel
                </a>
                <a href="<?= base_url('laporan/buku-pdf') ?>" onclick="applyDateFilter(event)" target="_blank" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 flex items-center justify-center gap-2 transition-colors">
                  <i class="fas fa-print"></i> Print
                </a>
              </div>
          </div>
          <!-- Laporan Peminjaman -->
          <div class="card-item bg-white rounded-xl shadow-md p-6 flex flex-col text-center hover:shadow-xl hover:scale-[1.02] transition-all">
              <div class="mx-auto bg-blue-100 p-4 rounded-full mb-4"><i class="fas fa-exchange-alt text-3xl text-blue-600"></i></div>
              <h3 class="font-semibold text-lg text-gray-800">Laporan Peminjaman</h3>
              <p class="text-sm text-gray-500 mt-1 mb-4 flex-grow">Cetak laporan transaksi peminjaman buku berdasarkan rentang tanggal.</p>
              <div class="mt-auto grid grid-cols-2 gap-2 w-full">
                <a href="<?= base_url('laporan/peminjaman-excel') ?>" onclick="applyDateFilter(event)" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 flex items-center justify-center gap-2 transition-colors">
                  <i class="fas fa-file-excel"></i> Excel
                </a>
                <a href="<?= base_url('laporan/peminjaman-pdf') ?>" onclick="applyDateFilter(event)" target="_blank" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 flex items-center justify-center gap-2 transition-colors">
                  <i class="fas fa-print"></i> Print
                </a>
              </div>
          </div>
          <!-- Laporan Anggota -->
          <div class="card-item bg-white rounded-xl shadow-md p-6 flex flex-col text-center hover:shadow-xl hover:scale-[1.02] transition-all">
              <div class="mx-auto bg-purple-100 p-4 rounded-full mb-4"><i class="fas fa-users text-3xl text-purple-600"></i></div>
              <h3 class="font-semibold text-lg text-gray-800">Laporan Data Anggota</h3>
              <p class="text-sm text-gray-500 mt-1 mb-4 flex-grow">Cetak laporan semua data anggota yang terdaftar di perpustakaan.</p>
              <div class="mt-auto grid grid-cols-2 gap-2 w-full">
                <a href="<?= base_url('laporan/anggota-excel') ?>" onclick="applyDateFilter(event)" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 flex items-center justify-center gap-2 transition-colors">
                  <i class="fas fa-file-excel"></i> Excel
                </a>
                <a href="<?= base_url('laporan/anggota-pdf') ?>" onclick="applyDateFilter(event)" target="_blank" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 flex items-center justify-center gap-2 transition-colors">
                  <i class="fas fa-print"></i> Print
                </a>
              </div>
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

    function applyDateFilter(event) {
      event.preventDefault(); // Mencegah link langsung terbuka

      const startDate = document.getElementById('start_date').value;
      const endDate = document.getElementById('end_date').value;
      const originalUrl = event.currentTarget.href;

      const url = new URL(originalUrl);

      if (startDate) url.searchParams.set('start_date', startDate);
      if (endDate) url.searchParams.set('end_date', endDate);

      // Buka di tab baru jika targetnya _blank, jika tidak, buka di tab yang sama
      if (event.currentTarget.target === '_blank') {
        window.open(url.toString(), '_blank');
      } else {
        window.location.href = url.toString();
      }
    }
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