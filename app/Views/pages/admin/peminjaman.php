<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Data Peminjaman</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- ApexCharts -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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

    /* Animasi untuk SweetAlert2 Toast */
    @keyframes toast-in-right {
      from { transform: translateX(100%); opacity: 0; }
      to { transform: translateX(0); opacity: 1; }
    }
    .swal2-show.swal2-toast { animation: toast-in-right 0.5s; }
    .swal2-hide.swal2-toast { animation: none; }
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
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <div>
            <h2 class="text-2xl font-bold text-green-600">Daftar Peminjaman Aktif</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola semua transaksi peminjaman buku oleh anggota.</p>
          </div>
          <div class="relative w-full sm:w-64">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
              <i class="fas fa-search text-gray-400"></i>
            </span>
            <input type="text" id="searchInput" placeholder="Cari ID atau Nama Anggota..." 
                   class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
          </div>
        </div>
      </div>

        <!-- Flash Messages Toast -->
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script>
        const AnimatedToast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 4000, timerProgressBar: true });
        <?php if (session()->getFlashdata('success')): ?>
          AnimatedToast.fire({ icon: 'success', title: '<?= session()->getFlashdata('success') ?>' })
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
          AnimatedToast.fire({ icon: 'error', title: '<?= session()->getFlashdata('error') ?>' })
        <?php endif; ?>
      </script>

        <!-- Tabel Data Peminjaman -->
        <div class="card-item overflow-x-auto bg-white rounded-lg shadow-sm">
        <table class="min-w-full text-sm text-left">
          <thead class="bg-slate-50 border-b border-gray-200 text-slate-600 uppercase text-xs">
            <tr>
              <th class="py-3 px-6 font-semibold">ID Pinjam</th>
              <th class="py-3 px-6 font-semibold">Nama Anggota</th>
              <th class="py-3 px-6 font-semibold">Tgl. Pinjam</th>
              <th class="py-3 px-6 font-semibold">Tgl. Kembali</th>
              <th class="py-3 px-6 font-semibold">Denda</th>
              <th class="py-3 px-6 font-semibold text-center">Jumlah Buku</th>
              <th class="py-3 px-6 font-semibold text-center">Status</th>
              <th class="py-3 px-6 font-semibold text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="text-gray-600" id="peminjamanTableBody">
            <?php if (empty($peminjaman)): ?>
              <tr>
                <td colspan="8" class="text-center py-10 text-gray-500">
                  <i class="fas fa-folder-open text-4xl mb-3"></i>
                  <p>Tidak ada data peminjaman.</p>
                </td>
              </tr>
            <?php else: foreach($peminjaman as $pinjam): // Tambahkan card-item ke setiap baris tabel ?>
              <tr class="border-b border-gray-200 hover:bg-gray-50 align-middle">
                <td class="py-3 px-6 font-mono text-gray-500">#<?= esc($pinjam['id_pinjam']) ?></td>
                <td class="py-3 px-6 font-medium text-gray-800"><?= esc($pinjam['nama_user']) ?></td>
                <td class="py-3 px-6"><?= date('d M Y', strtotime($pinjam['tanggal_pinjam'])) ?></td>
                <td class="py-3 px-6"><?= date('d M Y', strtotime($pinjam['tanggal_kembali'])) ?></td>
                <td class="py-3 px-6 text-red-600 font-medium">Rp <?= number_format($pinjam['total_denda'], 0, ',', '.') ?></td>
                <td class="py-3 px-6 text-center"><?= esc($pinjam['jumlah_buku']) ?></td>
                <td class="py-3 px-6 text-center">
                  <?php
                    $statusClass = ''; $statusText = '';
                    $isLate = strtotime(date('Y-m-d')) > strtotime($pinjam['tanggal_kembali']) && $pinjam['status'] == 'dipinjam';

                    if ($isLate) {
                        $statusClass = 'bg-red-100 text-red-800';
                        $statusText = 'Terlambat';
                    } elseif ($pinjam['status'] == 'dipinjam') { 
                        $statusClass = 'bg-yellow-100 text-yellow-800';
                        $statusText = 'Dipinjam';
                    } elseif ($pinjam['status'] == 'kembali') {
                        $statusClass = 'bg-green-100 text-green-800';
                        $statusText = 'Kembali';
                    }
                  ?>
                  <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $statusClass ?>">
                    <?= esc($statusText) ?>
                  </span>
                </td>
                <td class="py-3 px-6 text-center">
                  <div class="flex item-center justify-center gap-2">
                    <button onclick="showDetailModal(<?= $pinjam['id_pinjam'] ?>, '<?= esc($pinjam['nama_user'], 'js') ?>')" class="w-8 h-8 flex items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 transition-colors" title="Lihat Detail">
                      <i class="fas fa-eye text-sm"></i>
                    </button>
                    <?php if ($pinjam['status'] == 'dipinjam'): ?>
                      <a href="<?= base_url('peminjaman/return/' . $pinjam['id_pinjam']) ?>" onclick="return confirm('Anda yakin ingin memproses pengembalian untuk transaksi ini?')" class="w-8 h-8 flex items-center justify-center rounded-full text-green-500 hover:bg-green-100 transition-colors" title="Proses Pengembalian">
                        <i class="fas fa-undo-alt text-sm"></i>
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

  <!-- Modal Detail Peminjaman -->
  <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-2xl max-w-md w-full transform transition-all">
      <div class="flex justify-between items-center p-4 border-b">
        <h3 class="text-lg font-bold text-gray-800">Detail Peminjaman</h3>
        <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="p-6">
        <div class="mb-4">
          <p class="text-sm text-gray-500">ID Pinjam</p>
          <p id="modalIdPinjam" class="font-semibold text-gray-800"></p>
        </div>
        <div class="mb-6">
          <p class="text-sm text-gray-500">Nama Peminjam</p>
          <p id="modalNamaUser" class="font-semibold text-gray-800"></p>
        </div>
        <h4 class="font-semibold text-gray-700 mb-2">Buku yang Dipinjam:</h4>
        <ul id="modalBookList" class="list-disc list-inside text-gray-600 space-y-1">
          <!-- Daftar buku akan diisi oleh JavaScript -->
        </ul>
      </div>
    </div>
  </div>

  <script>
    const detailModal = document.getElementById('detailModal');

    async function showDetailModal(peminjamanId, namaUser) {
      // Populate info dasar
      document.getElementById('modalIdPinjam').textContent = `#${peminjamanId}`;
      document.getElementById('modalNamaUser').textContent = namaUser;
      const bookList = document.getElementById('modalBookList');
      bookList.innerHTML = '<li><i class="fas fa-spinner fa-spin mr-2"></i> Memuat...</li>';

      // Tampilkan modal
      detailModal.classList.remove('hidden');
      detailModal.classList.add('flex');

      // Fetch detail buku
      try {
        const response = await fetch(`<?= base_url('peminjaman/detail/') ?>/${peminjamanId}`);
        const books = await response.json();
        
        bookList.innerHTML = ''; // Kosongkan list
        if (books.length > 0) {
          books.forEach(book => {
            const li = document.createElement('li');
            li.textContent = book.judul_buku;
            bookList.appendChild(li);
          });
        } else {
          bookList.innerHTML = '<li>Tidak ada detail buku.</li>';
        }
      } catch (error) {
        bookList.innerHTML = '<li>Gagal memuat data buku.</li>';
      }
    }

    function closeModal() {
      detailModal.classList.add('hidden');
      detailModal.classList.remove('flex');
    }

    document.getElementById('sidebar-toggle').addEventListener('click', () => {
      document.documentElement.classList.toggle('sidebar-is-collapsed');
      localStorage.setItem('sidebarCollapsed', document.documentElement.classList.contains('sidebar-is-collapsed'));
    });

    // Fungsi Pencarian
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.getElementById('peminjamanTableBody');
    const tableRows = tableBody.getElementsByTagName('tr');
    const originalNoDataRow = tableBody.querySelector('td[colspan="8"]')?.parentElement.cloneNode(true);

    searchInput.addEventListener('keyup', function() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        let found = false;

        for (let i = 0; i < tableRows.length; i++) {
            const row = tableRows[i];
            // Pastikan baris ini bukan baris 'tidak ada data'
            if (row.cells.length < 2) continue;

            const idCell = row.cells[0];
            const nameCell = row.cells[1];

            if (idCell && nameCell) {
                const idText = (idCell.textContent || idCell.innerText).toLowerCase();
                const nameText = (nameCell.textContent || nameCell.innerText).toLowerCase();

                if (idText.includes(searchTerm) || nameText.includes(searchTerm)) {
                    row.style.display = '';
                    found = true;
                } else {
                    row.style.display = 'none';
                }
            }
        }

        // Handle pesan "tidak ada hasil"
        let noResultsRow = tableBody.querySelector('.no-results-row');
        if (!found && searchTerm !== '') {
            if (!noResultsRow) {
                tableBody.insertAdjacentHTML('beforeend', '<tr class="no-results-row"><td colspan="8" class="text-center py-10 text-gray-500">Data tidak ditemukan.</td></tr>');
            }
        } else if (noResultsRow) {
            noResultsRow.remove();
        }
    });
  </script>
  <script>
    // Animasi staggered untuk kartu
    document.addEventListener('DOMContentLoaded', () => {
      const cards = document.querySelectorAll('#peminjamanContent .card-item');
      cards.forEach((card, index) => {
        setTimeout(() => {
          card.classList.add('is-visible');
        }, index * 100); // Delay 100ms untuk setiap kartu
      });
    });
  </script>
</body>
</html>