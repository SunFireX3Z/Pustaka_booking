<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar Anggota</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- ApexCharts -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
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
    $current_page = 'anggota'; // Set halaman aktif
    echo view('pages/admin/template/sidebar', ['current_page' => $current_page]);
    // Pisahkan data admin dan member
    $admins = array_filter($anggota, fn($user) => $user['role_id'] == 1);
    $members = array_filter($anggota, fn($user) => $user['role_id'] == 2);

    // Ambil daftar kelas unik untuk filter
    $unique_kelas = !empty($members) ? array_unique(array_column($members, 'kelas')) : [];
    sort($unique_kelas);
  ?>

  <!-- Main content -->
  <div id="main-content" class="flex-1 flex flex-col ml-64 transition-all duration-300">

    <!-- Header bar -->
    <header class="bg-white shadow-sm p-4 flex justify-between items-center sticky top-0 z-30">
      <div class="flex items-center">
        <button id="sidebar-toggle" class="text-gray-500 hover:text-gray-700 focus:outline-none mr-4"><i class="fas fa-bars text-lg"></i></button>
        <h1 class="text-xl font-semibold">Daftar Anggota</h1>
      </div>
      <?= view('pages/admin/template/header_user_profile'); ?>
    </header>

    <!-- Content -->
    <main class="p-6">
      <div class="w-full max-w-7xl mx-auto">
        <div class="card-item relative bg-gradient-to-r from-green-500 to-blue-500 text-white p-6 rounded-lg shadow-lg mb-8 overflow-hidden">
          <div class="absolute -right-10 -bottom-10">
              <i class="fas fa-users-cog text-white/10 text-9xl transform -rotate-12"></i>
          </div>
          <div class="relative z-10">
            <h2 class="text-3xl font-bold">Manajemen Anggota</h2>
            <p class="mt-1 text-green-100">Kelola, cari, dan lihat semua pengguna yang terdaftar di sistem.</p>
            <div class="flex flex-col sm:flex-row items-center gap-2 mt-4">
              <div class="relative w-full sm:w-64">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3"><i class="fas fa-search text-gray-400"></i></span>
                <input type="text" id="searchInput" placeholder="Cari nama, email, NISN..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-full shadow-sm focus:outline-none focus:ring-2 focus:ring-white/50 text-gray-800">
              </div>
              <select id="classFilter" class="w-full sm:w-56 px-4 py-2 border border-gray-300 rounded-full shadow-sm focus:outline-none focus:ring-2 focus:ring-white/50 text-gray-800">
                <option value="all">Semua Kelas</option>
                <?php foreach($unique_kelas as $kelas): ?>
                  <option value="<?= esc($kelas) ?>"><?= esc($kelas) ?></option>
                <?php endforeach; ?>
              </select>
              <button onclick="openModal('add')" class="flex-shrink-0 w-full sm:w-auto bg-white text-green-600 font-semibold px-5 py-2 rounded-full hover:bg-green-50 flex items-center justify-center shadow-md hover:shadow-lg transition-all">
                  <i class="fas fa-plus mr-2"></i> Tambah Anggota
              </button>
            </div>
          </div>
        </div>

        <!-- Kartu Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div class="card-item bg-white p-5 rounded-lg shadow-md flex items-center space-x-4 border-l-4 border-blue-500">
            <div class="bg-blue-100 p-3 rounded-full"><i class="fas fa-users fa-lg text-blue-600"></i></div>
            <div>
              <p class="text-gray-500 text-sm font-medium">Total Anggota</p>
              <p class="text-2xl font-bold text-gray-800"><?= count($anggota) ?></p>
            </div>
          </div>
          <div class="card-item bg-white p-5 rounded-lg shadow-md flex items-center space-x-4 border-l-4 border-purple-500">
            <div class="bg-purple-100 p-3 rounded-full"><i class="fas fa-user-shield fa-lg text-purple-600"></i></div>
            <div>
              <p class="text-gray-500 text-sm font-medium">Jumlah Admin</p>
              <p class="text-2xl font-bold text-gray-800"><?= count($admins) ?></p>
            </div>
          </div>
          <div class="card-item bg-white p-5 rounded-lg shadow-md flex items-center space-x-4 border-l-4 border-green-500">
            <div class="bg-green-100 p-3 rounded-full"><i class="fas fa-user-graduate fa-lg text-green-600"></i></div>
            <div>
              <p class="text-gray-500 text-sm font-medium">Jumlah Member</p>
              <p class="text-2xl font-bold text-gray-800"><?= count($members) ?></p>
            </div>
          </div>
        </div>

        <!-- Tabel Administrator -->
        <div class="mb-8">
        <h3 class="text-lg font-semibold text-gray-700 mb-3">Tabel Administrator</h3>
        <div class="overflow-x-auto bg-white rounded-lg shadow-sm">
          <table class="min-w-full text-sm text-left">
            <thead class="bg-slate-50 border-b border-gray-200 text-slate-600 uppercase text-xs">
              <tr>
                <th class="py-3 px-6 font-semibold">Admin</th>
                <th class="py-3 px-6 font-semibold">Peran</th>
                <th class="py-3 px-6 font-semibold">Tanggal Bergabung</th>
                <th class="py-3 px-6 font-semibold text-center">Aksi</th>
              </tr>
            </thead>
            <tbody class="text-gray-600" id="adminTableBody">
              <?php if (empty($admins)): ?>
                <tr><td colspan="4" class="text-center py-4 text-gray-500">Tidak ada data admin.</td></tr>
              <?php else: foreach($admins as $u): ?>
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
                  <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                    <?= esc(ucfirst($u['role'])) ?>
                  </span>
                </td>
                <td class="py-3 px-6"><?= date('d M Y', strtotime($u['tanggal_input'])) ?></td>
                <td class="py-3 px-6 text-center">
                  <div class="flex item-center justify-center gap-2">
                    <button onclick="openModal('edit', <?= htmlspecialchars(json_encode($u), ENT_QUOTES, 'UTF-8') ?>)" class="w-8 h-8 flex items-center justify-center rounded-full text-green-500 hover:bg-green-100 transition-colors" title="Edit">
                      <i class="fas fa-edit text-sm"></i>
                    </button>
                    <a href="<?= base_url('anggota/delete/' . $u['id']) ?>" class="w-8 h-8 flex items-center justify-center rounded-full text-red-500 hover:bg-red-100 transition-colors" onclick="return confirm('Apakah Anda yakin ingin menghapus anggota ini?')" title="Hapus">
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

        <!-- Tabel Member -->
        <div>
        <h3 class="text-lg font-semibold text-gray-700 mb-3">Tabel Member</h3>
        <div class="overflow-x-auto bg-white rounded-lg shadow-sm">
        <table class="min-w-full text-sm text-left">
          <thead class="bg-slate-50 border-b border-gray-200 text-slate-600 uppercase text-xs">
            <tr>
              <th class="py-3 px-6 font-semibold">Anggota</th>
              <th class="py-3 px-6 font-semibold">NISN</th>
              <th class="py-3 px-6 font-semibold">NIK</th>
              <th class="py-3 px-6 font-semibold">Kelas</th>
              <th class="py-3 px-6 font-semibold">Jenis Kelamin</th>
              <th class="py-3 px-6 font-semibold">No. HP</th>
              <th class="py-3 px-6 font-semibold">Tanggal Bergabung</th>
              <th class="py-3 px-6 font-semibold text-center">Aksi</th> 
            </tr>
          </thead>
          <tbody class="text-gray-600" id="anggotaTableBody">
            <?php if (empty($members)): ?>
              <tr><td colspan="8" class="text-center py-4 text-gray-500">Tidak ada data member.</td></tr>
            <?php else: foreach($members as $u): ?>
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
              <td class="py-3 px-6 font-medium text-gray-700"><?= esc($u['nisn']) ?></td>
              <td class="py-3 px-6 font-medium text-gray-700"><?= esc($u['nik'] ?? '-') ?></td>
              <td class="py-3 px-6"><?= esc($u['kelas']) ?></td>
              <td class="py-3 px-6"><?= esc($u['jenis_kelamin'] ?? '-') ?></td>
              <td class="py-3 px-6"><?= esc($u['no_hp'] ?? '-') ?></td>
              <td class="py-3 px-6"><?= date('d M Y', strtotime($u['tanggal_input'])) ?></td>
              <td class="py-3 px-6 text-center">
                <div class="flex item-center justify-center gap-2">
                  <button onclick="openModal('edit', <?= htmlspecialchars(json_encode($u), ENT_QUOTES, 'UTF-8') ?>)" class="w-8 h-8 flex items-center justify-center rounded-full text-green-500 hover:bg-green-100 transition-colors" title="Edit">
                    <i class="fas fa-edit text-sm"></i>
                  </button>
                  <a href="<?= base_url('anggota/delete/' . $u['id']) ?>" class="w-8 h-8 flex items-center justify-center rounded-full text-red-500 hover:bg-red-100 transition-colors" onclick="return confirm('Apakah Anda yakin ingin menghapus anggota ini?')" title="Hapus">
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
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-3">
          <div>
            <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
            <input type="text" name="nama" id="nama" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" required>
          </div>
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" required>
          </div>
          <div class="school-field">
            <label for="nis" class="block text-sm font-medium text-gray-700">NIS</label>
            <input type="text" name="nis" id="nis" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" placeholder="Contoh: 12345">
          </div>
          <div class="school-field">
            <label for="nisn" class="block text-sm font-medium text-gray-700">NISN</label>
            <input type="text" name="nisn" id="nisn" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" placeholder="Contoh: 20251234">
          </div>
          <div class="school-field">
            <label for="kelas" class="block text-sm font-medium text-gray-700">Kelas</label>
            <input type="text" name="kelas" id="kelas" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" placeholder="Contoh: XI RPL 1">
          </div>
          <div class="school-field">
            <label for="jurusan" class="block text-sm font-medium text-gray-700">Jurusan</label>
            <input type="text" name="jurusan" id="jurusan" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" placeholder="Contoh: Rekayasa Perangkat Lunak">
          </div>
          <div class="school-field">
            <label for="no_hp" class="block text-sm font-medium text-gray-700">No. HP</label>
            <input type="tel" name="no_hp" id="no_hp" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" placeholder="Contoh: 08123456789">
          </div>
          <div class="school-field">
            <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
          </div>
          <div class="school-field">
            <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
            <select name="jenis_kelamin" id="jenis_kelamin" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
              <option value="">Pilih Jenis Kelamin</option>
              <option value="Laki-laki">Laki-laki</option>
              <option value="Perempuan">Perempuan</option>
            </select>
          </div>
          <div class="school-field">
            <label for="nik" class="block text-sm font-medium text-gray-700">NIK</label>
            <input type="text" name="nik" id="nik" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" placeholder="16 digit NIK">
          </div>
          <div id="password-field">
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" id="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
            <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password.</p>
          </div>
          <div>
            <label for="role_id" class="block text-sm font-medium text-gray-700">Peran</label>
            <select name="role_id" id="role_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" required>
              <option value="">Pilih Peran</option>
              <?php foreach($roles as $role): ?>
                <option value="<?= $role['id'] ?>"><?= esc(ucfirst($role['role'])) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Gambar Profil</label>
            <div class="mt-1 flex items-center space-x-4">
              <img id="image_preview" src="<?= base_url('uploads/default.png') ?>" alt="Preview" class="w-20 h-20 rounded-full object-cover">
              <label for="image_input" class="cursor-pointer bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <span>Ubah Gambar</span>
                <input id="image_input" name="image" type="file" class="sr-only" accept="image/*">
              </label>
            </div>
          </div>
        </div>
        <div class="flex justify-end mt-6 space-x-2">
          <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">Batal</button>
          <button type="submit" id="modalSubmitButton" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 shadow-sm">Simpan</button>
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
        <button type="button" id="cropButton" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Potong & Simpan</button>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
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
    <?php if (session()->getFlashdata('error')): ?>
      AnimatedToast.fire({
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

    const schoolFields = document.querySelectorAll('.school-field');
    const roleSelect = document.getElementById('role_id');

    // Fungsi untuk mengatur visibilitas field sekolah berdasarkan peran
    function toggleSchoolFields() {
      const selectedRole = roleSelect.value;
      // Asumsi role_id 1 = Admin, 2 = Member
      if (selectedRole == 1) {
        schoolFields.forEach(field => field.style.display = 'none');
      } else {
        schoolFields.forEach(field => field.style.display = 'block');
      }
    }

    // Tambahkan event listener ke dropdown peran
    roleSelect.addEventListener('change', toggleSchoolFields);

    function openModal(mode, data = null) {
      anggotaForm.reset();
      croppedImageInput.value = '';
      imagePreview.src = '<?= base_url('uploads/default.png') ?>';

      if (mode === 'add') {
        modalTitle.textContent = 'Tambah Anggota Baru';
        modalSubmitButton.textContent = 'Simpan';
        anggotaForm.action = '<?= base_url('anggota/create') ?>';
        passwordField.style.display = 'block';
        document.getElementById('password').setAttribute('required', 'required');
        // Saat menambah, panggil fungsi untuk menyesuaikan tampilan awal
        toggleSchoolFields();
      } else if (mode === 'edit' && data) {
        modalTitle.textContent = 'Edit Anggota';
        modalSubmitButton.textContent = 'Simpan Perubahan';
        anggotaForm.action = `<?= base_url('anggota/update/') ?>/${data.id}`;
        document.getElementById('nama').value = data.nama;
        document.getElementById('email').value = data.email;
        document.getElementById('nis').value = data.nis;
        document.getElementById('nisn').value = data.nisn;
        document.getElementById('kelas').value = data.kelas;
        document.getElementById('jurusan').value = data.jurusan;
        document.getElementById('no_hp').value = data.no_hp;
        document.getElementById('tanggal_lahir').value = data.tanggal_lahir;
        document.getElementById('jenis_kelamin').value = data.jenis_kelamin;
        document.getElementById('nik').value = data.nik;
        document.getElementById('role_id').value = data.role_id;
        imagePreview.src = `<?= base_url('uploads/') ?>/${data.image}`;
        passwordField.style.display = 'none';
        document.getElementById('password').removeAttribute('required');
        document.getElementById('password').placeholder = "Kosongkan jika tidak diubah";

        // Sembunyikan field sekolah jika role adalah admin (role_id = 1)
        // Panggil fungsi untuk menyesuaikan tampilan berdasarkan data yang ada
        toggleSchoolFields();
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
          document.getElementById('nis').value = '<?= old('nis') ?>';
          document.getElementById('nisn').value = '<?= old('nisn') ?>';
          document.getElementById('kelas').value = '<?= old('kelas') ?>';
          document.getElementById('jurusan').value = '<?= old('jurusan') ?>';
          document.getElementById('no_hp').value = '<?= old('no_hp') ?>';
          document.getElementById('tanggal_lahir').value = '<?= old('tanggal_lahir') ?>';
          document.getElementById('jenis_kelamin').value = '<?= old('jenis_kelamin') ?>';
          document.getElementById('nik').value = '<?= old('nik') ?>';
          document.getElementById('role_id').value = '<?= old('role_id') ?>';
        } else if (mode === 'edit') {
          const editId = '<?= session()->getFlashdata('edit_id') ?>';
          const editData = {
            id: editId,
            nama: '<?= old('nama') ?>',
            email: '<?= old('email') ?>',
            nis: '<?= old('nis') ?>',
            nisn: '<?= old('nisn') ?>',
            kelas: '<?= old('kelas') ?>',
            jurusan: '<?= old('jurusan') ?>',
            no_hp: '<?= old('no_hp') ?>',
            tanggal_lahir: '<?= old('tanggal_lahir') ?>',
            jenis_kelamin: '<?= old('jenis_kelamin') ?>',
            nik: '<?= old('nik') ?>',
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
    const sidebarToggle = document.getElementById('sidebar-toggle');

    sidebarToggle.addEventListener('click', () => {
      document.documentElement.classList.toggle('sidebar-is-collapsed');

      // Simpan status sidebar di localStorage
      localStorage.setItem('sidebarCollapsed', document.documentElement.classList.contains('sidebar-is-collapsed'));
    });

    // Fungsi Pencarian
    const searchInput = document.getElementById('searchInput');
    const classFilter = document.getElementById('classFilter');
    const adminTableBody = document.getElementById('adminTableBody');
    const memberTableBody = document.getElementById('anggotaTableBody');

    function applyFilters() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedClass = classFilter.value;

        // Filter Tabel Admin (hanya berdasarkan nama/email)
        const adminRows = adminTableBody.getElementsByTagName('tr');
        for (let row of adminRows) {
            const memberCell = row.cells[0];
            if (memberCell) {
                const textContent = (memberCell.textContent || memberCell.innerText).toLowerCase();
                row.style.display = textContent.includes(searchTerm) ? '' : 'none';
            }
        }

        // Filter Tabel Member (berdasarkan nama/email/NISN dan kelas)
        const memberRows = memberTableBody.getElementsByTagName('tr');
        for (let row of memberRows) {
            const memberCell = row.cells[0];
            const nisnCell = row.cells[1];
            const classCell = row.cells[2];

            if (memberCell && nisnCell && classCell) {
                const textContent = (memberCell.textContent || memberCell.innerText).toLowerCase();
                const nisnContent = (nisnCell.textContent || nisnCell.innerText).toLowerCase();
                const classContent = classCell.textContent || classCell.innerText;

                const searchMatch = textContent.includes(searchTerm) || nisnContent.includes(searchTerm);
                const classMatch = (selectedClass === 'all' || classContent === selectedClass);

                row.style.display = (searchMatch && classMatch) ? '' : 'none';
            }
        }
    }

    searchInput.addEventListener('keyup', applyFilters);
    classFilter.addEventListener('change', applyFilters);
  </script>
</body>
</html>