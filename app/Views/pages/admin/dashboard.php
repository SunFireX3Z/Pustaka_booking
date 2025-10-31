<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- ApexCharts -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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

    /* Custom Scrollbar */
     ::-webkit-scrollbar {
      width: 6px;
      height: 6px;
     }
     ::-webkit-scrollbar-track {
      background: transparent;
     }
     ::-webkit-scrollbar-thumb {
      background: #cbd5e1; /* slate-300 */
      border-radius: 10px;
     }
     ::-webkit-scrollbar-thumb:hover {
      background: #94a3b8; /* slate-400 */
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
    $current_page = 'dashboard'; // Set halaman aktif
    echo view('pages/admin/template/sidebar', ['current_page' => $current_page]);
  ?>

  <!-- Main content -->
  <div id="main-content" class="flex-1 flex flex-col ml-64 transition-all duration-300">

    <!-- Header bar -->
    <header class="bg-white shadow-sm p-4 flex justify-between items-center">
      <div class="flex items-center">
        <button id="sidebar-toggle" class="text-gray-500 hover:text-gray-700 focus:outline-none mr-4"><i class="fas fa-bars text-lg"></i></button>
        <h1 class="text-xl font-semibold">Dashboard</h1>
      </div>
      <?php echo view('pages/admin/template/header_user_profile'); ?>
    </header>

    <!-- Content -->
    <main class="p-6">
      <div class="w-full max-w-7xl mx-auto">
        <!-- Welcome Banner -->
      <div class="card-item relative bg-gradient-to-r from-green-500 to-purple-600 text-white p-6 rounded-lg shadow-lg mb-8">
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
        <div class="card-item bg-white p-5 rounded-lg shadow-md flex items-center space-x-4 border-l-4 border-transparent hover:border-green-500 transition-all duration-300">
          <div class="bg-gradient-to-br from-green-400 to-green-600 p-4 rounded-full shadow-lg">
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
            <a href="<?= base_url('anggota') ?>" class="text-sm text-green-600 hover:underline font-medium">Lihat Semua <i class="fas fa-arrow-right ml-1"></i></a>
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
          <div id="bookChartContainer" class="card-item bg-white p-4 rounded-lg shadow flex-grow flex items-center justify-center">
            <div id="bookChart" class="w-full"></div>
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
          <div class="card-item bg-white p-6 rounded-lg shadow">
            <div id="memberChart"></div>
          </div>
        </div>
        <!-- Buku Baru Ditambahkan -->
        <div class="lg:col-span-2">
          <div class="card-item flex justify-between items-center mb-4">
            <h4 class="text-xl font-semibold text-gray-700">Buku Baru Ditambahkan</h4>
            <a href="<?= base_url('buku') ?>" class="text-sm text-green-600 hover:underline font-medium">Lihat Semua <i class="fas fa-arrow-right ml-1"></i></a>
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
      const cards = document.querySelectorAll('.card-item');
      let bookChart, memberChart;

      // Staggered animation for cards
      const memberTableContainer = document.getElementById('memberTableContainer');
      const bookChartContainer = document.getElementById('bookChartContainer');

      cards.forEach((card, index) => {
        setTimeout(() => {
          card.classList.add('is-visible');
        }, index * 100); // Delay 100ms untuk setiap kartu
      });

      // Match chart container height with member table height
      function matchHeights() {
        // Hanya jalankan di layar besar (lg) di mana layoutnya berdampingan
        if (window.innerWidth >= 1024 && memberTableContainer && bookChartContainer) {
          const tableHeight = memberTableContainer.offsetHeight;
          const chartHeight = bookChartContainer.offsetHeight;

          if (tableHeight > chartHeight) {
            bookChartContainer.style.height = `${tableHeight}px`;
          }
        }
      }
      matchHeights();
      window.addEventListener('resize', matchHeights);

      // ApexCharts - Komposisi Buku
      const bookChartOptions = {
        series: [<?= $stokBuku ?>, <?= $dipinjam ?>, <?= $dibooking ?>],
        chart: {
          type: 'donut',
          height: '100%', // Biarkan chart mengisi kontainer
          toolbar: { show: false },
        },
        labels: ['Tersedia', 'Dipinjam', 'Dibooking'],
        colors: ['#10B981', '#EF4444', '#f59e0b'], // emerald-500, red-500, amber-500
        plotOptions: {
          pie: {
            donut: {
              size: '65%',
              labels: {
                show: true,
                total: {
                  show: true,
                  label: 'Total Buku',
                  fontSize: '16px',
                  fontWeight: 600,
                  color: '#374151'
                }
              }
            }
          }
        },
        dataLabels: {
          enabled: true,
          formatter: function (val, opts) {
            return opts.w.config.series[opts.seriesIndex]
          },
        },
        legend: {
          position: 'bottom',
          fontSize: '14px',
        },
      };
      bookChart = new ApexCharts(document.querySelector("#bookChart"), bookChartOptions);
      bookChart.render();

      // ApexCharts - Tren Anggota Baru
      const memberChartOptions = {
        series: [{
          name: 'Anggota Baru',
          data: <?= $memberTrendCounts ?>
        }],
        chart: {
          type: 'area',
          height: 300,
          zoom: { enabled: false },
          toolbar: { show: false }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2 },
        fill: {
          type: 'gradient',
          gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.7,
            opacityTo: 0.2,
            stops: [0, 90, 100]
          }
        },
        xaxis: {
          categories: <?= $memberTrendLabels ?>,
          labels: { style: { colors: '#6B7280' } }
        },
        yaxis: {
          labels: {
            style: { colors: '#6B7280' },
            formatter: (val) => { return val.toFixed(0) }
          }
        },
        grid: {
          borderColor: '#E5E7EB',
          strokeDashArray: 4
        },
        tooltip: {
          x: { format: 'MMM yyyy' }
        }
      };
      memberChart = new ApexCharts(document.querySelector("#memberChart"), memberChartOptions);
      memberChart.render();

    // Sidebar Toggle
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