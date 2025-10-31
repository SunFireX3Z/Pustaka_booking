<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar Kategori</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- ApexCharts -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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

    /* Animasi untuk SweetAlert2 Toast */
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
  <script>
    if (localStorage.getItem('sidebarCollapsed') === 'true') {
      document.documentElement.classList.add('sidebar-is-collapsed');
    }
  </script>
</head>
<body class="bg-gray-100 flex">

  <?php
    $current_page = 'kategori'; // Set halaman aktif
    echo view('pages/admin/template/sidebar', ['current_page' => $current_page]);
  ?>

  <!-- Main content -->
  <div id="main-content" class="flex-1 flex flex-col ml-64 transition-all duration-300">

    <!-- Header bar -->
    <header class="bg-white shadow-sm p-4 flex justify-between items-center sticky top-0 z-30">
      <div class="flex items-center">
        <button id="sidebar-toggle" class="text-gray-500 hover:text-gray-700 focus:outline-none mr-4"><i class="fas fa-bars text-lg"></i></button>
        <h1 class="text-xl font-semibold">Daftar Kategori</h1>
      </div>
      <?= view('pages/admin/template/header_user_profile'); ?>
    </header>

    <!-- Content -->
    <main class="p-6">
      <div class="w-full max-w-7xl mx-auto">
        <!-- Kartu Statistik -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Total Kategori -->
        <div class="card-item bg-white p-5 rounded-lg shadow-md flex items-center space-x-4 border-l-4 border-green-500">
          <div class="bg-green-100 p-3 rounded-full">
            <i class="fas fa-tags fa-lg text-green-600"></i>
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
            <h2 class="text-2xl font-bold text-green-600">Data Kategori Buku</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola semua kategori buku yang tersedia.</p>
          </div>
          <div class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <i class="fas fa-search text-gray-400"></i>
              </span>
              <input type="text" id="searchInput" placeholder="Cari nama kategori..." 
                     class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
            </div>
            <button onclick="openModal('add')" class="flex-shrink-0 w-full sm:w-auto bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 flex items-center justify-center">
              <i class="fas fa-plus mr-2"></i> Tambah Kategori
            </button>
          </div>
        </div>
      </div>

      <!-- Tabel Kategori -->
      <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kategori</th>
              <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Buku</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody id="kategoriTableBody" class="bg-white divide-y divide-gray-200">
            <?php if (empty($kategori)): ?>
              <tr><td colspan="3" class="text-center py-10 text-gray-500">Belum ada kategori.</td></tr>
            <?php else: foreach ($kategori as $k): ?>
              <tr class="kategori-row card-item">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= esc($k['nama_kategori']) ?></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center"><?= esc($k['jumlah_buku']) ?></td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium" data-kategori-id="<?= $k['id_kategori'] ?>">
                  <div class="flex items-center justify-end gap-2">
                    <button onclick="openModal('edit', <?= htmlspecialchars(json_encode($k), ENT_QUOTES, 'UTF-8') ?>)" class="w-8 h-8 flex items-center justify-center rounded-full text-green-500 hover:bg-green-100 transition-colors" title="Edit">
                        <i class="fas fa-edit text-sm"></i>
                    </button>
                    <button onclick="confirmDelete('<?= base_url('kategori/delete/' . $k['id_kategori']) ?>')" class="w-8 h-8 flex items-center justify-center rounded-full text-red-500 hover:bg-red-100 transition-colors" title="Hapus">
                        <i class="fas fa-trash-alt text-sm"></i>
                    </button>
                  </div>
                </td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
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
          <input type="text" name="nama_kategori" id="nama_kategori" value="<?= old('nama_kategori') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500" required>
        </div>
        <div class="flex justify-end mt-6 space-x-2">
          <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">Batal</button>
          <button type="submit" id="modalSubmitButton" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 shadow-sm">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
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

    // Konfirmasi hapus dengan SweetAlert2
    function confirmDelete(url) {
      Swal.fire({
        title: 'Anda Yakin?',
        text: "Kategori ini akan dihapus secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        customClass: {
          popup: 'rounded-xl'
        }
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = url;
        }
      });
    }
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
          const editId = '<?= session()->getFlashdata('edit_id') ?? '' ?>';
          if (editId) {
            // Temukan tombol edit yang sesuai berdasarkan data-attribute
            const editButton = document.querySelector(`td[data-kategori-id="${editId}"] button[title="Edit"]`);
            if (editButton) {
              // Panggil fungsi openModal dengan data dari tombol tersebut
              editButton.click(); 
              // Timpa nama kategori dengan input lama dari sesi
              namaKategoriInput.value = '<?= old('nama_kategori') ?>';
            }
          }
        }
      });
    <?php endif; ?>

    // Sidebar Toggle
    const sidebarToggle = document.getElementById('sidebar-toggle');

    sidebarToggle.addEventListener('click', () => {
      document.documentElement.classList.toggle('sidebar-is-collapsed');
      localStorage.setItem('sidebarCollapsed', document.documentElement.classList.contains('sidebar-is-collapsed'));
    });

    // Fungsi Pencarian
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.getElementById('kategoriTableBody');
    const rows = tableBody.getElementsByClassName('kategori-row');

    searchInput.addEventListener('keyup', function() {
      const searchTerm = searchInput.value.toLowerCase();

      for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const categoryNameCell = row.getElementsByTagName('td')[0];
        if (categoryNameCell) {
          const categoryName = categoryNameCell.textContent || categoryNameCell.innerText;
          if (categoryName.toLowerCase().includes(searchTerm)) {
            row.style.display = '';
          } else {
            row.style.display = 'none';
          }
        }
      }
    });
  </script>
</body>
</html>