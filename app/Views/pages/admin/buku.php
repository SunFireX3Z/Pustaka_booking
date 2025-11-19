<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Buku</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- ApexCharts -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Tom Select -->
  <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
  <style>
    @keyframes toast-in-right {
      from {
        transform: translateX(100%);
        opacity: 0;
      }
      to {
        transform: translateX(0); 
        opacity: 1;
      }
    }
    .swal2-show.swal2-toast {
      animation: toast-in-right 0.5s;
    }
    .swal2-hide.swal2-toast {
      animation: none; /* Biarkan SweetAlert menangani animasi keluar */
    }
  </style>
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
    if (localStorage.getItem('sidebarCollapsed') === 'true') {
      document.documentElement.classList.add('sidebar-is-collapsed');
    }
  </script>
</head>
<body class="bg-gray-100 flex">

  <?php
    $current_page = 'buku'; // Set halaman aktif
    echo view('pages/admin/template/sidebar', ['current_page' => $current_page]);
  ?>

  <!-- Main content -->
  <div id="main-content" class="flex-1 flex flex-col ml-64 transition-all duration-300">

    <!-- Header -->
    <header class="bg-white shadow-sm p-4 flex justify-between items-center sticky top-0 z-30">
      <div class="flex items-center">
        <button id="sidebar-toggle" class="text-gray-500 hover:text-gray-700 focus:outline-none mr-4"><i class="fas fa-bars text-lg"></i></button>
        <h1 class="text-xl font-semibold">Daftar Buku</h1>
      </div>
      <?= view('pages/admin/template/header_user_profile'); ?>
    </header>

    <!-- Content -->
    <main class="p-6">
      <div class="w-full max-w-7xl mx-auto">
      <div class="card-item relative bg-gradient-to-r from-green-500 to-blue-500 text-white p-6 rounded-lg shadow-lg mb-8 overflow-hidden">
        <div class="absolute -right-10 -bottom-10">
            <i class="fas fa-book-bookmark text-white/10 text-9xl transform -rotate-12"></i>
        </div>
        <div class="relative z-10">
          <h2 class="text-3xl font-bold">Koleksi Buku</h2>
          <p class="mt-1 text-green-100">Kelola, cari, dan lihat semua koleksi buku yang tersedia di perpustakaan.</p>
          <form action="<?= base_url('buku') ?>" method="get">
            <div class="flex flex-col sm:flex-row items-center gap-2 mt-4">
              <div class="relative w-full sm:w-64">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3"><i class="fas fa-search text-gray-400"></i></span>
                <input type="text" name="keyword" id="searchFilter" placeholder="Cari judul, pengarang..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-full shadow-sm focus:outline-none focus:ring-2 focus:ring-white/50 text-gray-800" value="<?= esc($keyword ?? '') ?>">
              </div>
              <select name="kategori" id="categoryFilter" class="w-full sm:w-56 px-4 py-2 border border-gray-300 rounded-full shadow-sm focus:outline-none focus:ring-2 focus:ring-white/50 text-gray-800" onchange="this.form.submit()">
                <option value="all">Semua Kategori</option>
                <?php foreach ($kategori as $kat): ?>
                  <option value="<?= esc($kat['id_kategori']) ?>" <?= ($selected_kategori ?? '') == $kat['id_kategori'] ? 'selected' : '' ?>><?= esc($kat['nama_kategori']) ?></option>
                <?php endforeach; ?>
              </select>
              <button onclick="openAddModal()" type="button" class="flex-shrink-0 w-full sm:w-auto bg-white text-green-600 font-semibold px-5 py-2 rounded-full hover:bg-green-50 flex items-center justify-center shadow-md hover:shadow-lg transition-all">
                  <i class="fas fa-plus mr-2"></i> Tambah Buku
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Kartu Statistik -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Total Judul Buku -->
        <div class="card-item bg-white p-5 rounded-lg shadow-md flex items-center space-x-4 border-l-4 border-purple-500">
          <div class="bg-purple-100 p-3 rounded-full">
            <i class="fas fa-book fa-lg text-purple-600"></i>
          </div>
          <div>
            <p class="text-gray-500 text-sm font-medium">Total Judul Buku</p>
            <p class="text-2xl font-bold text-gray-800"><?= $total_judul_buku ?? 0 ?></p>
          </div>
        </div>
        <!-- Total Stok Buku -->
        <div class="card-item bg-white p-5 rounded-lg shadow-md flex items-center space-x-4 border-l-4 border-yellow-500">
          <div class="bg-yellow-100 p-3 rounded-full">
            <i class="fas fa-boxes fa-lg text-yellow-600"></i>
          </div>
          <div>
            <p class="text-gray-500 text-sm font-medium">Total Stok Keseluruhan</p>
            <p class="text-2xl font-bold text-gray-800"><?= $total_stok_buku ?? 0 ?></p>
          </div>
        </div>
      </div>

      <?php if ($validation->getErrors()): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
          <strong class="font-bold">Terjadi Kesalahan!</strong>
          <span class="block sm:inline"><?= $validation->listErrors() ?></span>
        </div>
      <?php endif; ?>

      <?php if (!empty($buku)): ?>
        <div id="bookListContainer" class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <?php foreach ($buku as $row): ?>
              <div class="book-item card-item bg-white rounded-xl shadow-md overflow-hidden flex items-start transition-all duration-300 hover:shadow-xl hover:scale-[1.02]">
                <!-- Gambar -->
                <img src="<?= base_url('uploads/' . $row['image']) ?>" alt="Cover: <?= esc($row['judul_buku']) ?>" class="w-28 h-40 object-cover flex-shrink-0">
                
                <!-- Info Buku -->
                <div class="p-4 flex-grow flex flex-col justify-between self-stretch">
                  <div class="flex-grow">
                    <div class="text-xs font-semibold text-gray-600 mb-1 flex flex-wrap items-center gap-1">
                      <?php
                        $kategori_list = (!empty($row['kategori_nama'])) ? explode(', ', $row['kategori_nama']) : [];
                        $total_kategori = count($kategori_list);
                        $max_kategori_tampil = 2; // Batas kategori yang ditampilkan
                      ?>
                      <?php foreach($kategori_list as $index => $kat): ?>
                        <?php if($index < $max_kategori_tampil): ?>
                          <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded-full"><?= esc($kat) ?></span>
                        <?php endif; ?>
                      <?php endforeach; ?>
                      <?php if($total_kategori > $max_kategori_tampil): ?>
                        <span class="bg-gray-200 text-gray-700 px-2 py-0.5 rounded-full">+<?= $total_kategori - $max_kategori_tampil ?> lagi</span>
                      <?php endif; ?>
                    </div>
                    <h3 class="text-base font-bold text-gray-800 leading-tight mt-1"><?= esc($row['judul_buku']) ?></h3>
                    <p class="text-sm text-gray-500" title="Oleh: <?= esc($row['pengarang']) ?>">Oleh: 
                      <?php
                        $pengarang = $row['pengarang'];
                        $limit = 25; // Batas karakter
                        echo esc(strlen($pengarang) > $limit ? substr($pengarang, 0, $limit) . '...' : $pengarang);
                      ?>
                    </p>
                    <div class="text-xs text-gray-500 mt-2 flex items-center gap-3">
                      <span title="<?= esc($row['penerbit']) ?>"><i class="fas fa-building mr-1"></i> 
                        <?php
                          $penerbit = $row['penerbit'];
                          $limit = 25; // Batas karakter
                          echo esc(strlen($penerbit) > $limit ? substr($penerbit, 0, $limit) . '...' : $penerbit);
                        ?>
                      </span>
                      <span><i class="fas fa-calendar-alt mr-1"></i> <?= esc($row['tahun_terbit']) ?></span>
                      <span class="font-medium <?= $row['stok'] > 0 ? 'text-green-600' : 'text-red-600' ?>">
                        <i class="fas fa-box-open mr-1"></i> Stok: <?= esc($row['stok']) ?>
                      </span>
                    </div>
                  </div>
                  
                  <!-- Tombol Aksi -->
                  <div class="flex-shrink-0 flex items-center gap-2 mt-3">
                    <a href="<?= base_url('buku/qrcode/' . $row['id']) ?>" target="_blank" class="w-9 h-9 flex items-center justify-center rounded-full text-blue-500 hover:bg-blue-100 transition-colors" title="Tampilkan QR Code">
                      <i class="fas fa-qrcode text-sm"></i>
                    </a>
                    <button onclick="showDetail(this)" class="w-9 h-9 flex items-center justify-center rounded-full text-gray-500 hover:bg-gray-200 hover:text-gray-800 transition-colors" title="Lihat Detail"
                      data-judul="<?= esc($row['judul_buku']) ?>" data-penerbit="<?= esc($row['penerbit']) ?>" data-pengarang="<?= esc($row['pengarang']) ?>" data-tahun="<?= esc($row['tahun_terbit']) ?>" data-stok="<?= esc($row['stok']) > 0 ? $row['stok'] . ' Tersedia' : 'Kosong' ?>" data-kategori="<?= esc($row['kategori_nama'] ?? 'Tidak ada kategori') ?>" data-deskripsi="<?= esc($row['deskripsi'] ?? '-') ?>" data-image="<?= base_url('uploads/' . $row['image']) ?>" data-pdf="<?= !empty($row['file_pdf']) ? base_url('uploads/pdf/' . esc($row['file_pdf'])) : '' ?>" data-isbn="<?= esc($row['isbn']) ?>" data-eisbn="<?= esc($row['eisbn'] ?? '-') ?>">
                      <i class="fas fa-eye text-sm"></i>
                    </button>
                    <button onclick="openEditModal(<?= htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') ?>)" class="w-9 h-9 flex items-center justify-center rounded-full text-green-500 hover:bg-green-100 transition-colors" title="Edit">
                      <i class="fas fa-edit text-sm"></i>
                    </button>
                    <a href="<?= base_url('buku/delete/' . $row['id']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')" class="w-9 h-9 flex items-center justify-center rounded-full text-red-500 hover:bg-red-100 transition-colors" title="Hapus">
                      <i class="fas fa-trash text-sm"></i>
                    </a>
                  </div>
                </div>
              </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="card-item bg-white rounded-lg shadow-sm p-12 text-center">
          <div class="flex justify-center items-center mb-4">
            <i class="fas fa-book-dead text-5xl text-gray-300"></i>
          </div>
          <h3 class="text-xl font-semibold text-gray-700">Belum Ada Buku</h3>
          <p class="text-gray-500 mt-2">Tidak ada data buku yang dapat ditampilkan. Silakan tambahkan buku baru.</p>
        </div>
      <?php endif; ?>

      </div>
    </main>
  </div>

  <!-- Modal Detail -->
  <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center z-50 p-4">
    <div id="modalContent" class="bg-white rounded-lg shadow-2xl max-w-4xl w-full transform transition-all relative mt-16 md:mt-0">
      <!-- Tombol Close di pojok kanan atas -->
      <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-800 w-10 h-10 rounded-full flex items-center justify-center hover:bg-gray-100 transition-colors z-20">
        <i class="fas fa-times fa-lg"></i>
      </button>

      <div class="flex flex-col md:flex-row">
        <!-- Kolom Kiri untuk Gambar -->
        <div class="w-full md:w-1/3 bg-gray-100 rounded-t-lg md:rounded-l-lg md:rounded-t-none flex items-center justify-center p-6">
          <img id="modalImage" src="" alt="cover" class="max-h-96 object-contain shadow-2xl rounded-lg">
        </div>

        <!-- Kolom Kanan untuk Konten -->
        <div class="w-full md:w-2/3 p-8 md:p-10 flex flex-col">
          <div class="flex-grow">
            <div id="modalKategori" class="text-xs font-semibold text-green-800 mb-3 flex flex-wrap gap-1">
              <!-- Kategori akan diisi oleh JS -->
            </div>
            <h2 id="modalJudul" class="text-4xl font-bold text-gray-900 mb-2 leading-tight"></h2>
            <p id="modalPengarang" class="text-gray-500 text-lg mb-6"></p>
            
            <!-- Detail Meta -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-6 mb-8 text-sm">
              <div class="flex items-center gap-3">
                <i class="fas fa-building fa-fw text-gray-400 text-xl"></i>
                <div>
                  <p class="text-gray-500">Penerbit</p>
                  <p id="modalPenerbit" class="text-gray-800 font-semibold"></p>
                </div>
              </div>
              <div class="flex items-center gap-3">
                <i class="fas fa-calendar-alt fa-fw text-gray-400 text-xl"></i>
                <div>
                  <p class="text-gray-500">Tahun</p>
                  <p id="modalTahun" class="text-gray-800 font-semibold"></p>
                </div>
              </div>
              <div class="flex items-center gap-3">
                <i class="fas fa-box-open fa-fw text-gray-400 text-xl"></i> 
                <div>
                  <p class="text-gray-500">Stok</p>
                  <p id="modalStok" class="font-semibold"></p>
                </div>
              </div>
              <div class="flex items-center gap-3">
                <i class="fas fa-barcode fa-fw text-gray-400 text-xl"></i> 
                <div>
                  <p class="text-gray-500">ISBN</p>
                  <p id="modalIsbn" class="font-semibold"></p>
                </div>
              </div>
              <div class="flex items-center gap-3">
                <i class="fas fa-barcode fa-fw text-gray-400 text-xl"></i> 
                <div>
                  <p class="text-gray-500">EISBN</p>
                  <p id="modalEisbn" class="font-semibold"></p>
                </div>
              </div>
            </div>

            <!-- Deskripsi -->
            <div class="flex flex-col min-h-0">
              <div class="border-l-4 border-green-200 pl-4 overflow-y-auto pr-2">
                <h4 class="font-semibold text-gray-800 mb-1">Deskripsi</h4>
                <p id="modalDeskripsi" class="text-gray-600 text-sm leading-relaxed italic"></p>
              </div>
            </div>
          </div>
          <!-- Tombol Aksi Modal -->
          <div class="p-6 bg-gray-50 rounded-b-lg flex justify-end space-x-3">
            <a id="readPdfButton" href="#" target="_blank" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 shadow-sm flex items-center gap-2" style="display: none;">
              <i class="fas fa-file-pdf"></i> Baca PDF
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Tambah Buku -->
  <div id="addModal" class="fixed inset-0 bg-black bg-opacity-60 hidden items-start justify-center z-50 overflow-y-auto py-10">
    <div id="addModalContent" class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6 relative transform transition-all">
      <button onclick="closeAddModal()" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors">
        <i class="fas fa-times"></i>
      </button>
      <h2 class="text-xl font-bold mb-1 text-gray-800">Tambah Buku Baru</h2>
      <p class="text-sm text-gray-500 mb-5 border-b pb-3">Isi semua field untuk menambahkan buku baru.</p>
      <form action="<?= base_url('buku') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="grid grid-cols-2 gap-x-4 gap-y-3">
          <div class="col-span-2">
            <label for="add_judul_buku" class="block text-sm font-medium text-gray-700">Judul Buku</label>
            <input type="text" name="judul_buku" id="add_judul_buku" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500" value="<?= old('judul_buku') ?>" required>
          </div>
          <div class="col-span-2" id="add-kategori-wrapper">
            <label for="add_kategori_ids" class="block text-sm font-medium text-gray-700">Kategori</label>
            <select id="add_kategori_ids" name="kategori_ids[]" multiple placeholder="Pilih satu atau lebih kategori..." required></select>
          </div>
          <div>
            <label for="add_pengarang" class="block text-sm font-medium text-gray-700">Pengarang</label>
            <input type="text" name="pengarang" id="add_pengarang" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500" value="<?= old('pengarang') ?>" required>
          </div>
          <div>
            <label for="add_penerbit" class="block text-sm font-medium text-gray-700">Penerbit</label>
            <input type="text" name="penerbit" id="add_penerbit" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500" value="<?= old('penerbit') ?>" required>
          </div>
          <div>
            <label for="add_tahun_terbit" class="block text-sm font-medium text-gray-700">Tahun Terbit</label>
            <input type="number" name="tahun_terbit" id="add_tahun_terbit" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500" value="<?= old('tahun_terbit') ?>" required>
          </div>
          <div>
            <label for="add_isbn" class="block text-sm font-medium text-gray-700">ISBN</label>
            <input type="text" name="isbn" id="add_isbn" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500" value="<?= old('isbn') ?>" required>
          </div>
          <div>
            <label for="add_eisbn" class="block text-sm font-medium text-gray-700">EISBN (Opsional)</label>
            <input type="text" name="eisbn" id="add_eisbn" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500" value="<?= old('eisbn') ?>">
          </div>
          <div>
            <label for="add_stok" class="block text-sm font-medium text-gray-700">Stok</label>
            <input type="number" name="stok" id="add_stok" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500" value="<?= old('stok') ?>" required>
          </div>
          <div class="col-span-2">
            <label for="add_deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="deskripsi" id="add_deskripsi" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500" rows="3" required><?= old('deskripsi') ?></textarea>
          </div>
          <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700">Cover Buku</label>
            <input type="file" name="image" class="mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100" required>
          </div>
          <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700">File PDF (Opsional)</label>
            <input type="file" name="file_pdf" class="mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
          </div>
        </div>
        <div class="flex justify-end space-x-2 pt-5">
          <button type="button" onclick="closeAddModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">Batal</button>
          <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 shadow-sm hover:shadow-md transition-all">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Edit Buku -->
  <div id="editModal" class="fixed inset-0 bg-black bg-opacity-60 hidden items-start justify-center z-50 overflow-y-auto py-10">
    <div id="editModalContent" class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6 relative transform transition-all">
      <button onclick="closeEditModal()" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors">
        <i class="fas fa-times"></i>
      </button>
      <h2 class="text-xl font-bold mb-1 text-gray-800">Edit Buku</h2>
      <p class="text-sm text-gray-500 mb-5 border-b pb-3">Perbarui detail buku di bawah ini.</p>
      <form id="editForm" action="" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="grid grid-cols-2 gap-x-4 gap-y-3">
          <div class="col-span-2">
            <label for="edit_judul_buku" class="block text-sm font-medium text-gray-700">Judul Buku</label>
            <input type="text" name="judul_buku" id="edit_judul_buku" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500" required>
          </div>
          <div class="col-span-2" id="edit-kategori-wrapper">
            <label for="edit_kategori_ids" class="block text-sm font-medium text-gray-700">Kategori</label>
            <select id="edit_kategori_ids" name="kategori_ids[]" multiple placeholder="Pilih satu atau lebih kategori..." required></select>
            <div id="edit_current_kategori" class="text-xs text-gray-500 mt-1"></div>
          </div>
          <div>
            <label for="edit_pengarang" class="block text-sm font-medium text-gray-700">Pengarang</label>
            <input type="text" name="pengarang" id="edit_pengarang" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500" required>
          </div>
          <div>
            <label for="edit_penerbit" class="block text-sm font-medium text-gray-700">Penerbit</label>
            <input type="text" name="penerbit" id="edit_penerbit" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500" required>
          </div>
          <div>
            <label for="edit_tahun_terbit" class="block text-sm font-medium text-gray-700">Tahun Terbit</label>
            <input type="number" name="tahun_terbit" id="edit_tahun_terbit" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500" required>
          </div>
          <div>
            <label for="edit_isbn" class="block text-sm font-medium text-gray-700">ISBN</label>
            <input type="text" name="isbn" id="edit_isbn" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500" required>
          </div>
          <div>
            <label for="edit_eisbn" class="block text-sm font-medium text-gray-700">EISBN (Opsional)</label>
            <input type="text" name="eisbn" id="edit_eisbn" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
          </div>
          <div>
            <label for="edit_stok" class="block text-sm font-medium text-gray-700">Stok</label>
            <input type="number" name="stok" id="edit_stok" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500" required>
          </div>
          <div class="col-span-2">
            <label for="edit_deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="deskripsi" id="edit_deskripsi" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500" rows="3" required></textarea>
          </div>
          <div class="col-span-2">
          <label class="block text-sm font-medium text-gray-700">Ganti Cover Buku (Opsional)</label>
          <div class="flex items-center gap-4 mt-1">
            <img id="edit_current_image" src="" alt="Current Cover" class="w-16 h-20 object-cover rounded bg-gray-100">
            <input type="file" name="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
          </div>
        </div>
        <div class="col-span-2">
          <label class="block text-sm font-medium text-gray-700">Ganti File PDF (Opsional)</label>
          <div class="mt-1">
            <input type="file" name="file_pdf" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
            <p id="current_pdf_file" class="text-xs text-gray-500 mt-1">File saat ini: <a href="#" target="_blank" class="text-green-600 hover:underline"></a></p>
          </div>
        </div>
        </div>
        <div class="flex justify-end space-x-2 pt-5">
          <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">Batal</button>
          <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 shadow-sm hover:shadow-md transition-all">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
  <script>
    function openAddModal() {
      const modal = document.getElementById('addModal');
      const content = document.getElementById('addModalContent');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
      content.classList.remove('modal-leave', 'modal-leave-active');
      content.classList.add('modal-enter');
      setTimeout(() => {
        content.classList.remove('modal-enter');
        content.classList.add('modal-enter-active');
      }, 10);
      // Reset Tom Select
      if (window.tomSelectAdd) window.tomSelectAdd.clear();
    }

    function closeAddModal() {
      const modal = document.getElementById('addModal');
      const content = document.getElementById('addModalContent');
      content.classList.remove('modal-enter-active');
      content.classList.add('modal-leave');
      setTimeout(() => {
        content.classList.remove('modal-leave');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
      }, 200);
    }

    function openEditModal(bookData) {
      // Set form action
      document.getElementById('editForm').action = `<?= base_url('buku/update/') ?>/${bookData.id}`;
      
      // Logika untuk menangani kategori_ids, termasuk kasus ketika tidak ada kategori (null)
      let kategoriIds = [];
      if (bookData.kategori_ids && typeof bookData.kategori_ids === 'string' && bookData.kategori_ids.length > 0) {
        // Untuk Tom Select, kita hanya perlu array string.
        kategoriIds = bookData.kategori_ids.split(',').map(id => id.trim());
      }

      // Tampilkan nama kategori saat ini sebagai teks
      const currentKategoriEl = document.getElementById('edit_current_kategori');
      if (bookData.kategori_nama && bookData.kategori_nama.length > 0) {
        currentKategoriEl.innerHTML = `<strong>Kategori Saat Ini:</strong> ${bookData.kategori_nama}`;
      } else {
        currentKategoriEl.innerHTML = `<strong>Kategori Saat Ini:</strong> -`;
      }
      // Set nilai ke Tom Select
      if (window.tomSelectEdit) window.tomSelectEdit.setValue(kategoriIds);

      document.getElementById('edit_judul_buku').value = bookData.judul_buku;
      document.getElementById('edit_pengarang').value = bookData.pengarang;
      document.getElementById('edit_penerbit').value = bookData.penerbit;
      document.getElementById('edit_tahun_terbit').value = bookData.tahun_terbit;
      document.getElementById('edit_isbn').value = bookData.isbn;
      document.getElementById('edit_eisbn').value = bookData.eisbn || '';
      document.getElementById('edit_stok').value = bookData.stok;
      document.getElementById('edit_deskripsi').value = bookData.deskripsi;
      document.getElementById('edit_current_image').src = `<?= base_url('uploads/') ?>/${bookData.image}`;

      // Handle PDF file info
      const pdfInfo = document.getElementById('current_pdf_file');
      const pdfLink = pdfInfo.querySelector('a');
      if (bookData.file_pdf) {
        pdfLink.href = `<?= base_url('uploads/pdf/') ?>/${bookData.file_pdf}`;
        pdfLink.textContent = bookData.file_pdf;
        pdfInfo.style.display = 'block';
      } else {
        pdfInfo.style.display = 'none';
      }

      const modal = document.getElementById('editModal');
      const content = document.getElementById('editModalContent');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
      content.classList.remove('modal-leave', 'modal-leave-active');
      content.classList.add('modal-enter');
      setTimeout(() => {
        content.classList.remove('modal-enter');
        content.classList.add('modal-enter-active');
      }, 10);
    }

    function closeEditModal() {
      const modal = document.getElementById('editModal');
      const content = document.getElementById('editModalContent');
      content.classList.remove('modal-enter-active');
      content.classList.add('modal-leave');
      setTimeout(() => {
        content.classList.remove('modal-leave');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
      }, 200);
    }

    function showDetail(element) {
      const getValue = (val) => val && val.trim() !== "" ? val : "-";

      document.getElementById('modalJudul').innerText = getValue(element.dataset.judul);
      document.getElementById('modalPenerbit').innerText = getValue(element.dataset.penerbit);
      document.getElementById('modalPengarang').innerText = "oleh " + getValue(element.dataset.pengarang);
      document.getElementById('modalTahun').innerText = getValue(element.dataset.tahun);
      
      const stokElement = document.getElementById('modalStok');
      const stokText = getValue(element.dataset.stok);
      stokElement.innerText = stokText;
      stokElement.className = 'font-semibold'; // Reset class
      stokElement.classList.add(stokText.toLowerCase().includes('kosong') ? 'text-red-600' : 'text-green-600');

      const kategoriContainer = document.getElementById('modalKategori');
      kategoriContainer.innerHTML = ''; // Kosongkan dulu
      const kategoriList = getValue(element.dataset.kategori).split(', ');
      kategoriList.forEach(kat => {
          const span = document.createElement('span');
          span.className = 'bg-green-100 px-2.5 py-1 rounded-full';
          span.textContent = kat;
          kategoriContainer.appendChild(span);
      });
      document.getElementById('modalDeskripsi').innerText = getValue(element.dataset.deskripsi);
      document.getElementById('modalImage').src = getValue(element.dataset.image);
      document.getElementById('modalEisbn').innerText = getValue(element.dataset.eisbn);
      document.getElementById('modalIsbn').innerText = getValue(element.dataset.isbn);

      // Handle PDF button in detail modal
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

    function closeModal() {
      const modal = document.getElementById('detailModal');
      const content = document.getElementById('modalContent');

      content.classList.remove('modal-enter-active');
      content.classList.add('modal-leave');
      setTimeout(() => {
        content.classList.remove('modal-leave');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
      }, 200);
    }

    // Jika ada error validasi, otomatis buka modal tambah
    <?php if (session()->getFlashdata('show_add_modal')): ?>
      openAddModal();
    <?php endif; ?>

    // Jika ada error validasi saat edit, otomatis buka modal edit
    <?php
      $bookToEdit = session()->getFlashdata('book_to_edit');
      if ($bookToEdit):
    ?>
      openEditModal(<?= json_encode($bookToEdit) ?>);
    <?php endif; ?>

    // Tampilkan notifikasi toast dengan SweetAlert2
    const AnimatedToast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
    });

    <?php if (session()->getFlashdata('success')): ?>
      AnimatedToast.fire({
        icon: 'success',
        title: '<?= session()->getFlashdata('success') ?>'
      })
    <?php endif; ?>
    <?php if (session()->getFlashdata('error') || session()->getFlashdata('error_upload')): ?>
      AnimatedToast.fire({
        icon: 'error',
        title: '<?= session()->getFlashdata('error') ?? session()->getFlashdata('error_upload') ?>'
      })
    <?php endif; ?>

    // Animasi staggered untuk kartu buku
    document.addEventListener('DOMContentLoaded', () => {
      const cards = document.querySelectorAll('.card-item');
      cards.forEach((card, index) => {
        setTimeout(() => {
          card.classList.add('is-visible');
        }, index * 100); // Delay 100ms untuk setiap kartu
      });
    });

    // Sidebar Toggle
    const sidebarToggle = document.getElementById('sidebar-toggle');

    sidebarToggle.addEventListener('click', () => {
      document.documentElement.classList.toggle('sidebar-is-collapsed');
      localStorage.setItem('sidebarCollapsed', document.documentElement.classList.contains('sidebar-is-collapsed'));
    });

    // Inisialisasi Tom Select
    document.addEventListener('DOMContentLoaded', function() {
      const kategoriOptions = <?= json_encode(array_map(function($k) {
          return ['value' => $k['id_kategori'], 'text' => esc($k['nama_kategori'])];
      }, $kategori)) ?>;

      window.tomSelectAdd = new TomSelect('#add_kategori_ids', {
        options: kategoriOptions,
        plugins: ['remove_button'],
        create: false,
      });

      window.tomSelectEdit = new TomSelect('#edit_kategori_ids', {
        options: kategoriOptions,
        plugins: ['remove_button'],
        create: false,
      });
    });
  </script>
</body>
</html>