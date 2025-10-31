<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Booking - Pustaka Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

    <!-- Navbar -->
    <nav class="bg-white p-4 flex justify-between items-center shadow-sm sticky top-0 z-50 border-b border-slate-200">
        <div>
            <a href="<?= base_url('member') ?>" class="font-bold text-xl text-indigo-600"><i class="fas fa-book-open-reader"></i> Pustaka</a>
        </div>
        <div class="hidden md:flex items-center space-x-6">
            <a href="<?= base_url('member') ?>" class="font-medium text-slate-600 hover:text-indigo-600 transition duration-300">Home</a>
            <a href="<?= base_url('member/katalog') ?>" class="font-medium text-slate-600 hover:text-indigo-600 transition duration-300">Katalog Buku</a>
            <a href="<?= base_url('member/keranjang') ?>" class="relative font-semibold text-indigo-600">
                Keranjang
                <?php if (isset($cart_count) && $cart_count > 0): ?>
                    <span class="absolute -top-2 -right-4 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full"><?= $cart_count ?></span>
                <?php endif; ?>
            </a>
        </div>
        <!-- User Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                <span class="hidden sm:block text-slate-700 font-medium hover:text-indigo-600"><?= esc(session()->get('nama')) ?></span>
                <img src="<?= base_url('uploads/' . (session()->get('image') ?? 'default.png')) ?>" alt="User" class="w-9 h-9 rounded-full border-2 border-slate-200 hover:border-indigo-300 transition">
            </button>
            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-1 z-50 border border-slate-200">
                <a href="<?= base_url('member/profile') ?>" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Profil Saya</a>
                <div class="border-t border-slate-100"></div>
                <a href="<?= base_url('logout') ?>" class="block px-4 py-2 text-sm text-red-600 hover:bg-slate-100">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-6">
        <!-- Breadcrumb -->
        <div class="mb-6 text-sm">
            <a href="<?= base_url('member') ?>" class="text-indigo-600 hover:underline">Home</a>
            <span class="mx-2 text-slate-400">/</span>
            <span class="text-slate-500">Keranjang Booking</span>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8 max-w-4xl mx-auto">
            <h1 class="text-2xl font-bold text-slate-800 mb-6 border-b pb-4">Keranjang Booking Anda</h1>
            
            <?php if (empty($keranjang)): ?>
                <div class="text-center py-12">
                    <i class="fas fa-shopping-cart text-5xl text-slate-300 mb-4"></i>
                    <h2 class="text-xl font-semibold text-slate-700">Keranjang Anda Kosong</h2>
                    <p class="text-slate-500 mt-2">Sepertinya Anda belum menambahkan buku apapun ke keranjang.</p>
                    <a href="<?= base_url('member') ?>" class="mt-6 inline-block bg-indigo-600 text-white font-semibold px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors shadow-md">
                        Mulai Cari Buku
                    </a>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($keranjang as $item): ?>
                        <div class="flex items-center gap-4 p-4 border rounded-lg hover:bg-slate-50 transition">
                            <img src="<?= base_url('uploads/' . esc($item['image'])) ?>" alt="Cover" class="w-16 h-24 object-cover rounded-md flex-shrink-0">
                            <div class="flex-grow">
                                <h3 class="font-bold text-slate-800"><?= esc($item['judul_buku']) ?></h3>
                                <p class="text-sm text-slate-500">oleh <?= esc($item['pengarang']) ?></p>
                            </div>
                            <a href="<?= base_url('member/keranjang/hapus/' . $item['id_detail']) ?>" class="delete-item-btn flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full text-red-500 hover:bg-red-100 transition-colors" title="Hapus dari keranjang">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mt-8 pt-6 border-t text-center">
                    <p class="text-slate-600">Total <span class="font-bold"><?= count($keranjang) ?></span> buku di keranjang Anda.</p>
                    <p class="text-sm text-slate-500 mt-2">Booking Anda akan diproses oleh admin. Silakan tunggu konfirmasi selanjutnya.</p>
                </div>
            <?php endif; ?>
        </div>
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

        // Konfirmasi hapus dengan SweetAlert2
        const deleteButtons = document.querySelectorAll('.delete-item-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault(); // Mencegah link langsung diakses
                const url = this.href; // Simpan URL dari link

                Swal.fire({
                    title: 'Anda Yakin?',
                    text: "Buku ini akan dihapus dari keranjang Anda!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url; // Arahkan ke URL hapus jika dikonfirmasi
                    }
                });
            });
        });
    </script>
</body>
</html>