<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Data Booking</title>
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
    $current_page = 'booking'; // Set halaman aktif
    echo view('pages/admin/template/sidebar', ['current_page' => $current_page]);
  ?>

  <!-- Main content -->
  <div id="main-content" class="flex-1 flex flex-col ml-64 transition-all duration-300">
    <header class="bg-white shadow-sm p-4 flex justify-between items-center sticky top-0 z-30">
      <div class="flex items-center">
        <button id="sidebar-toggle" class="text-gray-500 hover:text-gray-700 focus:outline-none mr-4"><i class="fas fa-bars text-lg"></i></button>
        <h1 class="text-xl font-semibold">Data Booking</h1>
      </div>
      <?= view('pages/admin/template/header_user_profile'); ?>
    </header>

    <main class="p-6">
      <div class="w-full max-w-7xl mx-auto" id="bookingContent">
        <div class="card-item bg-white p-4 rounded-lg shadow-sm mb-6">
        <div class="flex justify-between items-center">
          <div>
            <h2 class="text-2xl font-bold text-green-600">Daftar Booking Buku</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola semua permintaan booking dari anggota.</p>
          </div>
        </div>
      </div>

        <!-- Placeholder untuk tabel data -->
        <div class="card-item overflow-x-auto bg-white rounded-lg shadow-sm">
        <table class="min-w-full text-sm text-left">
          <thead class="bg-slate-50 border-b border-gray-200 text-slate-600 uppercase text-xs">
            <tr>
              <th class="py-3 px-6 font-semibold">ID Booking</th>
              <th class="py-3 px-6 font-semibold">Nama Anggota</th>
              <th class="py-3 px-6 font-semibold">Tgl. Booking</th>
              <th class="py-3 px-6 font-semibold text-center">Jumlah Buku</th>
              <th class="py-3 px-6 font-semibold text-center">Status</th>
              <th class="py-3 px-6 font-semibold text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="text-gray-600">
            <?php if (empty($bookings)): ?>
              <tr>
                <td colspan="6" class="text-center py-10 text-gray-500">
                  <i class="fas fa-folder-open text-4xl mb-3"></i>
                  <p>Tidak ada data booking.</p>
                </td>
              </tr>
            <?php else: foreach($bookings as $booking): // Tambahkan card-item ke setiap baris tabel ?>
              <tr class="border-b border-gray-200 hover:bg-gray-50 align-middle">
                <td class="py-3 px-6 font-mono text-gray-500">#<?= esc($booking['id_booking']) ?></td>
                <td class="py-3 px-6 font-medium text-gray-800"><?= esc($booking['nama_user']) ?></td>
                <td class="py-3 px-6"><?= date('d M Y', strtotime($booking['tanggal_booking'])) ?></td>
                <td class="py-3 px-6 text-center"><?= esc($booking['jumlah_buku']) ?></td>
                <td class="py-3 px-6 text-center">
                  <?php
                    $statusClass = 'bg-gray-100 text-gray-800'; // Default
                    if ($booking['status'] == 'pending') $statusClass = 'bg-yellow-100 text-yellow-800';
                    if ($booking['status'] == 'disetujui') $statusClass = 'bg-green-100 text-green-800';
                    if ($booking['status'] == 'dibatalkan') $statusClass = 'bg-red-100 text-red-800';
                  ?>
                  <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $statusClass ?>">
                    <?= esc(ucfirst($booking['status'])) ?>
                  </span>
                </td> 
                <td class="py-3 px-6 text-center">
                  <div class="flex item-center justify-center gap-2">
                    <button onclick="showDetailModal(<?= esc($booking['id_booking']) ?>)" class="w-8 h-8 flex items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 transition-colors" title="Lihat Detail">
                      <i class="fas fa-eye text-sm"></i>
                    </button>
                    <?php if ($booking['status'] == 'dibooking'): ?>
                      <a href="<?= base_url('booking/approve/' . $booking['id_booking']) ?>" onclick="return confirm('Anda yakin ingin menyetujui booking ini? Aksi ini akan membuat transaksi peminjaman baru.')" class="w-8 h-8 flex items-center justify-center rounded-full text-green-500 hover:bg-green-100 transition-colors" title="Setujui Booking">
                        <i class="fas fa-check text-sm"></i>
                      </a>
                      <a href="<?= base_url('booking/cancel/' . $booking['id_booking']) ?>" onclick="return confirm('Anda yakin ingin membatalkan booking ini? Stok buku akan dikembalikan.')" class="w-8 h-8 flex items-center justify-center rounded-full text-red-500 hover:bg-red-100 transition-colors" title="Batalkan Booking">
                        <i class="fas fa-times text-sm"></i>
                      </a>
                    <?php else: ?>
                      <a href="<?= base_url('booking/delete/' . $booking['id_booking']) ?>" onclick="return confirm('Anda yakin ingin menghapus data booking ini secara permanen?')" class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:bg-red-100 hover:text-red-500 transition-colors" title="Hapus Data">
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

	<!-- Detail Modal -->
	<div id="detailBookingModal" class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center z-50 p-4">
		<div id="modalContent" class="bg-white rounded-lg shadow-2xl max-w-2xl w-full transform transition-all relative">
			<button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-800 w-10 h-10 rounded-full flex items-center justify-center hover:bg-gray-100 transition-colors z-20">
				<i class="fas fa-times fa-lg"></i>
			</button>

			<div class="flex flex-col">
				<div class="p-6">
					<h2 class="text-2xl font-bold text-gray-800 mb-2">Detail Booking</h2>
					<div class="border-l-4 border-green-500 pl-4 mb-4">
						<p class="text-gray-600"><span class="font-semibold">ID Booking:</span> <span id="detailBookingId"></span></p>
						<p class="text-gray-600"><span class="font-semibold">Nama Anggota:</span> <span id="detailBookingNama"></span></p>
						<p class="text-gray-600"><span class="font-semibold">Tanggal Booking:</span> <span id="detailBookingTgl"></span></p>
						<p class="text-gray-600"><span class="font-semibold">Status:</span> <span id="detailBookingStatus"></span></p>
					</div>

					<h3 class="text-xl font-semibold text-gray-700 mb-2">Daftar Buku</h3>
					<div class="overflow-x-auto">
						<table class="min-w-full text-sm text-left">
							<thead class="bg-gray-50 border-b border-gray-200 text-gray-600 uppercase text-xs">
								<tr>
									<th class="py-3 px-4 font-semibold">Judul Buku</th>
								</tr>
							</thead>
							<tbody class="text-gray-600" id="detailBookingList">

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
  </div>

  <script>
	async function showDetailModal(bookingId) {
		const modal = document.getElementById('detailBookingModal');
		const content = document.getElementById('modalContent');
		const listContainer = document.getElementById('detailBookingList');

		// Fetch detail booking dari controller
		const response = await fetch(`<?= base_url('booking/getDetailBooking/') ?>/${bookingId}`);
		const detailBooking = await response.json();

		// Populate detail booking ke modal
		document.getElementById('detailBookingId').innerText = bookingId;
		document.getElementById('detailBookingNama').innerText = detailBooking[0].nama_user;
		document.getElementById('detailBookingTgl').innerText = detailBooking[0].tanggal_booking;
		document.getElementById('detailBookingStatus').innerText = detailBooking[0].status;

		// Bersihkan isi list buku sebelum menambahkan yang baru
		listContainer.innerHTML = '';

		detailBooking.forEach(detail => {
			const row = document.createElement('tr');
			row.className = 'border-b border-gray-200 hover:bg-gray-50';
			row.innerHTML = `<td class="py-3 px-4">${detail.judul_buku}</td>`;
			listContainer.appendChild(row);
		});

		modal.classList.remove('hidden');
		modal.classList.add('flex');
	}

    function closeModal() {
        const modal = document.getElementById('detailBookingModal');
        const content = document.getElementById('modalContent');

        content.classList.add('opacity-0', 'transform', '-translate-y-10');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            content.classList.remove('opacity-0', 'transform', '-translate-y-10');
        }, 200);
    }

    document.getElementById('sidebar-toggle').addEventListener('click', () => {
      document.documentElement.classList.toggle('sidebar-is-collapsed');
      localStorage.setItem('sidebarCollapsed', document.documentElement.classList.contains('sidebar-is-collapsed'));
    });
  </script>
  <script>
    // Animasi staggered untuk kartu
    document.addEventListener('DOMContentLoaded', () => {
      const cards = document.querySelectorAll('#bookingContent .card-item');
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