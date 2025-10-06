<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar Anggota</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
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
    $current_page = 'anggota';
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
        <h1 class="text-xl font-semibold">Daftar Anggota</h1>
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
      <div class="card-item bg-white p-4 rounded-lg shadow-sm mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <div>
            <h2 class="text-2xl font-bold text-blue-600">Data Anggota Perpustakaan</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola dan lihat semua anggota yang terdaftar.</p>
          </div>
          <div class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <i class="fas fa-search text-gray-400"></i>
              </span>
              <input type="text" id="searchInput" placeholder="Cari nama atau email..." 
                     class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <button onclick="openModal('add')" class="flex-shrink-0 w-full sm:w-auto bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center justify-center">
              <i class="fas fa-plus mr-2"></i> Tambah Anggota
            </button>
          </div>
        </div>
      </div>

      <div class="overflow-x-auto bg-white rounded-lg shadow-sm">
        <table class="min-w-full text-sm text-left">
          <thead class="bg-slate-50 border-b border-gray-200 text-slate-600 uppercase text-xs">
            <tr>
              <th class="py-3 px-6 font-semibold">Anggota</th>
              <th class="py-3 px-6 font-semibold">Peran</th>
              <th class="py-3 px-6 font-semibold">Tanggal Bergabung</th>
              <th class="py-3 px-6 font-semibold text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="text-gray-600" id="anggotaTableBody">
            <?php foreach($anggota as $u): ?>
            <tr class="card-item border-b border-gray-200 hover:bg-gray-50 align-middle">
              <td class="py-3 px-6">
                <div class="flex items-center space-x-4">
                  <img src="<?= base_url('uploads/' . esc($u['image'])) ?>" alt="Foto <?= esc($u['nama']) ?>" class="w-10 h-10 rounded-full object-cover">
                  <div>
                    <div class="font-medium text-gray-800"><?= esc($u['nama']) ?></div>
                    <div class="text-xs text-gray-500"><?= esc($u['email']) ?></div>
                  </div>
                </div>
              </td>
              <td class="py-3 px-6">
                <?php
                  $roleClass = 'bg-gray-100 text-gray-800'; // Default
                  if ($u['role_id'] == 1) { // Administrator
                    $roleClass = 'bg-blue-100 text-blue-800';
                  } elseif ($u['role_id'] == 2) { // Petugas
                    $roleClass = 'bg-yellow-100 text-yellow-800';
                  } elseif ($u['role_id'] == 3) { // Member
                    $roleClass = 'bg-green-100 text-green-800';
                  }
                ?>
                <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $roleClass ?>">
                  <?= esc($u['role']) ?>
                </span>
              </td>
              <td class="py-3 px-6"><?= date('d M Y', strtotime($u['tanggal_input'])) ?></td>
              <td class="py-3 px-6 text-center">
                <div class="flex item-center justify-center gap-2">
                  <button onclick="openModal('edit', <?= htmlspecialchars(json_encode($u), ENT_QUOTES, 'UTF-8') ?>)" class="w-8 h-8 flex items-center justify-center rounded-full text-blue-500 hover:bg-blue-100 transition-colors" title="Edit">
                    <i class="fas fa-edit text-sm"></i>
                  </button>
                  <a href="<?= base_url('anggota/delete/' . $u['id']) ?>" class="w-8 h-8 flex items-center justify-center rounded-full text-red-500 hover:bg-red-100 transition-colors" onclick="return confirm('Apakah Anda yakin ingin menghapus anggota ini?')" title="Hapus">
                    <i class="fas fa-trash text-sm"></i>
                  </a>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>

  <!-- Modal Form Anggota -->
  <div id="anggotaModal" class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center z-50 p-4">
    <div id="anggotaModalContent" class="bg-white rounded-lg shadow-xl max-w-lg w-full p-6 transform transition-all -translate-y-10 opacity-0">
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

      <form id="anggotaForm" action="" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="cropped_image" id="cropped_image">
        <div class="space-y-4">
          <div>
            <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
            <input type="text" name="nama" id="nama" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
          </div>
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
          </div>
          <div id="password-field">
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" id="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
          </div>
          <div>
            <label for="role_id" class="block text-sm font-medium text-gray-700">Peran</label>
            <select name="role_id" id="role_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
              <option value="">Pilih Peran</option>
              <?php foreach($roles as $role): ?>
                <option value="<?= $role['id'] ?>"><?= esc($role['role']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Gambar Profil</label>
            <div class="mt-1 flex items-center space-x-4">
              <img id="image_preview" src="<?= base_url('uploads/default.jpg') ?>" alt="Preview" class="w-20 h-20 rounded-full object-cover">
              <label for="image_input" class="cursor-pointer bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <span>Ubah Gambar</span>
                <input id="image_input" name="image" type="file" class="sr-only" accept="image/*">
              </label>
            </div>
          </div>
        </div>
        <div class="flex justify-end mt-6 space-x-2">
          <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">Batal</button>
          <button type="submit" id="modalSubmitButton" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 shadow-sm">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Crop -->
  <div id="cropModal" class="fixed inset-0 bg-black bg-opacity-75 hidden items-center justify-center z-[60]">
    <div class="bg-white p-4 rounded-lg shadow-lg max-w-md w-full">
      <h3 class="text-lg font-medium mb-4">Potong Gambar</h3>
      <div>
        <img id="image_to_crop" src="" alt="Crop Preview">
      </div>
      <div class="flex justify-end space-x-2 mt-4">
        <button type="button" id="cancelCrop" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">Batal</button>
        <button type="button" id="cropButton" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Potong & Simpan</button>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
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
    <?php if (session()->getFlashdata('error')): ?>
      Toast.fire({
        icon: 'error',
        title: '<?= session()->getFlashdata('error') ?>'
      })
    <?php endif; ?>

    // Logika Modal
    const modal = document.getElementById('anggotaModal');
    const modalContent = document.getElementById('anggotaModalContent');
    const modalTitle = document.getElementById('modalTitle');
    const modalSubmitButton = document.getElementById('modalSubmitButton');
    const anggotaForm = document.getElementById('anggotaForm');
    const passwordField = document.getElementById('password-field');
    const imagePreview = document.getElementById('image_preview');
    const croppedImageInput = document.getElementById('cropped_image');

    function openModal(mode, data = null) {
      anggotaForm.reset();
      croppedImageInput.value = '';
      imagePreview.src = '<?= base_url('uploads/default.jpg') ?>';

      if (mode === 'add') {
        modalTitle.textContent = 'Tambah Anggota Baru';
        modalSubmitButton.textContent = 'Simpan';
        anggotaForm.action = '<?= base_url('anggota/create') ?>';
        passwordField.style.display = 'block';
        document.getElementById('password').setAttribute('required', 'required');
      } else if (mode === 'edit' && data) {
        modalTitle.textContent = 'Edit Anggota';
        modalSubmitButton.textContent = 'Simpan Perubahan';
        anggotaForm.action = `<?= base_url('anggota/update/') ?>/${data.id}`;
        document.getElementById('nama').value = data.nama;
        document.getElementById('email').value = data.email;
        document.getElementById('role_id').value = data.role_id;
        imagePreview.src = `<?= base_url('uploads/') ?>/${data.image}`;
        passwordField.style.display = 'none';
        document.getElementById('password').removeAttribute('required');
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
          document.getElementById('nama').value = '<?= old('nama') ?>';
          document.getElementById('email').value = '<?= old('email') ?>';
          document.getElementById('role_id').value = '<?= old('role_id') ?>';
        } else if (mode === 'edit') {
          const editId = '<?= session()->getFlashdata('edit_id') ?>';
          const editData = {
            id: editId,
            nama: '<?= old('nama') ?>',
            email: '<?= old('email') ?>',
            role_id: '<?= old('role_id') ?>',
            image: 'default.jpg' // Placeholder, gambar tidak di-repopulate
          };
          openModal('edit', editData);
        }
      });
    <?php endif; ?>

    // Logika CropperJS
    const imageInput = document.getElementById('image_input');
    const cropModal = document.getElementById('cropModal');
    const imageToCrop = document.getElementById('image_to_crop');
    const cropButton = document.getElementById('cropButton');
    const cancelCrop = document.getElementById('cancelCrop');
    let cropper;

    imageInput.addEventListener('change', function (e) {
      const files = e.target.files;
      if (files && files.length > 0) {
        const reader = new FileReader();
        reader.onload = function (event) {
          imageToCrop.src = event.target.result;
          cropModal.classList.remove('hidden');
          cropModal.classList.add('flex');
          
          if (cropper) {
            cropper.destroy();
          }

          cropper = new Cropper(imageToCrop, {
            aspectRatio: 1,
            viewMode: 1,
            autoCropArea: 0.9,
          });
        };
        reader.readAsDataURL(files[0]);
      }
    });

    cropButton.addEventListener('click', function () {
      const canvas = cropper.getCroppedCanvas({
        width: 400,
        height: 400,
      });
      const croppedDataUrl = canvas.toDataURL('image/jpeg');
      imagePreview.src = croppedDataUrl;
      croppedImageInput.value = croppedDataUrl;
      
      cropModal.classList.add('hidden');
      cropModal.classList.remove('flex');
      cropper.destroy();
    });

    cancelCrop.addEventListener('click', function() {
      cropModal.classList.add('hidden');
      cropModal.classList.remove('flex');
      if(cropper) cropper.destroy();
      imageInput.value = ''; // Reset file input
    });

    // Animasi staggered untuk kartu
    document.addEventListener('DOMContentLoaded', () => {
      const cards = document.querySelectorAll('.card-item');
      cards.forEach((card, index) => {
        setTimeout(() => {
          card.classList.add('is-visible');
        }, index * 80); // Delay 80ms untuk setiap item
      });
    });

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
    const tableBody = document.getElementById('anggotaTableBody');
    const tableRows = tableBody.getElementsByTagName('tr');

    searchInput.addEventListener('keyup', function() {
      const searchTerm = searchInput.value.toLowerCase();

      for (let i = 0; i < tableRows.length; i++) {
        const row = tableRows[i];
        const cells = row.getElementsByTagName('td');
        const memberCell = cells[0]; // Kolom 'Anggota'
        if (memberCell) {
          const textContent = memberCell.textContent || memberCell.innerText;
          row.style.display = textContent.toLowerCase().includes(searchTerm) ? '' : 'none';
        }
      }
    });
  </script>
</body>
</html>