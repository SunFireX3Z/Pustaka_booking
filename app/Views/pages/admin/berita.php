<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($title ?? 'Manajemen Berita') ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- ApexCharts -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- TinyMCE -->
  <script src="https://cdn.tiny.cloud/1/z55bit35ute03vwdycb5ouv49cjreozj63uv4o3gmhqdi7bk/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
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
    .tox-tinymce { border-radius: 0.375rem; }
    .sidebar-is-collapsed #sidebar { width: 4rem; }
    .sidebar-is-collapsed #sidebar .sidebar-text,
    .sidebar-is-collapsed #sidebar .sidebar-logo-text {
      display: none;
    }
    .sidebar-is-collapsed #main-content { margin-left: 4rem; }
    .sidebar-is-collapsed #sidebar .sidebar-menu-item {
      justify-content: center;
    }
    .sidebar-is-collapsed #sidebar .flex.items-center.gap-3.p-4 {
      justify-content: center;
      padding: 1rem;
    }
    /* Toggle Switch */
    .toggle-checkbox:checked { right: 0; border-color: #10B981; }
    .toggle-checkbox:checked + .toggle-label { background-color: #10B981; }
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
    $current_page = 'berita'; // Set halaman aktif
    echo view('pages/admin/template/sidebar', ['current_page' => $current_page]);
  ?>

  <!-- Main content -->
  <div id="main-content" class="flex-1 flex flex-col ml-64 transition-all duration-300">

    <!-- Header -->
    <header class="bg-white shadow-sm p-4 flex justify-between items-center sticky top-0 z-30">
      <div class="flex items-center">
        <button id="sidebar-toggle" class="text-gray-500 hover:text-gray-700 focus:outline-none mr-4"><i class="fas fa-bars text-lg"></i></button>
        <h1 class="text-xl font-semibold">Manajemen Berita</h1>
      </div>
      <?= view('pages/admin/template/header_user_profile'); ?>
    </header>

    <!-- Content -->
    <main class="p-6">
      <div class="w-full max-w-7xl mx-auto" id="beritaContent">
        <div class="card-item bg-white p-6 rounded-lg shadow-sm mb-6">
          <div class="flex justify-between items-center">
            <div>
              <h2 class="text-2xl font-bold text-green-600">Daftar Berita</h2>
              <p class="text-sm text-gray-500 mt-1">Kelola berita dan informasi terbaru.</p>
            </div>
            <button onclick="openAddModal()" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 flex items-center">
                <i class="fas fa-plus mr-2"></i> Tulis Berita
            </button>
          </div>
        </div>

        <?php if ($validation->getErrors()): ?>
          <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Terjadi Kesalahan!</strong>
            <ul class="mt-1 list-disc list-inside text-sm"><?= $validation->listErrors() ?></ul>
          </div>
        <?php endif; ?>

        <!-- Tabel Berita -->
        <div class="card-item bg-white rounded-lg shadow-sm overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thumbnail</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <?php if (empty($berita)): ?>
                <tr><td colspan="5" class="text-center py-10 text-gray-500">Belum ada berita.</td></tr>
              <?php else: foreach ($berita as $item): // Tambahkan card-item ke setiap baris tabel ?>
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <img src="<?= base_url('uploads/berita/' . $item['thumbnail']) ?>" alt="Thumbnail" class="w-24 h-16 object-cover rounded">
                  </td>
                  <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900"><?= esc($item['judul']) ?></div>
                    <div class="text-xs text-gray-500">Dibuat: <?= date('d M Y', strtotime($item['created_at'])) ?></div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= esc($item['nama_kategori']) ?></td>
                  <td class="px-6 py-4 whitespace-nowrap text-center">
                    <label for="toggle_<?= $item['id'] ?>" class="flex items-center cursor-pointer justify-center">
                      <div class="relative">
                        <input type="checkbox" id="toggle_<?= $item['id'] ?>" class="sr-only toggle-checkbox" 
                               onchange="toggleStatus(<?= $item['id'] ?>, this.checked)"
                               <?= $item['status'] == 'published' ? 'checked' : '' ?>>
                        <div class="block bg-gray-300 w-10 h-6 rounded-full toggle-label transition"></div>
                        <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition"></div>
                      </div>
                    </label>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end gap-2">
                        <button onclick="openEditModal(<?= htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8') ?>)" class="w-8 h-8 flex items-center justify-center rounded-full text-green-500 hover:bg-green-100 transition-colors" title="Edit">
                            <i class="fas fa-edit text-sm"></i>
                        </button>
                        <a href="<?= base_url('berita/delete/' . $item['id']) ?>" onclick="return confirm('Anda yakin ingin menghapus berita ini?')" class="w-8 h-8 flex items-center justify-center rounded-full text-red-500 hover:bg-red-100 transition-colors" title="Hapus">
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

  <!-- Modal Tambah/Edit Berita -->
  <div id="formModal" class="fixed inset-0 bg-black bg-opacity-60 hidden items-start justify-center z-50 overflow-y-auto py-10">
    <div class="bg-white rounded-lg shadow-lg max-w-4xl w-full p-6 relative">
      <button onclick="closeModal()" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-200">
        <i class="fas fa-times"></i>
      </button>
      <h2 id="modalTitle" class="text-xl font-bold mb-4 text-gray-800">Tulis Berita Baru</h2>
      <form id="beritaForm" action="" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="space-y-4">
          <div>
            <label for="judul" class="block text-sm font-medium text-gray-700">Judul Berita</label>
            <input type="text" name="judul" id="judul" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500" required>
          </div>
          <div>
            <label for="id_kategori_berita" class="block text-sm font-medium text-gray-700">Kategori</label>
            <select name="id_kategori_berita" id="id_kategori_berita" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500" required>
              <option value="">Pilih Kategori</option>
              <?php foreach($kategori_berita as $k): ?>
                <option value="<?= $k['id'] ?>"><?= esc($k['nama_kategori']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="isi_berita" class="block text-sm font-medium text-gray-700">Isi Berita</label>
            <textarea name="isi_berita" id="isi_berita" class="mt-1 w-full"></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Thumbnail</label>
            <div class="mt-1 flex items-center gap-4">
                <img id="thumbnail_preview" src="<?= base_url('image_assets/placeholder.png') ?>" alt="Preview" class="w-32 h-20 object-cover rounded bg-gray-100">
                <input type="file" name="thumbnail" id="thumbnail" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100" onchange="previewImage(event)">
            </div>
            <p class="text-xs text-gray-500 mt-1">Rekomendasi ukuran 1280x720. Maks 2MB.</p>
          </div>
          <div class="flex items-center">
            <input type="checkbox" name="status" id="status" value="published" class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
            <label for="status" class="ml-2 block text-sm text-gray-900">Publikasikan Berita</p>
          </div>
        </div>
        <div class="flex justify-end space-x-2 pt-5">
          <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Batal</button>
          <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    // Inisialisasi TinyMCE
    tinymce.init({
      selector: 'textarea#isi_berita',
      plugins: 'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste code help wordcount',
      toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | image media | help',
      height: 400,
      // Konfigurasi untuk upload gambar via TinyMCE (jika diperlukan)
      // images_upload_url: 'postAcceptor.php',
      // automatic_uploads: true,
    });

    async function toggleStatus(id, isChecked) {
        const status = isChecked ? 'published' : 'draft';
        const csrfTokenName = '<?= csrf_token() ?>';
        const csrfTokenValue = '<?= csrf_hash() ?>';

        try {
            const response = await fetch(`<?= base_url('berita/toggle-status/') ?>/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfTokenValue
                },
                body: JSON.stringify({ status: status, [csrfTokenName]: csrfTokenValue })
            });
            const result = await response.json();
            if (result.success) {
                Toast.fire({ icon: 'success', title: `Berita diubah ke ${result.status}` });
            } else {
                Toast.fire({ icon: 'error', title: 'Gagal mengubah status.' });
            }
        } catch (error) {
            Toast.fire({ icon: 'error', title: 'Terjadi kesalahan jaringan.' });
        }
    }

    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('thumbnail_preview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };

    function openAddModal() {
      document.getElementById('beritaForm').reset();
      document.getElementById('beritaForm').action = "<?= base_url('berita') ?>";
      document.getElementById('modalTitle').innerText = 'Tulis Berita Baru';
      tinymce.get('isi_berita').setContent('');
      document.getElementById('thumbnail_preview').src = "<?= base_url('image_assets/placeholder.png') ?>";
      document.getElementById('status').checked = false;
      document.getElementById('formModal').classList.remove('hidden');
      document.getElementById('formModal').classList.add('flex');
    }

    function openEditModal(data) {
      document.getElementById('beritaForm').reset();
      document.getElementById('beritaForm').action = `<?= base_url('berita/update/') ?>/${data.id}`;
      document.getElementById('modalTitle').innerText = 'Edit Berita';
      
      document.getElementById('judul').value = data.judul;
      document.getElementById('id_kategori_berita').value = data.id_kategori_berita;
      tinymce.get('isi_berita').setContent(data.isi_berita);
      document.getElementById('thumbnail_preview').src = `<?= base_url('uploads/berita/') ?>/${data.thumbnail}`;
      document.getElementById('status').checked = (data.status === 'published');

      document.getElementById('formModal').classList.remove('hidden');
      document.getElementById('formModal').classList.add('flex');
    }

    function closeModal() {
      document.getElementById('formModal').classList.add('hidden');
      document.getElementById('formModal').classList.remove('flex');
    }

    // Sidebar Toggle
    document.getElementById('sidebar-toggle').addEventListener('click', () => {
      document.documentElement.classList.toggle('sidebar-is-collapsed');
      localStorage.setItem('sidebarCollapsed', document.documentElement.classList.contains('sidebar-is-collapsed'));
    });

    // Notifikasi Toast
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
    });

    <?php if (session()->getFlashdata('success')): ?>
      Toast.fire({ icon: 'success', title: '<?= session()->getFlashdata('success') ?>' })
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
      Toast.fire({ icon: 'error', title: '<?= session()->getFlashdata('error') ?>' })
    <?php endif; ?>

    // Jika ada error validasi, buka kembali modal
    <?php if (session()->has('errors')): ?>
        <?php if(strpos(previous_url(), 'update')): ?>
            // Logika untuk membuka modal edit jika diperlukan
        <?php else: ?>
            openAddModal();
        <?php endif; ?>
    <?php endif; ?>

    // Animasi staggered untuk kartu
    document.addEventListener('DOMContentLoaded', () => {
      const cards = document.querySelectorAll('#beritaContent .card-item');
      cards.forEach((card, index) => {
        setTimeout(() => {
          card.classList.add('is-visible');
        }, index * 100); // Delay 100ms untuk setiap kartu
      });
    });
  </script>
</body>
</html>