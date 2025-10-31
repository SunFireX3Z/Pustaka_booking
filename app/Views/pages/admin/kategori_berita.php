<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($title ?? 'Kategori Berita') ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- ApexCharts -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
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
    .sidebar-is-collapsed #sidebar { width: 4rem; }
    .sidebar-is-collapsed #sidebar .sidebar-text, .sidebar-is-collapsed #sidebar .sidebar-logo-text { display: none; }
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
    $current_page = 'kategori_berita'; // Set halaman aktif
    echo view('pages/admin/template/sidebar', ['current_page' => $current_page]);
  ?>

  <!-- Main content -->
  <div id="main-content" class="flex-1 flex flex-col ml-64 transition-all duration-300">

    <!-- Header -->
    <header class="bg-white shadow-sm p-4 flex justify-between items-center sticky top-0 z-30">
      <div class="flex items-center">
        <button id="sidebar-toggle" class="text-gray-500 hover:text-gray-700 focus:outline-none mr-4"><i class="fas fa-bars text-lg"></i></button>
        <h1 class="text-xl font-semibold">Kategori Berita</h1>
      </div>
      <?= view('pages/admin/template/header_user_profile'); ?>
    </header>

    <!-- Content -->
    <main class="p-6">
      <div class="w-full max-w-4xl mx-auto" id="kategoriBeritaContent">
        <div class="card-item bg-white p-6 rounded-lg shadow-sm mb-6">
          <div class="flex justify-between items-center">
            <div>
              <h2 class="text-2xl font-bold text-green-600">Daftar Kategori Berita</h2>
              <p class="text-sm text-gray-500 mt-1">Kelola semua kategori untuk berita.</p>
            </div>
            <button onclick="openModal('add')" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Kategori
            </button>
          </div>
        </div>

        <!-- Tabel Kategori -->
        <div class="card-item bg-white rounded-lg shadow-sm overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr class="card-item">
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Berita</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <?php if (empty($kategori_berita)): ?>
                <tr><td colspan="3" class="text-center py-10 text-gray-500">Belum ada kategori.</td></tr>
              <?php else: foreach ($kategori_berita as $k): // Tambahkan card-item ke setiap baris tabel ?>
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= esc($k['nama_kategori']) ?></td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center"><?= esc($k['jumlah_berita']) ?></td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end gap-2">
                        <button onclick="openModal('edit', <?= htmlspecialchars(json_encode($k), ENT_QUOTES, 'UTF-8') ?>)" class="w-8 h-8 flex items-center justify-center rounded-full text-green-500 hover:bg-green-100 transition-colors" title="Edit">
                            <i class="fas fa-edit text-sm"></i>
                        </button>
                        <a href="<?= base_url('kategori-berita/delete/' . $k['id']) ?>" onclick="return confirm('Anda yakin ingin menghapus kategori ini?')" class="w-8 h-8 flex items-center justify-center rounded-full text-red-500 hover:bg-red-100 transition-colors" title="Hapus">
                            <i class="fas fa-trash text-sm"></i>
                        </a>
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
    <div class="bg-white rounded-lg shadow-xl max-w-lg w-full p-6">
      <div class="flex justify-between items-center border-b pb-3 mb-5">
        <h2 id="modalTitle" class="text-xl font-bold text-gray-800"></h2>
        <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-200">
          <i class="fas fa-times"></i>
        </button>
      </div>
      
      <?php if (session()->getFlashdata('validation')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
          <strong class="font-bold">Terjadi Kesalahan!</strong>
          <ul class="mt-1 list-disc list-inside text-sm"><?= session('validation')->listErrors() ?></ul>
        </div>
      <?php endif; ?>

      <form id="kategoriForm" action="" method="post">
        <?= csrf_field() ?>
        <div>
          <label for="nama_kategori" class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
          <input type="text" name="nama_kategori" id="nama_kategori" value="<?= old('nama_kategori') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500" required>
        </div>
        <div class="flex justify-end mt-6 space-x-2">
          <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Batal</button>
          <button type="submit" id="modalSubmitButton" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });
    <?php if (session()->getFlashdata('success')): ?>
      Toast.fire({ icon: 'success', title: '<?= session()->getFlashdata('success') ?>' })
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
      Toast.fire({ icon: 'error', title: '<?= session()->getFlashdata('error') ?>' })
    <?php endif; ?>

    const modal = document.getElementById('kategoriModal');
    const modalTitle = document.getElementById('modalTitle');
    const kategoriForm = document.getElementById('kategoriForm');
    const namaKategoriInput = document.getElementById('nama_kategori');

    function openModal(mode, data = null) {
      kategoriForm.reset();
      namaKategoriInput.value = '';

      if (mode === 'add') {
        modalTitle.textContent = 'Tambah Kategori Baru';
        kategoriForm.action = '<?= base_url('kategori-berita/create') ?>';
      } else if (mode === 'edit' && data) {
        modalTitle.textContent = 'Edit Kategori';
        kategoriForm.action = `<?= base_url('kategori-berita/update/') ?>/${data.id}`;
        namaKategoriInput.value = data.nama_kategori;
      }
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeModal() {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    <?php if (session()->getFlashdata('show_modal')): ?>
      document.addEventListener('DOMContentLoaded', () => {
        const mode = '<?= session()->getFlashdata('show_modal') ?>';
        if (mode === 'add') {
          openModal('add');
        } else if (mode === 'edit') {
          const editId = '<?= session()->getFlashdata('edit_id') ?>';
          const editData = { id: editId, nama_kategori: '<?= old('nama_kategori') ?>' };
          openModal('edit', editData);
        }
      });
    <?php endif; ?>

    // Animasi staggered untuk kartu
    document.addEventListener('DOMContentLoaded', () => {
      const cards = document.querySelectorAll('#kategoriBeritaContent .card-item');
      cards.forEach((card, index) => {
        setTimeout(() => {
          card.classList.add('is-visible');
        }, index * 100); // Delay 100ms untuk setiap kartu
      });
    });

    document.getElementById('sidebar-toggle').addEventListener('click', () => {
      document.documentElement.classList.toggle('sidebar-is-collapsed');
      localStorage.setItem('sidebarCollapsed', document.documentElement.classList.contains('sidebar-is-collapsed'));
    });
  </script>
</body>
</html>