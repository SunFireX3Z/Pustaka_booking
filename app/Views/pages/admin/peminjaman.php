<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Data Peminjaman</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .sidebar-is-collapsed #sidebar { width: 4rem; }
    .sidebar-is-collapsed #sidebar .sidebar-text,
    .sidebar-is-collapsed #sidebar .sidebar-logo-text { display: none; }
    .sidebar-is-collapsed #sidebar .sidebar-menu-title { text-align: center; }
    .sidebar-is-collapsed #sidebar .sidebar-menu-item i { margin-right: 0; }
    .sidebar-is-collapsed #main-content { margin-left: 4rem; }
    .sidebar-is-collapsed #sidebar .sidebar-menu-item { justify-content: center; }
     ::-webkit-scrollbar { width: 6px; height: 6px; }
     ::-webkit-scrollbar-track { background: transparent; }
     ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
     ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
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
    $current_page = 'peminjaman'; // Set halaman aktif
    echo view('pages/admin/template/sidebar', ['current_page' => $current_page]);
  ?>

  <!-- Main content -->
  <div id="main-content" class="flex-1 flex flex-col ml-64 transition-all duration-300">
    <header class="bg-white shadow-sm p-4 flex justify-between items-center sticky top-0 z-30">
      <div class="flex items-center">
        <button id="sidebar-toggle" class="text-gray-500 hover:text-gray-700 focus:outline-none mr-4"><i class="fas fa-bars text-lg"></i></button>
        <h1 class="text-xl font-semibold">Data Peminjaman</h1>
      </div>
      <?= view('pages/admin/template/header_user_profile'); ?>
    </header>

    <main class="p-6">
      <div class="w-full max-w-7xl mx-auto" id="peminjamanContent">
        <div class="card-item bg-white p-4 rounded-lg shadow-sm mb-6">
          <div class="flex justify-between items-center">
            <div>
              <h2 class="text-2xl font-bold text-green-600">Daftar Transaksi Peminjaman</h2>
              <p class="text-sm text-gray-500 mt-1">Kelola semua transaksi peminjaman yang sedang berjalan atau sudah selesai.</p>
            </div>
            <div class="flex items-center gap-2">
              <form action="<?= base_url('peminjaman') ?>" method="get" class="flex items-center gap-2">
                <label for="status" class="text-sm font-medium text-gray-700">Filter:</label>
                <select name="status" id="status" onchange="this.form.submit()" class="w-full sm:w-48 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                  <option value="semua" <?= ($selected_status ?? 'semua') == 'semua' ? 'selected' : '' ?>>Semua Status</option>
                  <option value="dipinjam" <?= ($selected_status ?? '') == 'dipinjam' ? 'selected' : '' ?>>Dipinjam</option>
                  <option value="terlambat" <?= ($selected_status ?? '') == 'terlambat' ? 'selected' : '' ?>>Terlambat</option>
                  <option value="kembali" <?= ($selected_status ?? '') == 'kembali' ? 'selected' : '' ?>>Kembali</option>
                </select>
              </form>
            </div>
          </div>
        </div>

        <div class="card-item overflow-x-auto bg-white rounded-lg shadow-sm">
          <table class="min-w-full text-sm text-left">
            <thead class="bg-slate-50 border-b border-gray-200 text-slate-600 uppercase text-xs">
              <tr>
                <th class="py-3 px-6 font-semibold">No</th>
                <th class="py-3 px-6 font-semibold">ID Pinjam</th>
                <th class="py-3 px-6 font-semibold">Nama Peminjam</th>
                <th class="py-3 px-6 font-semibold">Tgl Pinjam</th>
                <th class="py-3 px-6 font-semibold">Tgl Kembali</th>
                <th class="py-3 px-6 font-semibold text-center">Status</th>
                <th class="py-3 px-6 font-semibold">Denda</th>
                <th class="py-3 px-6 font-semibold text-center">Aksi</th>
              </tr>
            </thead>
            <tbody class="text-gray-600">
              <?php if (empty($peminjaman)): ?>
                <tr>
                  <td colspan="8" class="text-center py-10 text-gray-500">
                    <i class="fas fa-folder-open text-4xl mb-3 text-gray-400"></i>
                    <p>Belum ada data peminjaman.</p>
                  </td>
                </tr>
              <?php else: foreach($peminjaman as $item): ?>
                <tr class="border-b border-gray-200 hover:bg-gray-50 align-middle">
                  <td class="py-3 px-6 font-medium"><?= (($currentPage - 1) * $perPage) + (isset($i) ? ++$i : $i = 1) ?></td>
                  <td class="py-3 px-6 font-mono text-gray-500">#<?= esc($item['id_pinjam']) ?></td>
                  <td class="py-3 px-6 font-medium text-gray-800"><?= esc($item['nama_user']) ?></td>
                  <td class="py-3 px-6"><?= date('d M Y', strtotime($item['tanggal_pinjam'])) ?></td>
                  <td class="py-3 px-6"><?= date('d M Y', strtotime($item['tanggal_kembali'])) ?></td>
                  <td class="py-3 px-6 text-center">
                    <?php
                      $isTerlambat = $item['status'] == 'dipinjam' && strtotime(date('Y-m-d')) > strtotime($item['tanggal_kembali']);
                      if ($isTerlambat) {
                          $statusClass = 'bg-red-100 text-red-800';
                          $statusText = 'Terlambat';
                      } elseif ($item['status'] == 'dipinjam') {
                          $statusClass = 'bg-blue-100 text-blue-800';
                          $statusText = 'Dipinjam';
                      } else {
                          $statusClass = 'bg-green-100 text-green-800';
                          $statusText = 'Kembali';
                      }
                    ?>
                    <span class="px-2 py-1 font-semibold rounded-full <?= $statusClass ?>">
                      <?= esc($statusText) ?>
                    </span>
                  </td>
                  <td class="py-3 px-6 font-semibold">
                    <?php
                      $dendaToShow = $item['total_denda'];
                      $dendaClass = 'text-green-600';
                      // Jika terlambat dan belum dikembalikan, hitung estimasi denda berjalan
                      if ($isTerlambat) {
                          $tanggalKembali = new DateTime($item['tanggal_kembali']);
                          $hariIni = new DateTime(date('Y-m-d'));
                          $selisih = $hariIni->diff($tanggalKembali)->days;
                          $dendaToShow = $selisih * 1000; // Denda per hari
                          $dendaClass = 'text-orange-500 italic'; // Beri style berbeda untuk estimasi
                      } elseif ($item['total_denda'] > 0) {
                          $dendaClass = 'text-red-600';
                      }
                    ?>
                    <span class="<?= $dendaClass ?>">Rp <?= number_format($dendaToShow, 0, ',', '.') ?></span>
                  </td>
                  <td class="py-3 px-6 text-center">
                    <?php if ($item['status'] == 'dipinjam'): ?>
                      <a href="<?= base_url('peminjaman/return/' . $item['id_pinjam']) ?>" onclick="return confirm('Anda yakin ingin mengembalikan buku untuk transaksi ini?')" class="bg-green-500 text-white px-3 py-1 rounded-md hover:bg-green-600 text-xs font-semibold">
                        <i class="fas fa-undo-alt mr-1"></i> Kembalikan
                      </a>
                    <?php else: ?>
                      <span class="text-gray-400 text-xs italic">Selesai</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
        <!-- Paginasi -->
        <div class="card-item mt-6">
          <?= $pager->links('peminjaman', 'tailwind_pager') ?>
        </div>

      </div>
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.getElementById('sidebar-toggle').addEventListener('click', () => {
      document.documentElement.classList.toggle('sidebar-is-collapsed');
      localStorage.setItem('sidebarCollapsed', document.documentElement.classList.contains('sidebar-is-collapsed'));
    });

    document.addEventListener('DOMContentLoaded', () => {
      const cards = document.querySelectorAll('#peminjamanContent .card-item');
      cards.forEach((card, index) => {
        setTimeout(() => {
          card.classList.add('is-visible');
        }, index * 100);
      });
    });

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
  </script>
</body>
</html>