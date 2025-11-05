<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Data Denda</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- ApexCharts -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    $current_page = 'denda'; // Set halaman aktif
    echo view('pages/admin/template/sidebar', ['current_page' => $current_page]);
  ?>

  <!-- Main content -->
  <div id="main-content" class="flex-1 flex flex-col ml-64 transition-all duration-300">
    <header class="bg-white shadow-sm p-4 flex justify-between items-center sticky top-0 z-30">
      <div class="flex items-center">
        <button id="sidebar-toggle" class="text-gray-500 hover:text-gray-700 focus:outline-none mr-4"><i class="fas fa-bars text-lg"></i></button>
        <h1 class="text-xl font-semibold">Data Denda</h1>
      </div>
      <?= view('pages/admin/template/header_user_profile'); ?>
    </header>

    <main class="p-6">
      <div class="w-full max-w-7xl mx-auto" id="dendaContent">
        <div class="card-item bg-white p-4 rounded-lg shadow-sm mb-6">
        <div class="flex justify-between items-center">
          <div>
            <h2 class="text-2xl font-bold text-green-600">Daftar Denda Keterlambatan</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola semua denda yang belum dan sudah dibayar.</p>
          </div>
        </div>
      </div>

        <!-- Placeholder untuk tabel data -->
        <div class="card-item overflow-x-auto bg-white rounded-lg shadow-sm">
          <table class="min-w-full text-sm text-left">
            <thead class="bg-slate-50 border-b border-gray-200 text-slate-600 uppercase text-xs">
              <tr>
                <th class="py-3 px-6 font-semibold">ID Pinjam</th>
                <th class="py-3 px-6 font-semibold">Nama Anggota</th>
                <th class="py-3 px-6 font-semibold">Jumlah Denda</th>
                <th class="py-3 px-6 font-semibold">Keterangan</th>
                <th class="py-3 px-6 font-semibold text-center">Status</th>
                <th class="py-3 px-6 font-semibold text-center">Aksi</th>
              </tr>
            </thead>
            <tbody class="text-gray-600">
              <?php if (empty($denda)): ?>
                <tr>
                  <td colspan="6" class="text-center py-10 text-gray-500">
                    <i class="fas fa-check-circle text-4xl mb-3 text-green-400"></i>
                    <p>Tidak ada data denda. Semua pengembalian tepat waktu!</p>
                  </td>
                </tr>
              <?php else: foreach($denda as $item): ?>
                <tr class="border-b border-gray-200 hover:bg-gray-50 align-middle">
                  <td class="py-3 px-6 font-mono text-gray-500">#<?= esc($item['id_pinjam']) ?></td>
                  <td class="py-3 px-6 font-medium text-gray-800"><?= esc($item['nama_user']) ?></td>
                  <td class="py-3 px-6 font-semibold text-red-600">Rp <?= number_format($item['jumlah_denda'], 0, ',', '.') ?></td>
                  <td class="py-3 px-6 text-xs">
                    <div class="font-medium">Harus Kembali:</div>
                    <div class="text-gray-500"><?= date('d M Y', strtotime($item['tanggal_kembali'])) ?></div>
                    <div class="font-medium mt-1">Dikembalikan:</div>
                    <div class="text-gray-500"><?= date('d M Y', strtotime($item['tanggal_dikembalikan'])) ?></div>
                  </td>
                  <td class="py-3 px-6 text-center">
                    <?php
                      $statusClass = $item['status'] == 'belum bayar' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800';
                      $statusText = $item['status'] == 'belum bayar' ? 'Belum Lunas' : 'Lunas';
                      // Cek jika status sudah bayar DAN tanggal_bayar valid (bukan null atau '0000-00-00...')
                      if ($item['status'] == 'sudah bayar' && !empty($item['tanggal_bayar']) && strtotime($item['tanggal_bayar']) > 0) {
                          $formattedDate = date('d M Y, H:i', strtotime($item['tanggal_bayar']));
                          $statusText .= '<br><span class="text-xs font-normal text-green-700">' . $formattedDate . ' WIB</span>';
                      }
                    ?>
                    <span class="px-2 py-1 text-xs font-semibold leading-tight rounded-full <?= $statusClass ?>">
                      <?= $statusText // Menggunakan $statusText yang sudah mengandung HTML ?>
                    </span>
                  </td>
                  <td class="py-3 px-6 text-center">
                    <div class="flex items-center justify-center gap-2">
                      <?php if ($item['status'] == 'belum bayar'): ?>
                        <a href="<?= base_url('denda/bayar/' . $item['id_denda']) ?>" onclick="return confirm('Anda yakin ingin menandai denda ini sebagai Lunas?')" class="bg-green-500 text-white px-3 py-1 rounded-md hover:bg-green-600 text-xs font-semibold flex items-center">
                          <i class="fas fa-check mr-1"></i> Tandai Lunas
                        </a>
                        <span class="w-8 h-8 flex items-center justify-center rounded-full text-gray-300" title="Hapus setelah denda lunas">
                          <i class="fas fa-trash-alt text-sm"></i>
                        </span>
                      <?php else: ?>
                        <span class="text-gray-400 text-xs italic">Selesai</span>
                        <a href="<?= base_url('denda/delete/' . $item['id_denda']) ?>" onclick="return confirm('Anda yakin ingin menghapus data denda ini secara permanen? Aksi ini tidak bisa dibatalkan.')" class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:bg-red-100 hover:text-red-500 transition-colors" title="Hapus Data Denda">
                          <i class="fas fa-trash-alt text-sm"></i>
                        </a>
                      <?php endif; ?>
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

  <script>
    document.getElementById('sidebar-toggle').addEventListener('click', () => {
      document.documentElement.classList.toggle('sidebar-is-collapsed');
      localStorage.setItem('sidebarCollapsed', document.documentElement.classList.contains('sidebar-is-collapsed'));
    });
  </script>
  <script>
    // Animasi staggered untuk kartu
    document.addEventListener('DOMContentLoaded', () => {
      const cards = document.querySelectorAll('#dendaContent .card-item');
      cards.forEach((card, index) => {
        setTimeout(() => {
          card.classList.add('is-visible');
        }, index * 100); // Delay 100ms untuk setiap kartu
      });
    });

    // Notifikasi Toast dengan SweetAlert2
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