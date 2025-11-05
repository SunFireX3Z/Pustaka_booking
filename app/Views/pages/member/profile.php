<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title><?= esc($title ?? 'Profil Saya') ?> - Pustaka Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased">

    <?= view('pages/member/template/navbar', [
        'current_page' => 'profile', // 'profile' tidak ada di navbar, jadi tidak akan ada yang aktif
        'cart_count'   => $cart_count ?? 0,
        'keyword'      => $keyword ?? '',
        'web_profile'  => $web_profile ?? []
    ]) ?>

    <!-- Main Content Wrapper -->
    <!-- pt-16 dihapus karena navbar sudah memiliki class 'sticky' -->
    <div class="flex-1">
        <main class="container mx-auto p-6">
            <!-- Breadcrumb -->
            <div class="mb-6 text-sm">
                <a href="<?= base_url('member') ?>" class="text-indigo-600 hover:underline">Home</a>
                <span class="mx-2 text-slate-400">/</span>
                <span class="text-slate-500">Profil Saya</span>
            </div>

            <!-- User Info Card -->
            <div class="bg-white rounded-lg shadow-lg p-8 max-w-4xl mx-auto mb-8">
                <div class="flex flex-col md:flex-row items-center gap-8">
                    <div class="flex-shrink-0">
                        <img src="<?= base_url('uploads/' . esc($user['image'] ?? 'default.png')) ?>" alt="Foto Profil" class="w-32 h-32 rounded-full object-cover border-4 border-slate-200">
                    </div>
                    <div class="flex-grow text-center md:text-left">
                        <h1 class="text-3xl font-bold text-slate-800"><?= esc($user['nama']) ?></h1>
                        <p class="text-slate-500 mt-1"><?= esc($user['email']) ?></p>
                        <p class="text-sm text-slate-400 mt-2">Anggota sejak: <?= date('d F Y', strtotime($user['tanggal_input'])) ?></p>
                    </div>
                </div>
                <!-- Detail Info Tambahan -->
                <div class="border-t border-slate-200 mt-8 pt-6 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 text-sm">
                    <div class="flex items-center">
                        <i class="fas fa-id-card fa-fw w-6 text-slate-400 mr-3"></i>
                        <span class="text-slate-500 mr-2">NIK:</span>
                        <span class="font-semibold text-slate-700"><?= esc($user['nik'] ?? '-') ?></span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-phone fa-fw w-6 text-slate-400 mr-3"></i>
                        <span class="text-slate-500 mr-2">No. HP:</span>
                        <span class="font-semibold text-slate-700"><?= esc($user['no_hp'] ?? '-') ?></span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-venus-mars fa-fw w-6 text-slate-400 mr-3"></i>
                        <span class="text-slate-500 mr-2">Jenis Kelamin:</span>
                        <span class="font-semibold text-slate-700"><?= esc($user['jenis_kelamin'] ?? '-') ?></span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-calendar-alt fa-fw w-6 text-slate-400 mr-3"></i>
                        <span class="text-slate-500 mr-2">Tanggal Lahir:</span>
                        <span class="font-semibold text-slate-700"><?= !empty($user['tanggal_lahir']) ? date('d F Y', strtotime($user['tanggal_lahir'])) : '-' ?></span>
                    </div>
                </div>
            </div>

            <!-- Riwayat Peminjaman -->
            <div class="bg-white rounded-lg shadow-lg p-8 max-w-4xl mx-auto">
                <h2 class="text-2xl font-bold text-slate-800 mb-6 border-b pb-4">Riwayat Peminjaman</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-slate-50 text-slate-600 uppercase text-xs">
                            <tr>
                                <th class="py-3 px-4 font-semibold">ID Pinjam</th>
                                <th class="py-3 px-4 font-semibold">Tanggal</th>
                                <th class="py-3 px-4 font-semibold">Buku</th>
                                <th class="py-3 px-4 font-semibold text-center">Status</th>
                                <th class="py-3 px-4 font-semibold text-right">Denda</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-600">
                            <?php if (empty($riwayat_peminjaman)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-10 text-slate-500">
                                        <i class="fas fa-folder-open text-3xl mb-2"></i>
                                        <p>Anda belum memiliki riwayat peminjaman.</p>
                                    </td>
                                </tr>
                            <?php else: foreach($riwayat_peminjaman as $pinjam): ?>
                                <tr class="border-b border-slate-200 hover:bg-slate-50 align-top">
                                    <td class="py-4 px-4 font-mono text-slate-500">#<?= esc($pinjam['id_pinjam']) ?></td>
                                    <td class="py-4 px-4">
                                        <div class="font-medium">Pinjam: <?= date('d M Y', strtotime($pinjam['tanggal_pinjam'])) ?></div>
                                        <div class="text-xs text-slate-500">Kembali: <?= date('d M Y', strtotime($pinjam['tanggal_kembali'])) ?></div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <ul class="list-disc list-inside space-y-1">
                                            <?php 
                                                $buku_list = explode('||', $pinjam['daftar_buku']);
                                                foreach($buku_list as $judul_buku) {
                                                    echo '<li>' . esc($judul_buku) . '</li>';
                                                }
                                            ?>
                                        </ul>
                                    </td>
                                    <td class="py-4 px-4 text-center">
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
                                                $statusText = 'Selesai';
                                            }
                                        ?>
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full <?= $statusClass ?>">
                                            <?= esc($statusText) ?>
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-right font-semibold <?= $pinjam['total_denda'] > 0 ? 'text-red-600' : 'text-slate-500' ?>">
                                        Rp <?= number_format($pinjam['total_denda'], 0, ',', '.') ?>
                                    </td>
                                </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <footer class="text-center py-4 mt-8 bg-white border-t border-slate-200">
        <p class="text-slate-500">&copy; <?= date('Y') ?> Pustaka Booking. All rights reserved.</p>
    </footer>

</body>
</html>