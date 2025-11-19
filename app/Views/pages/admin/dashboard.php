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
    .modal-enter { opacity: 0; transform: translateY(-20px); }
    .modal-enter-active { opacity: 1; transform: translateY(0); transition: all 0.3s ease-out; }
    .modal-leave { opacity: 1; transform: translateY(0); }
    .modal-leave-active { opacity: 0; transform: translateY(-20px); transition: all 0.2s ease-in; }
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
        <div class="lg:col-span-3 flex flex-col">
          <div class="card-item flex justify-between items-center mb-4">
            <h4 class="text-xl font-semibold text-gray-700">Tren Anggota Baru</h4>
          </div>
          <div class="card-item bg-white p-6 rounded-lg shadow flex-grow flex flex-col">
            <div id="memberChart"></div>
          </div>
        </div>
        <!-- Buku Baru Ditambahkan -->
        <div class="lg:col-span-2 flex flex-col">
          <div class="card-item flex justify-between items-center mb-4">
            <h4 class="text-xl font-semibold text-gray-700">Buku Baru Ditambahkan</h4>
            <a href="<?= base_url('buku') ?>" class="text-sm text-green-600 hover:underline font-medium">Lihat Semua <i class="fas fa-arrow-right ml-1"></i></a>
          </div>
          <?php if (!empty($buku)): ?>
            <div class="space-y-3 flex-grow">
              <?php 
                $borderColors = ['border-purple-500', 'border-blue-500', 'border-green-500', 'border-yellow-500', 'border-red-500', 'border-indigo-500'];
                foreach ($buku as $key => $b): 
                $colorClass = $borderColors[$key % count($borderColors)];
              ?>
                <div class="card-item bg-white p-3 rounded-lg shadow-sm flex items-center space-x-4 hover:shadow-md transition-shadow border-l-4 <?= $colorClass ?>">
                  <img src="<?= base_url('uploads/' . esc($b['image'])) ?>" alt="Cover" class="w-12 h-16 object-contain rounded bg-gray-100">
                  <div class="flex-1 min-w-0">
                    <h5 class="font-semibold text-sm text-gray-800 truncate" title="<?= esc($b['judul_buku']) ?>"><?= esc($b['judul_buku']) ?></h5>
                    <p class="text-xs text-gray-500 truncate" title="Pengarang: <?= esc($b['pengarang']) ?>"><?= esc($b['pengarang']) ?></p>
                    <div class="text-xs text-gray-400 mt-1 flex items-center gap-2">
                      <span title="Penerbit: <?= esc($b['penerbit']) ?>"><i class="fas fa-building fa-fw"></i> <?= esc(substr($b['penerbit'], 0, 15)) . (strlen($b['penerbit']) > 15 ? '...' : '') ?></span>
                      <span title="Tahun Terbit: <?= esc($b['tahun_terbit']) ?>"><i class="fas fa-calendar-alt fa-fw"></i> <?= esc($b['tahun_terbit']) ?></span>
                      <button onclick="showDashboardBookDetailModal(this)" class="text-green-600 hover:underline" title="Lihat Detail"
                        data-judul="<?= esc($b['judul_buku']) ?>" data-penerbit="<?= esc($b['penerbit']) ?>" data-pengarang="<?= esc($b['pengarang']) ?>" 
                        data-tahun="<?= esc($b['tahun_terbit']) ?>" data-stok="<?= esc($b['stok']) ?>" data-kategori="<?= esc($b['kategori_nama'] ?? 'Tidak ada kategori') ?>" 
                        data-deskripsi="<?= esc($b['deskripsi'] ?? '-') ?>" data-image="<?= base_url('uploads/' . esc($b['image'])) ?>" 
                        data-pdf="<?= !empty($b['file_pdf']) ? base_url('uploads/pdf/' . esc($b['file_pdf'])) : '' ?>" data-isbn="<?= esc($b['isbn']) ?>" data-eisbn="<?= esc($b['eisbn'] ?? '-') ?>">
                        <i class="fas fa-eye fa-fw"></i>
                      </button>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="card-item text-center py-10 bg-white rounded-lg shadow flex-grow flex items-center justify-center"><p class="text-gray-500">Belum ada data buku.</p></div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Third Row Grid: Berita & Booking -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        <!-- Berita Terbaru -->
        <div class="flex flex-col">
          <div class="card-item flex justify-between items-center mb-4">
            <h4 class="text-xl font-semibold text-gray-700">Berita Terbaru</h4>
            <a href="<?= base_url('berita') ?>" class="text-sm text-green-600 hover:underline font-medium">Lihat Semua <i class="fas fa-arrow-right ml-1"></i></a>
          </div>
          <div class="card-item overflow-x-auto bg-white rounded-lg shadow flex-grow">
            <table class="min-w-full text-sm text-left">
              <thead class="bg-slate-50 border-b border-gray-200 text-slate-600 uppercase text-xs">
                <tr>
                  <th class="py-3 px-4 font-semibold">Thumbnail</th>
                  <th class="py-3 px-4 font-semibold">Judul Berita</th>
                  <th class="py-3 px-4 font-semibold">Tgl. Publikasi</th>
                </tr>
              </thead>
              <tbody class="text-gray-600">
                <?php if (!empty($berita_terbaru)): ?>
                  <?php foreach($berita_terbaru as $berita): ?>
                  <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="py-2 px-4">
                      <img src="<?= base_url('uploads/berita/' . esc($berita['thumbnail'])) ?>" alt="Thumbnail" class="w-16 h-10 object-cover rounded">
                    </td>
                    <td class="py-3 px-4 font-medium truncate" title="<?= esc($berita['judul']) ?>"><?= esc($berita['judul']) ?></td>
                    <td class="py-3 px-4 whitespace-nowrap"><?= date('d M Y', strtotime($berita['tanggal_publikasi'])) ?></td>
                  </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="3" class="text-center py-10 text-gray-500">Belum ada berita terbaru.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
        <!-- Booking Terbaru -->
        <div class="flex flex-col">
          <div class="card-item flex justify-between items-center mb-4">
            <h4 class="text-xl font-semibold text-gray-700">Booking Terbaru</h4>
            <a href="<?= base_url('booking') ?>" class="text-sm text-green-600 hover:underline font-medium">Lihat Semua <i class="fas fa-arrow-right ml-1"></i></a>
          </div>
          <div class="card-item overflow-x-auto bg-white rounded-lg shadow flex-grow">
            <table class="min-w-full text-sm text-left">
              <thead class="bg-slate-50 border-b border-gray-200 text-slate-600 uppercase text-xs">
                <tr>
                  <th class="py-3 px-4 font-semibold">Nama Anggota</th>
                  <th class="py-3 px-4 font-semibold">Judul Buku</th>
                  <th class="py-3 px-4 font-semibold text-center">Status</th>
                  <th class="py-3 px-4 font-semibold">Tgl. Booking</th>
                </tr>
              </thead>
              <tbody class="text-gray-600">
                <?php if (!empty($bookings_terbaru)): ?>
                  <?php foreach($bookings_terbaru as $booking): ?>
                  <tr class="border-b border-gray-200 hover:bg-gray-50 align-middle">
                    <td class="py-3 px-4 font-medium truncate" title="<?= esc($booking['nama_anggota']) ?>"><?= esc($booking['nama_anggota']) ?></td>
                    <td class="py-3 px-4">
                      <?php if (strpos($booking['judul_buku'], ',') !== false): ?>
                        <button onclick="showBookListModal('Daftar Buku Booking', '<?= esc($booking['judul_buku'], 'js') ?>')" class="text-sm text-green-600 hover:underline">Lihat Daftar Buku</button>
                      <?php else: ?>
                        <span class="truncate" title="<?= esc($booking['judul_buku']) ?>"><?= esc($booking['judul_buku']) ?></span>
                      <?php endif; ?>
                    </td>
                    <td class="py-3 px-4 text-center">
                      <?php $statusClass = $booking['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($booking['status'] == 'disetujui' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>
                      <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $statusClass ?>"><?= esc(ucfirst($booking['status'])) ?></span>
                    </td>
                    <td class="py-3 px-4 whitespace-nowrap"><?= date('d M Y', strtotime($booking['tgl_booking'])) ?></td>
                  </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="4" class="text-center py-10 text-gray-500">Belum ada data booking.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Fourth Row Grid: Peminjaman Terbaru -->
      <div class="grid grid-cols-1 gap-8 mt-8">
        <!-- Peminjaman Terbaru -->
        <div class="flex flex-col">
          <div class="card-item flex justify-between items-center mb-4">
            <h4 class="text-xl font-semibold text-gray-700">Peminjaman Terbaru</h4>
            <a href="<?= base_url('peminjaman') ?>" class="text-sm text-green-600 hover:underline font-medium">Lihat Semua <i class="fas fa-arrow-right ml-1"></i></a>
          </div>
          <div class="card-item overflow-x-auto bg-white rounded-lg shadow flex-grow">
            <table class="min-w-full text-sm text-left">
              <thead class="bg-slate-50 border-b border-gray-200 text-slate-600 uppercase text-xs">
                <tr>
                  <th class="py-3 px-4 font-semibold">Nama Peminjam</th>
                  <th class="py-3 px-4 font-semibold">Judul Buku</th>
                  <th class="py-3 px-4 font-semibold">Tgl. Pinjam</th>
                </tr>
              </thead>
              <tbody class="text-gray-600">
                <?php if (!empty($peminjaman_terbaru)): ?>
                  <?php foreach($peminjaman_terbaru as $pinjam): ?>
                  <tr class="border-b border-gray-200 hover:bg-gray-50 align-middle">
                    <td class="py-3 px-4 font-medium truncate" title="<?= esc($pinjam['nama_peminjam']) ?>"><?= esc($pinjam['nama_peminjam']) ?></td>
                    <td class="py-3 px-4">
                      <?php if (strpos($pinjam['judul_buku'], ',') !== false): ?>
                        <button onclick="showBookListModal('Daftar Buku Dipinjam', '<?= esc($pinjam['judul_buku'], 'js') ?>')" class="text-sm text-green-600 hover:underline">Lihat Daftar Buku</button>
                      <?php else: ?>
                        <span title="<?= esc($pinjam['judul_buku']) ?>"><?= esc($pinjam['judul_buku']) ?></span>
                      <?php endif; ?>
                    </td>
                    <td class="py-3 px-4 whitespace-nowrap"><?= date('d M Y', strtotime($pinjam['tgl_pinjam'])) ?></td>
                  </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="3" class="text-center py-10 text-gray-500">Belum ada data peminjaman.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Fifth Row Grid: Anggota Aktif -->
      <div class="grid grid-cols-1 gap-8 mt-8">
        <div class="flex flex-col">
          <div class="card-item flex justify-between items-center mb-4">
            <h4 class="text-xl font-semibold text-gray-700">Anggota yang Sedang Aktif</h4>
          </div>
          <div class="card-item bg-white rounded-lg shadow p-4">
            <?php if (!empty($anggota_aktif)): ?>
              <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <?php foreach ($anggota_aktif as $aktif): ?>
                  <div class="flex flex-col items-center text-center p-2 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="relative">
                      <img src="<?= base_url('uploads/' . esc($aktif['image'])) ?>" alt="Foto" class="w-16 h-16 object-cover rounded-full shadow-md">
                      <span class="absolute bottom-0 right-0 block h-4 w-4 rounded-full bg-green-500 ring-2 ring-white" title="Online"></span>
                    </div>
                    <h5 class="font-semibold text-sm text-gray-800 truncate w-full mt-2" title="<?= esc($aktif['nama']) ?>"><?= esc($aktif['nama']) ?></h5>
                    <p class="text-xs text-gray-500"><?= $aktif['role_id'] == 1 ? 'Admin' : 'Member' ?></p>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <div class="text-center py-10">
                <i class="fas fa-wifi-slash text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">Tidak ada anggota yang sedang online.</p>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Modal Detail Buku (dari halaman buku) -->
      <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center z-50 p-4">
        <div id="modalContent" class="bg-white rounded-lg shadow-2xl max-w-4xl w-full transform transition-all relative mt-16 md:mt-0">
          <button onclick="closeDashboardBookDetailModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-800 w-10 h-10 rounded-full flex items-center justify-center hover:bg-gray-100 transition-colors z-20">
            <i class="fas fa-times fa-lg"></i>
          </button>
          <div class="flex flex-col md:flex-row">
            <div class="w-full md:w-1/3 bg-gray-100 rounded-t-lg md:rounded-l-lg md:rounded-t-none flex items-center justify-center p-6">
              <img id="modalImage" src="" alt="cover" class="max-h-96 object-contain shadow-2xl rounded-lg">
            </div>
            <div class="w-full md:w-2/3 p-8 md:p-10 flex flex-col">
              <div class="flex-grow">
                <div id="modalKategori" class="text-xs font-semibold text-green-800 mb-3 flex flex-wrap gap-1"></div>
                <h2 id="modalJudul" class="text-4xl font-bold text-gray-900 mb-2 leading-tight"></h2>
                <p id="modalPengarang" class="text-gray-500 text-lg mb-6"></p>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-6 mb-8 text-sm">
                  <div class="flex items-center gap-3">
                    <i class="fas fa-building fa-fw text-gray-400 text-xl"></i>
                    <div><p class="text-gray-500">Penerbit</p><p id="modalPenerbit" class="text-gray-800 font-semibold"></p></div>
                  </div>
                  <div class="flex items-center gap-3">
                    <i class="fas fa-calendar-alt fa-fw text-gray-400 text-xl"></i>
                    <div><p class="text-gray-500">Tahun</p><p id="modalTahun" class="text-gray-800 font-semibold"></p></div>
                  </div>
                  <div class="flex items-center gap-3">
                    <i class="fas fa-box-open fa-fw text-gray-400 text-xl"></i> 
                    <div><p class="text-gray-500">Stok</p><p id="modalStok" class="font-semibold"></p></div>
                  </div>
                  <div class="flex items-center gap-3">
                    <i class="fas fa-barcode fa-fw text-gray-400 text-xl"></i> 
                    <div><p class="text-gray-500">ISBN</p><p id="modalIsbn" class="font-semibold"></p></div>
                  </div>
                  <div class="flex items-center gap-3">
                    <i class="fas fa-barcode fa-fw text-gray-400 text-xl"></i> 
                    <div><p class="text-gray-500">EISBN</p><p id="modalEisbn" class="font-semibold"></p></div>
                  </div>
                </div>
                <div class="flex flex-col min-h-0">
                  <div class="border-l-4 border-green-200 pl-4 overflow-y-auto pr-2">
                    <h4 class="font-semibold text-gray-800 mb-1">Deskripsi</h4>
                    <p id="modalDeskripsi" class="text-gray-600 text-sm leading-relaxed italic"></p>
                  </div>
                </div>
              </div>
              <div class="p-6 bg-gray-50 rounded-b-lg flex justify-end space-x-3">
                <a id="readPdfButton" href="#" target="_blank" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 shadow-sm flex items-center gap-2" style="display: none;">
                  <i class="fas fa-file-pdf"></i> Baca PDF
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal untuk Daftar Buku -->
      <div id="bookListModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
          <div class="flex justify-between items-center border-b p-4">
            <h3 id="bookListModalTitle" class="text-lg font-semibold text-gray-800"></h3>
            <button onclick="closeBookListModal()" class="text-gray-400 hover:text-gray-600 w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-100">
              <i class="fas fa-times"></i>
            </button>
          </div>
          <div class="p-6">
            <ul id="bookListContainer" class="list-disc list-inside space-y-2 text-gray-700">
              <!-- Daftar buku akan diisi oleh JavaScript -->
            </ul>
          </div>
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
          height: '100%', // PERBAIKAN: Mengubah tinggi menjadi responsif
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

    // --- SCRIPT UNTUK MODAL DAFTAR BUKU ---
    function showBookListModal(title, bookListString) {
      const modal = document.getElementById('bookListModal');
      const modalTitle = document.getElementById('bookListModalTitle');
      const listContainer = document.getElementById('bookListContainer');

      // Set judul modal
      modalTitle.innerText = title;

      // Bersihkan daftar sebelumnya
      listContainer.innerHTML = '';

      // Buat daftar buku dari string yang dipisahkan koma
      const books = bookListString.split(', ');
      books.forEach(book => {
        const li = document.createElement('li');
        li.textContent = book;
        listContainer.appendChild(li);
      });

      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeBookListModal() {
      document.getElementById('bookListModal').classList.add('hidden');
    }

    // --- SCRIPT UNTUK MODAL DETAIL BUKU DI DASHBOARD ---
    function showDashboardBookDetailModal(element) {
      const getValue = (val) => val && val.trim() !== "" ? val : "-";

      document.getElementById('modalJudul').innerText = getValue(element.dataset.judul);
      document.getElementById('modalPenerbit').innerText = getValue(element.dataset.penerbit);
      document.getElementById('modalPengarang').innerText = "oleh " + getValue(element.dataset.pengarang);
      document.getElementById('modalTahun').innerText = getValue(element.dataset.tahun);
      
      const stokElement = document.getElementById('modalStok');
      const stokValue = parseInt(getValue(element.dataset.stok), 10);
      stokElement.innerText = isNaN(stokValue) || stokValue <= 0 ? 'Kosong' : `${stokValue} Tersedia`;
      stokElement.className = 'font-semibold'; // Reset class
      stokElement.classList.add(isNaN(stokValue) || stokValue <= 0 ? 'text-red-600' : 'text-green-600');

      const kategoriContainer = document.getElementById('modalKategori');
      kategoriContainer.innerHTML = ''; // Kosongkan dulu
      const kategoriList = getValue(element.dataset.kategori).split(', ');
      kategoriList.forEach(kat => {
          if(kat !== '-') {
            const span = document.createElement('span');
            span.className = 'bg-green-100 px-2.5 py-1 rounded-full';
            span.textContent = kat;
            kategoriContainer.appendChild(span);
          }
      });
      document.getElementById('modalDeskripsi').innerText = getValue(element.dataset.deskripsi);
      document.getElementById('modalImage').src = getValue(element.dataset.image);
      document.getElementById('modalEisbn').innerText = getValue(element.dataset.eisbn);
      document.getElementById('modalIsbn').innerText = getValue(element.dataset.isbn);

      // Handle PDF button
      const pdfUrl = element.dataset.pdf;
      const readPdfButton = document.getElementById('readPdfButton');
      if (pdfUrl) {
          readPdfButton.href = pdfUrl;
          readPdfButton.style.display = 'flex';
      } else {
          readPdfButton.style.display = 'none';
      }

      const modal = document.getElementById('detailModal');
      const content = document.getElementById('modalContent');

      modal.classList.remove('hidden');
      modal.classList.add('flex');

      content.classList.remove('modal-leave', 'modal-leave-active');
      content.classList.add('modal-enter');
      setTimeout(() => {
        content.classList.remove('modal-enter');
        content.classList.add('modal-enter-active');
      }, 10);
    }

    function closeDashboardBookDetailModal() {
      const modal = document.getElementById('detailModal');
      const content = document.getElementById('modalContent');

      content.classList.remove('modal-enter-active');
      content.classList.add('modal-leave');
      setTimeout(() => {
        content.classList.remove('modal-leave');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }, 200);
    }
  </script>
</body>
</html>