<?php
  // Set kelas aktif untuk menu sidebar
  $active_class = 'bg-slate-800 text-white border-l-4 border-green-500';
  $inactive_class = 'text-slate-400 hover:bg-slate-800 hover:text-white transition-colors duration-200 border-l-4 border-transparent';
?>
<style>
  #kategori-dropdown {
    max-height: 0; opacity: 0; overflow: hidden; transition: max-height 0.3s ease-out, opacity 0.3s ease-out;
  }
  #kategori-dropdown.open { max-height: 200px; opacity: 1; } /* Cukup besar untuk menampung item */
</style>

<!-- Sidebar -->
<aside id="sidebar" class="fixed top-0 left-0 flex h-screen w-64 flex-col bg-slate-900 text-gray-200 transition-all duration-300">
  <!-- Logo Wrapper -->
  <div class="flex items-center gap-3 p-4">
    <a href="<?= base_url('dashboard') ?>" class="flex items-center gap-3">
      <img src="<?= base_url('uploads/profile/' . esc($web_profile['logo'] ?? 'default_logo.png')) ?>" alt="Logo" class="h-10 w-auto object-contain">
      <div class="sidebar-logo-text">
        <span class="text-white font-bold text-lg block leading-tight"><?= esc($web_profile['nama_instansi'] ?? 'Nama Instansi') ?></span>
        <span class="text-slate-400 text-sm"><?= esc($web_profile['nama_aplikasi'] ?? 'App Perpustakaan') ?></span>
      </div>
    </a>
  </div>

  <!-- Menu -->
  <nav class="flex-1 space-y-4 p-4">
    <!-- Grup Menu Utama -->
    <div>
      <h3 class="sidebar-menu-title px-3 text-xs font-semibold uppercase text-slate-500 mb-2"><span class="sidebar-text">Utama</span></h3>
      <div class="flex flex-col space-y-1">
        <a href="<?= base_url('dashboard') ?>" class="sidebar-menu-item flex items-center gap-3 rounded-md px-3 py-2 <?= ($current_page ?? '') === 'dashboard' ? $active_class : $inactive_class ?>">
          <i class="fas fa-tachometer-alt w-5 text-center"></i> <span class="sidebar-text">Dashboard</span>
        </a>
        <a href="<?= base_url('profile-web') ?>" class="sidebar-menu-item flex items-center gap-3 rounded-md px-3 py-2 <?= ($current_page ?? '') === 'profile-web' ? $active_class : $inactive_class ?>">
          <i class="fas fa-cogs w-5 text-center"></i> <span class="sidebar-text">Profil Web</span>
        </a>
      </div>
    </div>

    <!-- Master Data -->
    <div>
      <h3 class="sidebar-menu-title px-3 text-xs font-semibold uppercase text-slate-500 mb-2"><span class="sidebar-text">Master Data</span></h3>
      <div class="flex flex-col space-y-1">
        <a href="<?= base_url('anggota') ?>" class="sidebar-menu-item flex items-center gap-3 rounded-md px-3 py-2 <?= ($current_page ?? '') === 'anggota' ? $active_class : $inactive_class ?>">
          <i class="fas fa-users w-5 text-center"></i> <span class="sidebar-text">Anggota</span>
        </a>
        <a href="<?= base_url('buku') ?>" class="sidebar-menu-item flex items-center gap-3 rounded-md px-3 py-2 <?= ($current_page ?? '') === 'buku' ? $active_class : $inactive_class ?>">
          <i class="fas fa-book w-5 text-center"></i> <span class="sidebar-text">Buku</span>
        </a>
        <!-- Dropdown Kategori -->
        <div>
          <?php
            $is_kategori_active = in_array(($current_page ?? ''), ['kategori', 'kategori_berita']);
          ?>
          <button id="kategori-dropdown-toggle" class="sidebar-menu-item flex items-center justify-between w-full gap-3 rounded-md px-3 py-2 <?= $is_kategori_active ? $active_class : $inactive_class ?>">
            <div class="flex items-center gap-3">
              <i class="fas fa-tags w-5 text-center"></i>
              <span class="sidebar-text">Kategori</span>
            </div>
            <i id="kategori-chevron" class="fas fa-chevron-down sidebar-text text-xs transition-transform duration-200 <?= $is_kategori_active ? 'rotate-180' : '' ?>"></i>
          </button>
          <div id="kategori-dropdown" class="sidebar-text mt-1 space-y-1 pl-8 <?= $is_kategori_active ? 'open' : '' ?>">
            <a href="<?= base_url('kategori') ?>" class="flex items-center rounded-md px-3 py-2 text-sm <?= ($current_page ?? '') === 'kategori' ? 'text-white font-semibold' : 'text-slate-400 hover:text-white' ?>"><i class="fas fa-book-open mr-2"></i> Kategori Buku</a>
            <a href="<?= base_url('kategori-berita') ?>" class="flex items-center rounded-md px-3 py-2 text-sm <?= ($current_page ?? '') === 'kategori_berita' ? 'text-white font-semibold' : 'text-slate-400 hover:text-white' ?>"><i class="fas fa-newspaper mr-2"></i> Kategori Berita</a>
          </div>
        </div>
        <a href="<?= base_url('berita') ?>" class="sidebar-menu-item flex items-center gap-3 rounded-md px-3 py-2 <?= ($current_page ?? '') === 'berita' ? $active_class : $inactive_class ?>">
          <i class="fas fa-newspaper w-5 text-center"></i> <span class="sidebar-text">Berita</span>
        </a>
      </div>
    </div>

    <!-- Transaksi & Laporan -->
    <div>
      <h3 class="sidebar-menu-title px-3 text-xs font-semibold uppercase text-slate-500 mb-2"><span class="sidebar-text">Transaksi & Laporan</span></h3>
      <div class="flex flex-col space-y-1">
        <a href="<?= base_url('peminjaman') ?>" class="sidebar-menu-item flex items-center gap-3 rounded-md px-3 py-2 <?= ($current_page ?? '') === 'peminjaman' ? $active_class : $inactive_class ?>">
          <i class="fas fa-exchange-alt w-5 text-center"></i> <span class="sidebar-text">Peminjaman</span>
        </a>
        <a href="<?= base_url('booking') ?>" class="sidebar-menu-item flex items-center gap-3 rounded-md px-3 py-2 <?= ($current_page ?? '') === 'booking' ? $active_class : $inactive_class ?>">
          <i class="fas fa-bookmark w-5 text-center"></i> <span class="sidebar-text">Booking</span>
        </a>
        <a href="<?= base_url('denda') ?>" class="sidebar-menu-item flex items-center gap-3 rounded-md px-3 py-2 <?= ($current_page ?? '') === 'denda' ? $active_class : $inactive_class ?>">
          <i class="fas fa-money-bill-wave w-5 text-center"></i> <span class="sidebar-text">Denda</span>
        </a>
        <a href="<?= base_url('laporan') ?>" class="sidebar-menu-item flex items-center gap-3 rounded-md px-3 py-2 <?= ($current_page ?? '') === 'laporan' ? $active_class : $inactive_class ?>">
          <i class="fas fa-file-alt w-5 text-center"></i> <span class="sidebar-text">Laporan</span>
        </a>
      </div>
    </div>
  </nav>

  <!-- Logout -->
  <div class="p-4">
    <a href="<?= base_url('logout') ?>" class="sidebar-menu-item flex items-center gap-3 rounded-md px-3 py-2 <?= $inactive_class ?>">
      <i class="fas fa-sign-out-alt w-5 text-center"></i> <span class="sidebar-text">Logout</span>
    </a>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const toggleButton = document.getElementById('kategori-dropdown-toggle');
      const dropdown = document.getElementById('kategori-dropdown');
      const chevron = document.getElementById('kategori-chevron');

      toggleButton.addEventListener('click', function() {
        dropdown.classList.toggle('open');
        chevron.classList.toggle('rotate-180');
      });

      // Pastikan dropdown terbuka jika salah satu sub-itemnya aktif saat halaman dimuat
      if (dropdown.classList.contains('open')) {
        chevron.classList.add('rotate-180');
      }
    });
  </script>
</aside>