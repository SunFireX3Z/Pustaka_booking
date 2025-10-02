<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
    .sidebar-is-collapsed #sidebar { width: 4rem; }
    .sidebar-is-collapsed #sidebar .sidebar-text { display: none; }
    .sidebar-is-collapsed #sidebar .sidebar-logo-text { display: none; }
    .sidebar-is-collapsed #sidebar .sidebar-menu-title { text-align: center; }
    .sidebar-is-collapsed #sidebar .sidebar-menu-item i { margin-right: 0; }
    .sidebar-is-collapsed #main-content { margin-left: 4rem; }
    .sidebar-is-collapsed #sidebar .sidebar-menu-item { justify-content: center; }
  </style>
  <script>
    // Skrip ini dijalankan di <head> untuk mencegah "flash" saat sidebar tertutup.
    // Ini menerapkan kelas ke <html> sebelum body dirender.
    if (localStorage.getItem('sidebarCollapsed') === 'true') {
      document.documentElement.classList.add('sidebar-is-collapsed');
    }
  </script>
</head>
<body class="bg-gray-100 flex">

  <?php
    $current_page = 'dashboard';
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
        <h1 class="text-xl font-semibold">Dashboard</h1>
      </div>
      <div class="flex items-center space-x-3">
        <?php 
          $userImage = session()->get('image') ?? 'default.jpg';
          $userName = session()->get('nama') ?? 'User';
          $userRole = session()->get('role') ?? 'Peran';
        ?>
        <div class="text-right">
          <span class="font-medium block text-sm"><?= esc($userName) ?></span>
          <span class="text-xs text-gray-500 block"><?= esc($userRole) ?></span>
        </div>
        <img src="<?= base_url('uploads/' . $userImage) ?>" alt="User" class="w-8 h-8 rounded-full">
      </div>
    </header>

    <!-- Content -->
    <main class="p-6">
      <!-- Welcome Banner -->
      <div class="card-item relative bg-gradient-to-r from-blue-500 to-purple-600 text-white p-6 rounded-lg shadow-lg mb-8">
        <!-- Graphic from image_assets -->
        <img src="<?= base_url('image_assets/book.png') ?>" alt="Banner Graphic" class="absolute -bottom-11 right-4 w-[220px] h-auto hidden md:block pointer-events-none">
        
        <div class="relative z-10">
          <h2 class="text-2xl font-bold">Selamat Datang Kembali, <?= esc(session()->get('nama') ?? 'User') ?>!</h2>
          <p class="mt-1">Senang melihat Anda lagi. Mari kita kelola perpustakaan hari ini.</p>
        </div>
      </div>

      <!-- Cards Statistik -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8" id="stat-cards">
        <!-- Card Anggota -->
        <div class="card-item bg-white p-5 rounded-lg shadow-md flex items-center space-x-4 border-l-4 border-transparent hover:border-blue-500 transition-all duration-300">
          <div class="bg-gradient-to-br from-blue-400 to-blue-600 p-4 rounded-full shadow-lg">
            <i class="fas fa-users fa-2x text-white"></i>
          </div>
          <div>
            <p class="text-gray-500 text-sm font-medium">Jumlah Anggota</p>
            <p class="text-2xl font-bold text-gray-800"><?= $jumlahAnggota ?></p>
          </div>
        </div>
        <!-- Card Stok Buku -->
        <div class="card-item bg-white p-5 rounded-lg shadow-md flex items-center space-x-4 border-l-4 border-transparent hover:border-yellow-500 transition-all duration-300">
          <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 p-4 rounded-full shadow-lg">
            <i class="fas fa-book fa-2x text-white"></i>
          </div>
          <div>
            <p class="text-gray-500 text-sm font-medium">Stok Buku Tersedia</p>
            <p class="text-2xl font-bold text-gray-800"><?= $stokBuku ?></p>
          </div>
        </div>
        <!-- Card Dipinjam -->
        <div class="card-item bg-white p-5 rounded-lg shadow-md flex items-center space-x-4 border-l-4 border-transparent hover:border-red-500 transition-all duration-300">
          <div class="bg-gradient-to-br from-red-400 to-red-600 p-4 rounded-full shadow-lg">
            <i class="fas fa-hand-holding-hand fa-2x text-white"></i>
          </div>
          <div>
            <p class="text-gray-500 text-sm font-medium">Buku Dipinjam</p>
            <p class="text-2xl font-bold text-gray-800"><?= $dipinjam ?></p>
          </div>
        </div>
        <!-- Card Dibooking -->
        <div class="card-item bg-white p-5 rounded-lg shadow-md flex items-center space-x-4 border-l-4 border-transparent hover:border-green-500 transition-all duration-300">
          <div class="bg-gradient-to-br from-green-400 to-green-600 p-4 rounded-full shadow-lg">
            <i class="fas fa-bookmark fa-2x text-white"></i>
          </div>
          <div>
            <p class="text-gray-500 text-sm font-medium">Buku Dibooking</p>
            <p class="text-2xl font-bold text-gray-800"><?= $dibooking ?></p>
          </div>
        </div>
      </div>

      <!-- Main Content Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 mb-8">
        <!-- Anggota Terbaru -->
        <div class="lg:col-span-3 flex flex-col">
          <div class="card-item flex justify-between items-center mb-4">
            <h4 class="text-xl font-semibold text-gray-700">Anggota Terbaru</h4>
            <a href="<?= base_url('anggota') ?>" class="text-sm text-blue-600 hover:underline font-medium">Lihat Semua <i class="fas fa-arrow-right ml-1"></i></a>
          </div>
          <div id="memberTableContainer" class="card-item overflow-x-auto bg-white rounded-lg shadow flex-grow">
            <table class="min-w-full text-sm text-left">
              <thead class="bg-slate-50 border-b border-gray-200 text-slate-600 uppercase text-xs">
                <tr>
                  <th class="py-3 px-4 font-semibold">Nama</th>
                  <th class="py-3 px-4 font-semibold">Email</th>
                  <th class="py-3 px-4 font-semibold">Tanggal Bergabung</th>
                </tr>
              </thead>
              <tbody class="text-gray-600">
                <?php foreach($users as $u): ?>
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                  <td class="py-3 px-4 font-medium"><?= esc($u['nama']) ?></td>
                  <td class="py-3 px-4"><?= esc($u['email']) ?></td>
                  <td class="py-3 px-4"><?= date('d M Y', strtotime($u['tanggal_input'])) ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
        <!-- Komposisi Buku -->
        <div class="lg:col-span-2 flex flex-col">
          <div class="card-item flex justify-between items-center mb-4">
            <h4 class="text-xl font-semibold text-gray-700">Komposisi Buku</h4>
          </div>
          <div id="bookChartContainer" class="card-item bg-white p-6 rounded-lg shadow flex-grow flex flex-col">
            <div class="relative flex-grow"><canvas id="bookChart"></canvas></div>
          </div>
        </div>
      </div>

      <!-- Second Row Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <!-- Tren Anggota Baru -->
        <div class="lg:col-span-3">
          <div class="card-item flex justify-between items-center mb-4">
            <h4 class="text-xl font-semibold text-gray-700">Tren Anggota Baru</h4>
          </div>
          <div class="card-item bg-white p-6 rounded-lg shadow"><canvas id="memberChart"></canvas></div>
        </div>
        <!-- Buku Baru Ditambahkan -->
        <div class="lg:col-span-2">
          <div class="card-item flex justify-between items-center mb-4">
            <h4 class="text-xl font-semibold text-gray-700">Buku Baru Ditambahkan</h4>
            <a href="<?= base_url('buku') ?>" class="text-sm text-blue-600 hover:underline font-medium">Lihat Semua <i class="fas fa-arrow-right ml-1"></i></a>
          </div>
          <?php if (!empty($buku)): ?>
            <div class="space-y-3">
              <?php foreach ($buku as $b): ?>
                <div class="card-item bg-white p-3 rounded-lg shadow-sm flex items-center space-x-4 hover:shadow-md transition-shadow">
                  <img src="<?= base_url('uploads/' . $b['image']) ?>" alt="Cover" class="w-12 h-16 object-contain rounded bg-gray-100">
                  <div class="flex-1">
                    <h5 class="font-semibold text-sm text-gray-800 truncate" title="<?= esc($b['judul_buku']) ?>"><?= esc($b['judul_buku']) ?></h5>
                    <p class="text-xs text-gray-500 truncate"><?= esc($b['pengarang']) ?></p>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="text-center py-10 bg-white rounded-lg shadow"><p class="text-gray-500">Belum ada data buku.</p></div>
          <?php endif; ?>
        </div>
      </div>
    </main>
  </div>
  <script>
    // Animasi staggered untuk kartu buku
    document.addEventListener('DOMContentLoaded', () => {
      // Variabel untuk menyimpan instance chart
      let bookChartInstance, memberChartInstance;

      const cards = document.querySelectorAll('.card-item');
      const memberTableContainer = document.getElementById('memberTableContainer');
      const bookChartContainer = document.getElementById('bookChartContainer');

      cards.forEach((card, index) => {
        setTimeout(() => {
          card.classList.add('is-visible');
        }, index * 100); // Delay 100ms untuk setiap kartu
      });

      // Menyamakan tinggi chart dengan tabel
      function matchHeights() {
        if (memberTableContainer && bookChartContainer) {
          bookChartContainer.style.height = `${memberTableContainer.offsetHeight}px`;
        }
      }
      matchHeights();
      window.addEventListener('resize', matchHeights); // Juga sesuaikan saat ukuran window berubah

    // Chart.js - Komposisi Buku
    const bookCtx = document.getElementById('bookChart').getContext('2d');
    bookChartInstance = new Chart(bookCtx, {
      type: 'bar', // Mengubah tipe chart menjadi bar
      data: {
        labels: ['Tersedia', 'Dipinjam', 'Dibooking'],
        datasets: [{
          label: 'Jumlah Buku',
          data: [<?= $stokBuku ?>, <?= $dipinjam ?>, <?= $dibooking ?>],
          backgroundColor: [
            'rgba(59, 130, 246, 0.8)', // blue-500
            'rgba(239, 68, 68, 0.8)',  // red-500
            'rgba(34, 197, 94, 0.8)'   // green-500
          ],
          borderColor: [
            'rgba(59, 130, 246, 1)',
            'rgba(239, 68, 68, 1)',
            'rgba(34, 197, 94, 1)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        indexAxis: 'y', // Membuat bar chart menjadi horizontal
        maintainAspectRatio: false, // Penting agar chart mengisi kontainer
        responsive: true,
        scales: {
          x: {
            beginAtZero: true
          }
        },
        plugins: {
          legend: {
            display: false, // Legenda tidak terlalu diperlukan untuk bar chart ini
          },
          title: {
            display: false, // Judul utama sudah ada di atas chart
            text: 'Komposisi Status Buku'
          }
        }
      }
    });

    // Chart.js - Tren Anggota Baru
    const memberCtx = document.getElementById('memberChart').getContext('2d');
    memberChartInstance = new Chart(memberCtx, {
      type: 'bar',
      data: {
        labels: <?= $memberTrendLabels ?>, // Data dari controller
        datasets: [{
          label: 'Anggota Baru',
          data: <?= $memberTrendCounts ?>, // Data dari controller
          backgroundColor: 'rgba(59, 130, 246, 0.6)',
          borderColor: 'rgba(59, 130, 246, 1)',
          borderWidth: 1,
          borderRadius: 5
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true
          }
        },
        plugins: {
          legend: {
            display: false
          }
        }
      }
    });

    // Sidebar Toggle
    const sidebarToggle = document.getElementById('sidebar-toggle');

    sidebarToggle.addEventListener('click', () => {
      document.documentElement.classList.toggle('sidebar-is-collapsed');

      // Tunda resize chart sampai setelah transisi sidebar selesai (300ms)
      // Ini memperbaiki bug di mana chart mengecil saat sidebar di-toggle.
      setTimeout(() => {
        bookChartInstance.resize();
        memberChartInstance.resize();
      }, 310); // Sedikit lebih lama dari durasi transisi (300ms)

      // Simpan status sidebar di localStorage
      const isCollapsed = document.documentElement.classList.contains('sidebar-is-collapsed');
      localStorage.setItem('sidebarCollapsed', isCollapsed);
    });
    });

  </script>
</body>
</html>