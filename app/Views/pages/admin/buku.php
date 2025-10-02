<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Buku</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .modal-enter { opacity: 0; transform: translateY(-20px); }
    .modal-enter-active { opacity: 1; transform: translateY(0); transition: all 0.3s ease-out; }
    .modal-leave { opacity: 1; transform: translateY(0); }
    .modal-leave-active { opacity: 0; transform: translateY(-20px); transition: all 0.2s ease-in; }

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
    if (localStorage.getItem('sidebarCollapsed') === 'true') {
      document.documentElement.classList.add('sidebar-is-collapsed');
    }
  </script>
</head>
<body class="bg-gray-100 flex">

  <?php
    $current_page = 'buku';
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

    <!-- Header -->
    <header class="bg-white shadow-sm p-4 flex justify-between items-center">
      <div class="flex items-center">
        <button id="sidebar-toggle" class="text-gray-500 hover:text-gray-700 focus:outline-none mr-4"><i class="fas fa-bars text-lg"></i></button>
        <h1 class="text-xl font-semibold">Daftar Buku</h1>
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
      <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <div>
            <h2 class="text-2xl font-bold text-blue-600">Daftar Buku Perpustakaan</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola dan lihat koleksi buku yang tersedia.</p>
          </div>
          <div class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto">
            <div class="relative w-full sm:w-56">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <i class="fas fa-search text-gray-400"></i>
              </span>
              <input type="text" id="searchFilter" placeholder="Cari judul atau pengarang..." 
                     class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <select id="categoryFilter" class="w-full sm:w-48 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
              <option value="all">Semua Kategori</option>
              <?php
                $categoryNames = array_keys($groupedBooks);
                sort($categoryNames);
                foreach ($categoryNames as $categoryName):
              ?>
                <option value="<?= esc($categoryName) ?>"><?= esc($categoryName) ?></option>
              <?php endforeach; ?>
            </select>
            <button onclick="openAddModal()" class="flex-shrink-0 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Buku
            </button>
          </div>
        </div>
      </div>

      <?php if ($validation->getErrors()): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
          <strong class="font-bold">Terjadi Kesalahan!</strong>
          <span class="block sm:inline"><?= $validation->listErrors() ?></span>
        </div>
      <?php endif; ?>

      <?php if (!empty($groupedBooks)): ?>
        <div id="bookListContainer" class="grid grid-cols-1 lg:grid-cols-2 gap-4">
          <?php foreach ($groupedBooks as $categoryName => $booksInCategory): ?>
            <?php foreach ($booksInCategory as $row): ?>
              <div class="book-item card-item bg-white rounded-lg shadow-sm overflow-hidden flex items-center transition-all duration-300 hover:shadow-lg" data-category="<?= esc($categoryName) ?>">
                <!-- Gambar -->
                <img src="<?= base_url('uploads/' . $row['image']) ?>" alt="Cover: <?= esc($row['judul_buku']) ?>" class="w-24 h-auto object-contain flex-shrink-0">
                
                <!-- Info Buku -->
                <div class="p-4 flex-grow flex flex-col sm:flex-row justify-between items-start">
                  <div class="flex-grow">
                    <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-0.5 rounded-full mb-1"><?= esc($categoryName) ?></span>
                    <h3 class="text-lg font-bold text-gray-800 leading-tight"><?= esc($row['judul_buku']) ?></h3>
                    <p class="text-sm text-gray-500">Oleh: <?= esc($row['pengarang']) ?></p>
                    <div class="text-xs text-gray-500 mt-2 flex items-center gap-3">
                      <span><i class="fas fa-building mr-1"></i> <?= esc($row['penerbit']) ?></span>
                      <span><i class="fas fa-calendar-alt mr-1"></i> <?= esc($row['tahun_terbit']) ?></span>
                      <span class="font-medium <?= $row['stok'] > 0 ? 'text-green-600' : 'text-red-600' ?>">
                        <i class="fas fa-box-open mr-1"></i> Stok: <?= esc($row['stok']) ?>
                      </span>
                    </div>
                  </div>
                  
                  <!-- Tombol Aksi -->
                  <div class="flex-shrink-0 flex items-center gap-2 mt-3 sm:mt-0">
                    <button onclick="showDetail(this)" class="w-9 h-9 flex items-center justify-center rounded-full text-gray-500 hover:bg-gray-200 hover:text-gray-800 transition-colors" title="Lihat Detail"
                      data-judul="<?= esc($row['judul_buku']) ?>" data-penerbit="<?= esc($row['penerbit']) ?>" data-pengarang="<?= esc($row['pengarang']) ?>" data-tahun="<?= esc($row['tahun_terbit']) ?>" data-stok="<?= esc($row['stok']) > 0 ? $row['stok'] . ' Tersedia' : 'Kosong' ?>" data-kategori="<?= esc($row['nama_kategori'] ?? 'Tidak ada kategori') ?>" data-hipotesis="<?= esc($row['hipotesis'] ?? '-') ?>" data-image="<?= base_url('uploads/' . $row['image']) ?>">
                      <i class="fas fa-eye text-sm"></i>
                    </button>
                    <button onclick="openEditModal(<?= htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') ?>)" class="w-9 h-9 flex items-center justify-center rounded-full text-blue-500 hover:bg-blue-100 transition-colors" title="Edit">
                      <i class="fas fa-edit text-sm"></i>
                    </button>
                    <a href="<?= base_url('buku/delete/' . $row['id']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')" class="w-9 h-9 flex items-center justify-center rounded-full text-red-500 hover:bg-red-100 transition-colors" title="Hapus">
                      <i class="fas fa-trash text-sm"></i>
                    </a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p class="text-center text-gray-500">Belum ada data buku.</p>
      <?php endif; ?>
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
            <span id="modalKategori" class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-1 rounded-full mb-3"></span>
            <h2 id="modalJudul" class="text-4xl font-bold text-gray-900 mb-2 leading-tight"></h2>
            <p id="modalPengarang" class="text-gray-500 text-lg mb-6"></p>
            
            <!-- Detail Meta -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8 text-sm">
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
            </div>

            <!-- Hipotesis -->
            <div class="flex flex-col min-h-0">
              <div class="border-l-4 border-blue-200 pl-4 overflow-y-auto pr-2">
                <h4 class="font-semibold text-gray-800 mb-1">Hipotesis</h4>
                <p id="modalHipotesis" class="text-gray-600 text-sm leading-relaxed italic"></p>
              </div>
            </div>
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
            <input type="text" name="judul_buku" id="add_judul_buku" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="<?= old('judul_buku') ?>" required>
          </div>
          <div>
            <label for="add_id_kategori" class="block text-sm font-medium text-gray-700">Kategori</label>
            <select name="id_kategori" id="add_id_kategori" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
              <option value="">Pilih Kategori</option>
              <?php foreach($kategori as $k): ?>
                <option value="<?= $k['id_kategori'] ?>" <?= old('id_kategori') == $k['id_kategori'] ? 'selected' : '' ?>><?= esc($k['nama_kategori']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="add_pengarang" class="block text-sm font-medium text-gray-700">Pengarang</label>
            <input type="text" name="pengarang" id="add_pengarang" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="<?= old('pengarang') ?>" required>
          </div>
          <div>
            <label for="add_penerbit" class="block text-sm font-medium text-gray-700">Penerbit</label>
            <input type="text" name="penerbit" id="add_penerbit" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="<?= old('penerbit') ?>" required>
          </div>
          <div>
            <label for="add_tahun_terbit" class="block text-sm font-medium text-gray-700">Tahun Terbit</label>
            <input type="number" name="tahun_terbit" id="add_tahun_terbit" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="<?= old('tahun_terbit') ?>" required>
          </div>
          <div>
            <label for="add_isbn" class="block text-sm font-medium text-gray-700">ISBN</label>
            <input type="text" name="isbn" id="add_isbn" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="<?= old('isbn') ?>" required>
          </div>
          <div>
            <label for="add_stok" class="block text-sm font-medium text-gray-700">Stok</label>
            <input type="number" name="stok" id="add_stok" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="<?= old('stok') ?>" required>
          </div>
          <div class="col-span-2">
            <label for="add_hipotesis" class="block text-sm font-medium text-gray-700">Hipotesis</label>
            <textarea name="hipotesis" id="add_hipotesis" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" rows="3" required><?= old('hipotesis') ?></textarea>
          </div>
          <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700">Cover Buku</label>
            <input type="file" name="image" class="mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
          </div>
        </div>
        <div class="flex justify-end space-x-2 pt-5">
          <button type="button" onclick="closeAddModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">Batal</button>
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 shadow-sm hover:shadow-md transition-all">Simpan</button>
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
            <input type="text" name="judul_buku" id="edit_judul_buku" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
          </div>
          <div>
            <label for="edit_id_kategori" class="block text-sm font-medium text-gray-700">Kategori</label>
            <select name="id_kategori" id="edit_id_kategori" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
              <option value="">Pilih Kategori</option>
              <?php foreach($kategori as $k): ?>
                <option value="<?= $k['id_kategori'] ?>"><?= esc($k['nama_kategori']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="edit_pengarang" class="block text-sm font-medium text-gray-700">Pengarang</label>
            <input type="text" name="pengarang" id="edit_pengarang" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
          </div>
          <div>
            <label for="edit_penerbit" class="block text-sm font-medium text-gray-700">Penerbit</label>
            <input type="text" name="penerbit" id="edit_penerbit" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
          </div>
          <div>
            <label for="edit_tahun_terbit" class="block text-sm font-medium text-gray-700">Tahun Terbit</label>
            <input type="number" name="tahun_terbit" id="edit_tahun_terbit" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
          </div>
          <div>
            <label for="edit_isbn" class="block text-sm font-medium text-gray-700">ISBN</label>
            <input type="text" name="isbn" id="edit_isbn" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
          </div>
          <div>
            <label for="edit_stok" class="block text-sm font-medium text-gray-700">Stok</label>
            <input type="number" name="stok" id="edit_stok" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
          </div>
          <div class="col-span-2">
            <label for="edit_hipotesis" class="block text-sm font-medium text-gray-700">Hipotesis</label>
            <textarea name="hipotesis" id="edit_hipotesis" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" rows="3" required></textarea>
          </div>
          <div class="col-span-2">
          <label class="block text-sm font-medium text-gray-700">Ganti Cover Buku (Opsional)</label>
          <div class="flex items-center gap-4 mt-1">
            <img id="edit_current_image" src="" alt="Current Cover" class="w-16 h-20 object-cover rounded bg-gray-100">
            <input type="file" name="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
          </div>
        </div>
        </div>
        <div class="flex justify-end space-x-2 pt-5">
          <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">Batal</button>
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 shadow-sm hover:shadow-md transition-all">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

      // Populate form fields
      document.getElementById('edit_judul_buku').value = bookData.judul_buku;
      document.getElementById('edit_id_kategori').value = bookData.id_kategori;
      document.getElementById('edit_pengarang').value = bookData.pengarang;
      document.getElementById('edit_penerbit').value = bookData.penerbit;
      document.getElementById('edit_tahun_terbit').value = bookData.tahun_terbit;
      document.getElementById('edit_isbn').value = bookData.isbn;
      document.getElementById('edit_stok').value = bookData.stok;
      document.getElementById('edit_hipotesis').value = bookData.hipotesis;
      document.getElementById('edit_current_image').src = `<?= base_url('uploads/') ?>/${bookData.image}`;

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

      document.getElementById('modalKategori').textContent = getValue(element.dataset.kategori);
      document.getElementById('modalHipotesis').innerText = getValue(element.dataset.hipotesis);
      document.getElementById('modalImage').src = getValue(element.dataset.image);

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
    <?php if ($edit_id = session()->getFlashdata('show_edit_modal')): ?>
      openEditModal(<?= json_encode(array_values(array_filter($buku, fn($b) => $b['id'] == $edit_id))[0] ?? null) ?>);
    <?php endif; ?>

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
    <?php if (session()->getFlashdata('error') || session()->getFlashdata('error_upload')): ?>
      Toast.fire({
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

    // Filter
    const categoryFilter = document.getElementById('categoryFilter');
    const searchFilter = document.getElementById('searchFilter');

    function applyFilters() {
      const selectedCategory = categoryFilter.value;
      const searchQuery = searchFilter.value.toLowerCase().trim();
      const bookItems = document.querySelectorAll('.book-item');

      bookItems.forEach(item => {
        const itemCategory = item.dataset.category;
        const bookTitle = item.querySelector('h3').textContent.toLowerCase();
        const bookAuthor = item.querySelector('p').textContent.toLowerCase();

        const categoryMatch = (selectedCategory === 'all' || itemCategory === selectedCategory);
        const searchMatch = (searchQuery === '' || bookTitle.includes(searchQuery) || bookAuthor.includes(searchQuery));

        if (categoryMatch && searchMatch) {
          item.style.display = 'flex';
          setTimeout(() => item.classList.add('is-visible'), 10);
        } else {
          item.style.display = 'none';
          item.classList.remove('is-visible');
        }
      });
    }

    // Event Listeners untuk filter
    categoryFilter.addEventListener('change', applyFilters);
    searchFilter.addEventListener('input', applyFilters);




    // Sidebar Toggle
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const sidebarToggle = document.getElementById('sidebar-toggle');

    sidebarToggle.addEventListener('click', () => {
      document.documentElement.classList.toggle('sidebar-is-collapsed');

      const isCollapsed = document.documentElement.classList.contains('sidebar-is-collapsed');
      localStorage.setItem('sidebarCollapsed', isCollapsed);
    });

  </script>
</body>
</html