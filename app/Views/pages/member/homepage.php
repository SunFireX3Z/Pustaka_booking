<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Homepage Member') ?> - Pustaka Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- AlpineJS for interactivity -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .modal-enter { opacity: 0; transform: scale(0.95); }
        .modal-enter-active { opacity: 1; transform: scale(1); transition: all 0.2s ease-out; }
        .modal-leave { opacity: 1; transform: scale(1); }
        .modal-leave-active { opacity: 0; transform: scale(0.95); transition: all 0.2s ease-in; }
        .fade-in-up {
            opacity: 0;
            transform: translateY(15px);
            transition: opacity 0.5s ease-out, transform 0.5s ease-out;
        }
        .fade-in-up.is-visible { opacity: 1; transform: translateY(0); }
        /* Custom scrollbar for horizontal scroll */
        .horizontal-scroll {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
        .horizontal-scroll::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Opera */
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
            <a href="<?= base_url('member') ?>" class="font-semibold text-indigo-600">Home</a>
            <a href="#" class="font-medium text-slate-600 hover:text-indigo-600 transition duration-300">Katalog Buku</a>
            <a href="#" class="font-medium text-slate-600 hover:text-indigo-600 transition duration-300">Riwayat Pinjam</a>
        </div>
        <!-- User Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                <span class="hidden sm:block text-slate-700 font-medium hover:text-indigo-600"><?= esc(session()->get('nama')) ?></span>
                <img src="<?= base_url('uploads/' . (session()->get('image') ?? 'default.png')) ?>" alt="User" class="w-9 h-9 rounded-full border-2 border-slate-200 hover:border-indigo-300 transition">
            </button>
            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-1 z-50 border border-slate-200">
                <a href="#" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Profil Saya</a>
                <div class="border-t border-slate-100"></div>
                <a href="<?= base_url('logout') ?>" class="block px-4 py-2 text-sm text-red-600 hover:bg-slate-100">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-6">

        <!-- Header -->
        <header class="animated-item rounded-xl shadow-lg p-8 md:p-12 mb-8 flex flex-col md:flex-row items-center gap-8 relative overflow-hidden" 
            style="background-image: linear-gradient(rgba(116, 140, 245, 0.4), rgba(98, 178, 244, 0.4)), url('<?= base_url('image_assets/bannerbackground.jpg') ?>'); background-size: cover; background-position: center;">
            <div class="flex-1 text-center md:text-left">
                <h1 class="text-4xl font-bold mb-2 text-white">
                    Selamat Datang, <span class="font-bold"><?= esc($nama_member) ?>!</span>
                </h1>
                <p class="text-white mb-6 max-w-xl mx-auto md:mx-0">
                    Jelajahi ribuan koleksi buku, temukan pengetahuan baru, dan pinjam dengan mudah.
                </p>
                <div class="max-w-md mx-auto md:mx-0">
                    <form action="#" method="get">
                        <div class="relative flex items-center">
                            <input type="search" name="keyword" placeholder="Cari judul buku atau penulis..." 
                                class="w-full p-4 pr-12 rounded-full bg-slate-100 border-2 border-transparent 
                                focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition 
                                text-slate-800 placeholder-slate-400">
                            <button type="submit" class="absolute right-4 text-blue-400 hover:text-indigo-600 transition">
                                <i class="fas fa-search fa-lg"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="hidden md:block absolute -bottom-4 right-4">
                <img src="<?= base_url('image_assets/peoplebook.png') ?>" alt="Ilustrasi" class="h-72 w-auto pointer-events-none">
            </div>
        </header>

        <!-- Personal Stats -->
        <section class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 animated-item">
            <a href="#" class="group relative block bg-gradient-to-br from-green-500 to-green-600 text-white rounded-2xl p-6 overflow-hidden shadow-lg shadow-green-500/20 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-green-500/40">
                <i class="fas fa-book-reader absolute -right-4 -bottom-4 text-8xl text-white opacity-10 transform transition-transform duration-500 group-hover:scale-110 group-hover:rotate-6"></i>
                <div class="relative z-10">
                    <p class="text-5xl font-bold"><?= esc($jumlah_dipinjam) ?></p>
                    <p class="mt-1 font-medium">Buku Sedang Dipinjam</p>
                </div>
            </a>
            <a href="#" class="group relative block bg-gradient-to-br from-indigo-500 to-blue-600 text-white rounded-2xl p-6 overflow-hidden shadow-lg shadow-indigo-500/20 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-indigo-500/40">
                <i class="fas fa-calendar-check absolute -right-4 -bottom-4 text-8xl text-white opacity-10 transform transition-transform duration-500 group-hover:scale-110 group-hover:rotate-6"></i>
                <div class="relative z-10">
                    <p class="text-5xl font-bold"><?= esc($jumlah_dibooking) ?></p>
                    <p class="mt-1 font-medium">Buku Sedang Dibooking</p>
                </div>
            </a>
        </section>

        <!-- Buku Terbaru -->
        <section>
            <div class="flex justify-between items-center mb-4 animated-item">
                <h2 class="text-2xl font-bold text-slate-800">Koleksi Terbaru</h2>
                <div class="flex items-center gap-2">
                    <button id="scroll-left" class="w-8 h-8 flex items-center justify-center rounded-full bg-white border border-slate-200 text-slate-500 hover:bg-slate-100 transition"><i class="fas fa-chevron-left"></i></button>
                    <button id="scroll-right" class="w-8 h-8 flex items-center justify-center rounded-full bg-white border border-slate-200 text-slate-500 hover:bg-slate-100 transition"><i class="fas fa-chevron-right"></i></button>
                    <a href="#" class="hidden sm:inline-block ml-2 text-sm font-medium text-indigo-600 hover:underline">Lihat Semua</a>
                </div>
            </div>
            <?php if (!empty($buku_terbaru) && is_array($buku_terbaru)) : ?>
                <div id="book-list" class="horizontal-scroll flex gap-5 overflow-x-auto pb-4 -mb-4">
                    <?php foreach ($buku_terbaru as $buku) : ?>
                        <div class="animated-item flex-shrink-0 w-48 group bg-white rounded-xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-xl hover:border-indigo-300 transition-all duration-300 hover:-translate-y-1">
                            <div class="relative">
                                <!-- Image Container -->
                                <div class="h-72 bg-slate-100 flex items-center justify-center p-1">
                                    <img src="<?= base_url('uploads/' . esc($buku['image'], 'attr')) ?>" alt="Cover <?= esc($buku['judul_buku'], 'attr') ?>" class="w-auto h-full object-contain transition-transform duration-300 group-hover:scale-105">
                                </div>
                                <!-- Stock Badge -->
                                <span class="absolute top-2 left-2 bg-indigo-500 text-white text-xs font-semibold px-2.5 py-1 rounded-full">Stok: <?= esc($buku['stok']) ?></span>
                                <!-- Hover Actions -->
                                <div class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="flex items-center gap-3">
                                        <button class="w-10 h-10 flex items-center justify-center rounded-full bg-green-500 text-white hover:bg-green-600 transition-all transform scale-90 group-hover:scale-100" title="Booking"><i class="fas fa-bookmark"></i></button>
                                        <button onclick="showDetail(this)" class="w-10 h-10 flex items-center justify-center rounded-full bg-indigo-500 text-white hover:bg-indigo-600 transition-all transform scale-90 group-hover:scale-100" title="Lihat Detail" data-judul="<?= esc($buku['judul_buku']) ?>" data-penerbit="<?= esc($buku['penerbit']) ?>" data-pengarang="<?= esc($buku['pengarang']) ?>" data-tahun="<?= esc($buku['tahun_terbit']) ?>" data-stok="<?= esc($buku['stok']) > 0 ? $buku['stok'] . ' Tersedia' : 'Kosong' ?>" data-kategori="<?= esc($buku['nama_kategori'] ?? 'Tidak ada kategori') ?>" data-deskripsi="<?= esc($buku['deskripsi'] ?? '-') ?>" data-image="<?= base_url('uploads/' . $buku['image']) ?>"><i class="fas fa-eye"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-sm text-slate-800 truncate group-hover:text-indigo-600 transition-colors" title="<?= esc($buku['judul_buku']) ?>"><?= esc($buku['judul_buku']) ?></h3>
                                <p class="text-xs text-slate-500 mt-1 truncate" title="<?= esc($buku['pengarang']) ?>"><?= esc($buku['pengarang']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <!-- "Lihat Semua" Card for mobile -->
                    <a href="#" class="animated-item flex-shrink-0 w-48 sm:hidden flex flex-col items-center justify-center bg-slate-50 rounded-xl text-slate-500 hover:bg-white hover:border-indigo-500 hover:text-indigo-600 transition-all duration-300 border border-slate-200 hover:shadow-xl hover:-translate-y-1">
                        <i class="fas fa-arrow-right fa-2x mb-2"></i>
                        <span class="font-semibold text-sm">Lihat Semua</span>
                    </a>
                </div>
            <?php else : ?>
                <div class="bg-white shadow-md rounded-lg p-8 text-center">
                    <p class="text-gray-500">Belum ada buku terbaru yang ditambahkan.</p>
                </div>
            <?php endif; ?>
        </section>

    </div>

    <footer class="text-center py-4 mt-8 bg-white border-t">
        <p class="text-slate-500">&copy; <?= date('Y') ?> Pustaka Booking. All rights reserved.</p>
    </footer>
    
    <!-- Modal Detail Buku -->
    <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center z-50 p-4">
        <div id="modalContent" class="bg-white rounded-lg shadow-2xl max-w-4xl w-full transform transition-all relative">
            <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-800 w-10 h-10 rounded-full flex items-center justify-center hover:bg-gray-100 transition-colors z-20">
                <i class="fas fa-times fa-lg"></i>
            </button>

            <div class="flex flex-col md:flex-row">
                <div class="w-full md:w-1/3 bg-slate-100 rounded-t-lg md:rounded-l-lg md:rounded-t-none flex items-center justify-center p-8">
                    <img id="modalImage" src="" alt="cover" class="max-h-96 object-contain shadow-xl rounded-lg">
                </div>
                <div class="w-full md:w-2/3 p-8 md:p-10 flex flex-col">
                    <div class="flex-grow">
                        <span id="modalKategori" class="inline-block bg-indigo-100 text-indigo-800 text-xs font-semibold px-2.5 py-1 rounded-full mb-3"></span>
                        <h2 id="modalJudul" class="text-3xl font-bold text-slate-900 mb-2 leading-tight"></h2>
                        <p id="modalPengarang" class="text-slate-500 text-lg mb-6"></p>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8 text-sm">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-building fa-fw text-slate-400 text-2xl"></i>
                                <div>
                                    <p class="text-slate-500">Penerbit</p>
                                    <p id="modalPenerbit" class="text-slate-800 font-semibold"></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-calendar-alt fa-fw text-slate-400 text-2xl"></i>
                                <div>
                                    <p class="text-slate-500">Tahun</p>
                                    <p id="modalTahun" class="text-slate-800 font-semibold"></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-box-open fa-fw text-slate-400 text-2xl"></i>
                                <div>
                                    <p class="text-slate-500">Stok</p>
                                    <p id="modalStok" class="font-semibold"></p>
                                </div>
                            </div>
                        </div>

                        <div class="border-l-4 border-indigo-200 pl-4">
                            <h4 class="font-semibold text-slate-800 mb-1">Deskripsi</h4>
                            <p id="modalDeskripsi" class="text-slate-600 text-sm leading-relaxed italic"></p>
                        </div>
                    </div>
                    <!-- Modal Actions -->
                    <div class="mt-8 pt-6 border-t border-slate-200 flex flex-col sm:flex-row sm:justify-end space-y-3 sm:space-y-0 sm:space-x-4 font-medium">
                        <button class="w-full sm:w-auto flex items-center justify-center px-6 py-3 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 transition-colors shadow-md">
                            <i class="fas fa-bookmark mr-2"></i> Booking Buku Ini
                        </button>
                        <button class="w-full sm:w-auto flex items-center justify-center px-6 py-3 bg-indigo-500 text-white font-semibold rounded-lg hover:bg-indigo-600 transition-colors shadow-md">
                            <i class="fas fa-hand-holding-hand mr-2"></i> Pinjam Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const animatedItems = document.querySelectorAll('.animated-item');
            animatedItems.forEach((item, index) => {
                setTimeout(() => {
                    item.classList.add('is-visible', 'fade-in-up');
                }, index * 100);
            });
        });

        // Horizontal Scroll Buttons
        const bookList = document.getElementById('book-list');
        const scrollLeftBtn = document.getElementById('scroll-left');
        const scrollRightBtn = document.getElementById('scroll-right');

        scrollLeftBtn.addEventListener('click', () => {
            bookList.scrollBy({ left: -300, behavior: 'smooth' });
        });

        scrollRightBtn.addEventListener('click', () => {
            bookList.scrollBy({ left: 300, behavior: 'smooth' });
        });


        function showDetail(element) {
            const getValue = (val) => val && val.trim() !== "" ? val : "-";

            document.getElementById('modalJudul').innerText = getValue(element.dataset.judul);
            document.getElementById('modalPenerbit').innerText = getValue(element.dataset.penerbit);
            document.getElementById('modalPengarang').innerText = "oleh " + getValue(element.dataset.pengarang);
            document.getElementById('modalTahun').innerText = getValue(element.dataset.tahun);
            
            const stokElement = document.getElementById('modalStok');
            const stokText = getValue(element.dataset.stok);
            stokElement.innerText = stokText;
            stokElement.className = 'font-semibold'; // Reset class
            stokElement.classList.add(stokText.toLowerCase().includes('kosong') ? 'text-red-600' : 'text-green-600');

            document.getElementById('modalKategori').textContent = getValue(element.dataset.kategori);
            document.getElementById('modalDeskripsi').innerText = getValue(element.dataset.deskripsi);
            document.getElementById('modalImage').src = getValue(element.dataset.image);

            const modal = document.getElementById('detailModal');
            const content = document.getElementById('modalContent');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            content.classList.remove('modal-leave', 'modal-leave-active');
            content.classList.add('modal-enter-active');
        }

        function closeModal() {
            const modal = document.getElementById('detailModal');
            const content = document.getElementById('modalContent');

            content.classList.remove('modal-enter-active');
            content.classList.add('modal-leave-active');
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 200);
        }
    </script>
</body>
</html>