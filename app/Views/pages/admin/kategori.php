<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar Kategori</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .sidebar-is-collapsed #sidebar { width: 4rem; }
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
    $current_page = 'kategori';
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
        <h1 class="text-xl font-semibold">Daftar Kategori</h1>
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
      <!-- Kartu Statistik -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Total Kategori -->
        <div class="card-item bg-white p-5 rounded-lg shadow-md flex items-center space-x-4 border-l-4 border-blue-500">
          <div class="bg-blue-100 p-3 rounded-full">
            <i class="fas fa-tags fa-lg text-blue-600"></i>
          </div>
          <div>
            <p class="text-gray-500 text-sm font-medium">Total Kategori</p>
            <p class="text-2xl font-bold text-gray-800"><?= $total_kategori ?? 0 ?></p>
          </div>
        </div>
        <!-- Kategori Terpopuler -->
        <div class="card-item bg-white p-5 rounded-lg shadow-md flex items-center space-x-4 border-l-4 border-green-500">
          <div class="bg-green-100 p-3 rounded-full">
            <i class="fas fa-star fa-lg text-green-600"></i>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-gray-500 text-sm font-medium">Kategori Terpopuler</p>
            <p class="text-lg font-bold text-gray-800 truncate" title="<?= esc($kategori_populer['nama']) ?>"><?= esc($kategori_populer['nama']) ?></p>
          </div>
        </div>
      </div>

      <div class="card-item bg-white p-4 rounded-lg shadow-sm mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <div>
            <h2 class="text-2xl font-bold text-blue-600">Data Kategori Buku</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola semua kategori buku yang tersedia.</p>
          </div>
          <div class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <i class="fas fa-search text-gray-400"></i>
              </span>
              <input type="text" id="searchInput" placeholder="Cari nama kategori..." 
                     class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <button onclick="openModal('add')" class="flex-shrink-0 w-full sm:w-auto bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center justify-center">
              <i class="fas fa-plus mr-2"></i> Tambah Kategori
            </button>
          </div>
        </div>
      </div>

      <!-- Layout Kartu Kategori -->
      <div id="kategoriCardContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (empty($kategori)): ?>
          <div class="col-span-full text-center py-10 text-gray-500 bg-white rounded-lg shadow">
            Tidak ada data kategori untuk ditampilkan.
          </div>
        <?php else: foreach($kategori as $k): ?>
          <div class="kategori-card card-item bg-white rounded-lg shadow-md overflow-hidden flex h-36 transition-all duration-300 hover:shadow-xl hover:-translate-y-1" data-nama="<?= strtolower(esc($k['nama_kategori'])) ?>">
            <!-- Bagian Kiri untuk Gambar Latar -->
            <div class="w-1/3 relative bg-gray-100 bg-contain bg-center bg-no-repeat" style="background-image: url('<?= base_url('uploads/' . esc($k['random_cover'])) ?>');">
              <!-- Overlay gelap untuk keterbacaan jika diperlukan -->
              <div class="absolute inset-0 flex items-center justify-center" style="background-color: rgba(0,0,0,0.05)">
                <i class="fas fa-bookmark text-4xl text-white opacity-30"></i>
              </div>
            </div>

            <!-- Bagian Kanan untuk Konten -->
            <div class="w-2/3 p-4 flex flex-col justify-between bg-white">
              <div>
                <h3 class="font-bold text-gray-800 truncate" title="<?= esc($k['nama_kategori']) ?>">
                  <?= esc($k['nama_kategori']) ?>
                </h3>
                <p class="text-sm text-gray-600 mt-1">
                  <i class="fas fa-book-open text-xs mr-1"></i>
                  <?= $k['jumlah_buku'] ?> Buku
                </p>
              </div>
              <div class="flex justify-end items-center gap-2">
                <button onclick="openModal('edit', <?= htmlspecialchars(json_encode($k), ENT_QUOTES, 'UTF-8') ?>)" class="w-8 h-8 flex items-center justify-center rounded-full text-blue-500 hover:bg-blue-100 transition-colors" title="Edit">
                  <i class="fas fa-edit text-sm"></i>
                </button>
                <a href="<?= base_url('kategori/delete/' . $k['id_kategori']) ?>" class="w-8 h-8 flex items-center justify-center rounded-full text-red-500 hover:bg-red-100 transition-colors" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')" title="Hapus">
                  <i class="fas fa-trash text-sm"></i>
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; endif; ?>
      </div>
    </main>
  </div>

  <!-- Modal Form Kategori -->
  <div id="kategoriModal" class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center z-50 p-4">
    <div id="kategoriModalContent" class="bg-white rounded-lg shadow-xl max-w-lg w-full p-6 transform transition-all -translate-y-10 opacity-0">
      <div class="flex justify-between items-center border-b pb-3 mb-5">
        <h2 id="modalTitle" class="text-xl font-bold text-gray-800"></h2>
        <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors">
          <i class="fas fa-times"></i>
        </button>
      </div>
      
      <?php if (session()->getFlashdata('validation')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
          <strong class="font-bold">Terjadi Kesalahan!</strong>
          <ul class="mt-1 list-disc list-inside text-sm">
            <?php foreach (session('validation')->getErrors() as $error) : ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
          </ul>
        </div>
      <?php endif; ?>

      <form id="kategoriForm" action="" method="post">
        <?= csrf_field() ?>
        <div>
          <label for="nama_kategori" class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
          <input type="text" name="nama_kategori" id="nama_kategori" value="<?= old('nama_kategori') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
        </div>
        <div class="flex justify-end mt-6 space-x-2">
          <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">Batal</button>
          <button type="submit" id="modalSubmitButton" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 shadow-sm">Simpan</button>
        </div>
      </form>
    </div>
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

    // Animasi staggered untuk kartu
    document.addEventListener('DOMContentLoaded', () => {
      const cards = document.querySelectorAll('.card-item');
      cards.forEach((card, index) => {
        setTimeout(() => {
          card.classList.add('is-visible');
        }, index * 80); // Delay 80ms untuk setiap item
      });
    });

    // Logika Modal
    const modal = document.getElementById('kategoriModal');
    const modalContent = document.getElementById('kategoriModalContent');
    const modalTitle = document.getElementById('modalTitle');
    const modalSubmitButton = document.getElementById('modalSubmitButton');
    const kategoriForm = document.getElementById('kategoriForm');
    const namaKategoriInput = document.getElementById('nama_kategori');

    function openModal(mode, data = null) {
      // Reset form
      kategoriForm.reset();
      namaKategoriInput.value = '';

      if (mode === 'add') {
        modalTitle.textContent = 'Tambah Kategori Baru';
        modalSubmitButton.textContent = 'Simpan';
        kategoriForm.action = '<?= base_url('kategori/create') ?>';
      } else if (mode === 'edit' && data) {
        modalTitle.textContent = 'Edit Kategori';
        modalSubmitButton.textContent = 'Simpan Perubahan';
        kategoriForm.action = `<?= base_url('kategori/update/') ?>/${data.id_kategori}`;
        namaKategoriInput.value = data.nama_kategori;
      }

      modal.classList.remove('hidden');
      modal.classList.add('flex');
      setTimeout(() => {
        modalContent.classList.remove('-translate-y-10', 'opacity-0');
      }, 10);
    }

    function closeModal() {
      modalContent.classList.add('-translate-y-10', 'opacity-0');
      setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }, 200);
    }

    // Otomatis buka modal jika ada error validasi
    <?php if (session()->getFlashdata('show_modal')): ?>
      document.addEventListener('DOMContentLoaded', () => {
        const mode = '<?= session()->getFlashdata('show_modal') ?>';
        if (mode === 'add') {
          openModal('add');
          namaKategoriInput.value = '<?= old('nama_kategori') ?>';
        } else if (mode === 'edit') {
          const editId = '<?= session()->getFlashdata('edit_id') ?>';
          const editData = {
            id_kategori: editId,
            nama_kategori: '<?= old('nama_kategori') ?>'
          };
          openModal('edit', editData);
        }
      });
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

    // Fungsi Pencarian
    const searchInput = document.getElementById('searchInput');
    const cardContainer = document.getElementById('kategoriCardContainer');
    const cards = cardContainer.getElementsByClassName('kategori-card');

    searchInput.addEventListener('keyup', function() {
      const searchTerm = searchInput.value.toLowerCase();

      for (let i = 0; i < cards.length; i++) {
        const card = cards[i];
        const cardName = card.dataset.nama;
        if (cardName.includes(searchTerm)) {
          card.style.display = 'flex';
        } else {
          card.style.display = 'none';
        }
      }
    });
  </script>
</body>
</html>