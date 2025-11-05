<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= esc($title ?? 'Profil Website') ?></title>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .sidebar-is-collapsed #sidebar { width: 4rem; }
    .sidebar-is-collapsed #sidebar .sidebar-text,
    .sidebar-is-collapsed #sidebar .sidebar-logo-text { display: none; }
    .sidebar-is-collapsed #main-content { margin-left: 4rem; }
    .sidebar-is-collapsed #sidebar .sidebar-menu-item { justify-content: center; }
     ::-webkit-scrollbar { width: 6px; height: 6px; }
     ::-webkit-scrollbar-track { background: transparent; }
     ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
     ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
  </style>
  <script>
    if (localStorage.getItem('sidebarCollapsed') === 'true') {
      document.documentElement.classList.add('sidebar-is-collapsed');
    }
  </script>
</head>
<body class="bg-gray-100 flex">

  <?php
    $current_page = 'profile-web'; // Set halaman aktif
    echo view('pages/admin/template/sidebar', ['current_page' => $current_page]);
  ?>

  <!-- Main content -->
  <div id="main-content" class="flex-1 flex flex-col ml-64 transition-all duration-300">
    <header class="bg-white shadow-sm p-4 flex justify-between items-center sticky top-0 z-30">
      <div class="flex items-center">
        <button id="sidebar-toggle" class="text-gray-500 hover:text-gray-700 focus:outline-none mr-4"><i class="fas fa-bars text-lg"></i></button>
        <h1 class="text-xl font-semibold"><?= esc($title) ?></h1>
      </div>
      <?= view('pages/admin/template/header_user_profile'); ?>
    </header>

    <main class="p-6">
      <div class="w-full max-w-4xl mx-auto">
        <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
          <h2 class="text-2xl font-bold text-green-600">Pengaturan Profil Website</h2>
          <p class="text-sm text-gray-500 mt-1">Kelola informasi umum dan pengaturan aplikasi Anda.</p>
        </div>

        <?php if ($validation->getErrors()): ?>
          <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p class="font-bold">Terjadi Kesalahan Validasi:</p>
            <ul class="list-disc list-inside">
              <?php foreach ($validation->getErrors() as $error): ?>
                <li><?= esc($error) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <form action="<?= base_url('profile-web/update') ?>" method="post" enctype="multipart/form-data">
          <?= csrf_field() ?>
          <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              
              <!-- Kolom Kiri -->
              <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Informasi Instansi</h3>
                <div>
                  <label for="nama_instansi" class="block text-sm font-medium text-gray-700">Nama Instansi</label>
                  <input type="text" name="nama_instansi" id="nama_instansi" value="<?= esc($profile['nama_instansi'] ?? '') ?>" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                  <label for="nama_aplikasi" class="block text-sm font-medium text-gray-700">Nama Aplikasi</label>
                  <input type="text" name="nama_aplikasi" id="nama_aplikasi" value="<?= esc($profile['nama_aplikasi'] ?? '') ?>" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                  <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                  <textarea name="alamat" id="alamat" rows="3" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"><?= esc($profile['alamat'] ?? '') ?></textarea>
                </div>
                <div>
                  <label for="kabupaten_kota" class="block text-sm font-medium text-gray-700">Kabupaten/Kota</label>
                  <input type="text" name="kabupaten_kota" id="kabupaten_kota" value="<?= esc($profile['kabupaten_kota'] ?? '') ?>" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                  <label for="npwp" class="block text-sm font-medium text-gray-700">NPWP</label>
                  <input type="text" name="npwp" id="npwp" value="<?= esc($profile['npwp'] ?? '') ?>" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                  <label for="logo" class="block text-sm font-medium text-gray-700">Logo Aplikasi</label>
                  <div class="mt-2 flex items-center gap-4">
                    <img id="logo_preview" src="<?= base_url('uploads/profile/' . esc($profile['logo'] ?? 'default-logo.png')) ?>" alt="Logo" class="w-16 h-16 object-contain bg-gray-100 p-1 rounded-md border">
                    <input type="file" name="logo" id="logo" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100" onchange="previewFile(this, 'logo_preview')">
                  </div>
                  <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah logo. Rekomendasi: SVG atau PNG transparan.</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Banner Homepage Member</label>
                  <div class="mt-2 flex items-center gap-4">
                    <img id="banner_preview" src="<?= base_url('uploads/profile/' . ($profile['banner_image'] ?? 'default-banner.png')) ?>" alt="Banner Preview" class="w-48 h-24 object-cover rounded-md bg-gray-100 border">
                    <div>
                      <input type="file" name="banner_image" id="banner_image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100" onchange="previewFile(this, 'banner_preview')">
                      <p class="text-xs text-gray-500 mt-1">PNG, JPG, WEBP (MAX. 2MB). Rasio 21:9.</p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Kolom Kanan -->
              <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Penanggung Jawab & Pengelola</h3>
                <div>
                  <label for="nama_penanggung_jawab" class="block text-sm font-medium text-gray-700">Nama Penanggung Jawab</label>
                  <input type="text" name="nama_penanggung_jawab" id="nama_penanggung_jawab" value="<?= esc($profile['nama_penanggung_jawab'] ?? '') ?>" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                  <label for="jabatan_penanggung_jawab" class="block text-sm font-medium text-gray-700">Jabatan Penanggung Jawab</label>
                  <input type="text" name="jabatan_penanggung_jawab" id="jabatan_penanggung_jawab" value="<?= esc($profile['jabatan_penanggung_jawab'] ?? '') ?>" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                  <label for="nama_penandatangan_mou" class="block text-sm font-medium text-gray-700">Nama Penandatangan MOU</label>
                  <input type="text" name="nama_penandatangan_mou" id="nama_penandatangan_mou" value="<?= esc($profile['nama_penandatangan_mou'] ?? '') ?>" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                  <label for="jabatan_penandatangan_mou" class="block text-sm font-medium text-gray-700">Jabatan Penandatangan MOU</label>
                  <input type="text" name="jabatan_penandatangan_mou" id="jabatan_penandatangan_mou" value="<?= esc($profile['jabatan_penandatangan_mou'] ?? '') ?>" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                  <label for="nama_pengelola" class="block text-sm font-medium text-gray-700">Nama Pengelola (Admin)</label> 
                  <input type="text" id="nama_pengelola" value="<?= esc($admin_user['nama'] ?? 'Admin tidak ditemukan') ?>" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100 cursor-not-allowed" readonly>
                </div>
                <div>
                  <label for="no_hp_pengelola" class="block text-sm font-medium text-gray-700">Nomor HP/WA (Admin)</label>
                  <input type="text" id="no_hp_pengelola" value="<?= esc($admin_user['no_hp'] ?? '-') ?>" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100 cursor-not-allowed" readonly>
                </div>
                <div>
                  <label for="email_pengelola" class="block text-sm font-medium text-gray-700">E-Mail Pengelola (Admin)</label>
                  <input type="email" id="email_pengelola" value="<?= esc($admin_user['email'] ?? '-') ?>" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100 cursor-not-allowed" readonly>
                </div>
                <p class="text-xs text-gray-500 mt-2">
                  <i class="fas fa-info-circle mr-1"></i>
                  Informasi Pengelola diambil dari data pengguna dengan peran "Admin". Untuk mengubahnya, silakan edit melalui menu <a href="<?= base_url('anggota') ?>" class="text-green-600 hover:underline">Master Data > Anggota</a>.
                </p>
              </div>

              <!-- Pengaturan Peminjaman -->
              <div class="md:col-span-2 space-y-4">
                <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Pengaturan Peminjaman</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                  <div>
                    <label for="max_buku_pinjam" class="block text-sm font-medium text-gray-700">Jumlah Maksimal Buku Dipinjam</label>
                    <input type="number" name="max_buku_pinjam" id="max_buku_pinjam" value="<?= esc($profile['max_buku_pinjam'] ?? 3) ?>" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                  </div>
                  <div>
                    <label for="max_hari_pinjam" class="block text-sm font-medium text-gray-700">Jumlah Maksimal Hari Pinjam</label>
                    <input type="number" name="max_hari_pinjam" id="max_hari_pinjam" value="<?= esc($profile['max_hari_pinjam'] ?? 7) ?>" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                  </div>
                  <div>
                    <label for="denda_per_hari" class="block text-sm font-medium text-gray-700">Denda Keterlambatan (per hari)</label>
                    <input type="number" name="denda_per_hari" id="denda_per_hari" value="<?= esc($profile['denda_per_hari'] ?? 1000) ?>" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                  </div>
                </div>
              </div>

            </div>
            <div class="flex justify-end mt-8 pt-6 border-t">
              <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 shadow-sm flex items-center gap-2">
                <i class="fas fa-save"></i> Simpan Perubahan
              </button>
            </div>
          </div>
        </form>

      </div>
    </main>
  </div>

  <script>
    // Fungsi preview gambar generik
    function previewFile(input, previewId) {
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById(previewId).src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
  </script>
  <script>
    document.getElementById('sidebar-toggle').addEventListener('click', () => {
      document.documentElement.classList.toggle('sidebar-is-collapsed');
      localStorage.setItem('sidebarCollapsed', document.documentElement.classList.contains('sidebar-is-collapsed'));
    });

    // Konfigurasi Toast Notifikasi
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
  </script>
</body>
</html>