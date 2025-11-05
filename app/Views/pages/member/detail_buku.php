<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Detail Buku') ?> - Pustaka Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
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
            animation: none;
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800">

    <?= view('pages/member/template/navbar', [
        'current_page' => 'katalog', // Menandai 'Katalog Buku' sebagai link aktif
        'cart_count'   => $cart_count ?? 0,
        'keyword'      => $keyword ?? '',
        'web_profile'  => $web_profile ?? []
    ]) ?>

    <div class="container mx-auto p-6">
        <!-- Breadcrumb -->
        <div class="mb-6 text-sm">
            <a href="<?= base_url('member') ?>" class="text-indigo-600 hover:underline">Home</a>
            <span class="mx-2 text-slate-400">/</span>
            <span class="text-slate-500">Detail Buku</span>
        </div>

        <div class="bg-white rounded-lg shadow-xl w-full transform transition-all relative">
            <div class="flex flex-col md:flex-row">
                <!-- Kolom Kiri untuk Gambar -->
                <div class="w-full md:w-1/3 bg-slate-100 rounded-t-lg md:rounded-l-lg md:rounded-t-none flex items-center justify-center p-8">
                    <img src="<?= base_url('uploads/' . esc($buku['image'])) ?>" alt="Cover <?= esc($buku['judul_buku']) ?>" class="max-h-96 object-contain shadow-xl rounded-lg">
                </div>
                <!-- Kolom Kanan untuk Konten -->
                <div class="w-full md:w-2/3 p-8 md:p-10 flex flex-col">
                    <div class="flex-grow">
                        <?php 
                            // Memeriksa apakah nama_kategori ada dan tidak kosong
                            $kategoriDisplay = !empty($buku['nama_kategori']) ? $buku['nama_kategori'] : 'Tanpa Kategori';
                            $kategoriList = explode(', ', $kategoriDisplay);
                        ?>
                        <?php foreach ($kategoriList as $kat): ?>
                            <span class="inline-block bg-indigo-100 text-indigo-800 text-xs font-semibold px-2.5 py-1 rounded-full mb-3"><?= esc($kat) ?></span>
                        <?php endforeach; ?>
                        <h1 class="text-3xl font-bold text-slate-900 mb-2 leading-tight"><?= esc($buku['judul_buku']) ?></h1>
                        <p class="text-slate-500 text-lg mb-6">oleh <?= esc($buku['pengarang']) ?></p>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8 text-sm">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-building fa-fw text-slate-400 text-2xl"></i>
                                <div>
                                    <p class="text-slate-500">Penerbit</p>
                                    <p class="text-slate-800 font-semibold"><?= esc($buku['penerbit']) ?></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-calendar-alt fa-fw text-slate-400 text-2xl"></i>
                                <div>
                                    <p class="text-slate-500">Tahun</p>
                                    <p class="text-slate-800 font-semibold"><?= esc($buku['tahun_terbit']) ?></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-box-open fa-fw text-slate-400 text-2xl"></i>
                                <div>
                                    <p class="text-slate-500">Stok</p>
                                    <p class="font-semibold <?= $buku['stok'] > 0 ? 'text-green-600' : 'text-red-600' ?>"><?= $buku['stok'] > 0 ? $buku['stok'] . ' Tersedia' : 'Kosong' ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="border-l-4 border-indigo-200 pl-4">
                            <h4 class="font-semibold text-slate-800 mb-1">Deskripsi</h4>
                            <p class="text-slate-600 text-sm leading-relaxed italic"><?= esc($buku['deskripsi'] ?? '-') ?></p>
                        </div>
                    </div>
                    <!-- Aksi -->
                    <div class="mt-8 pt-6 border-t border-slate-200 flex flex-col sm:flex-row sm:justify-end space-y-3 sm:space-y-0 sm:space-x-4 font-medium">
                        <?php if (!empty($buku['file_pdf'])): ?>
                            <a href="<?= base_url('uploads/pdf/' . esc($buku['file_pdf'])) ?>" target="_blank" class="w-full sm:w-auto flex items-center justify-center px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors shadow-md">
                                <i class="fas fa-book-open mr-2"></i> Baca Buku
                            </a>
                        <?php endif; ?>
                        <form action="<?= base_url('member/book/' . $buku['id']) ?>" method="post">
                            <?= csrf_field() ?>
                            <button type="submit" class="w-full sm:w-auto flex items-center justify-center px-6 py-3 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 transition-colors shadow-md <?= $buku['stok'] > 0 ? '' : 'opacity-50 cursor-not-allowed' ?>" <?= $buku['stok'] > 0 ? '' : 'disabled' ?>>
                                <i class="fas fa-bookmark mr-2"></i> Booking Buku Ini
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buku Terkait -->
        <?php if (!empty($buku_terkait)): ?>
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-slate-800 mb-6 border-l-4 border-indigo-500 pl-3">Anda Mungkin Juga Suka</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
                <?php foreach ($buku_terkait as $item): ?>
                    <div class="group bg-white rounded-xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-xl hover:border-indigo-300 transition-all duration-300 hover:-translate-y-1">
                        <a href="<?= base_url('member/buku/' . esc($item['id'], 'url')) ?>" title="<?= esc($item['judul_buku']) ?>">
                            <div class="relative">
                                <div class="bg-slate-100 flex items-center justify-center p-1" style="aspect-ratio: 2/3;">
                                    <img src="<?= base_url('uploads/' . esc($item['image'], 'attr')) ?>" alt="Cover <?= esc($item['judul_buku'], 'attr') ?>" class="w-auto h-full object-contain group-hover:scale-105 transition-transform duration-300">
                                </div>
                                <div class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-indigo-500 text-white">
                                        <i class="fas fa-eye"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3">
                                <h3 class="font-bold text-sm text-slate-800 truncate group-hover:text-indigo-600 transition-colors"><?= esc($item['judul_buku']) ?></h3>
                                <p class="text-xs text-slate-500 mt-1 truncate"><?= esc($item['pengarang']) ?></p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </div>

    <footer class="text-center py-4 mt-8 bg-white border-t">
        <p class="text-slate-500">&copy; <?= date('Y') ?> Pustaka Booking. All rights reserved.</p>
    </footer>

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
    </script>
</body>
</html>