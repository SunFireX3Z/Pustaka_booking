<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Homepage') ?> - Pustaka Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; }

        /* Cover buku seragam dengan rasio 2:3 */
        .book-cover {
            width: 100%;
            aspect-ratio: 2 / 3;
            object-fit: cover;
            object-position: center;
            transition: transform 0.3s ease;
        }

        /* Responsive grid spacing */
        @media (min-width: 1024px) {
            .book-grid {
                gap: 1.5rem; /* Menambah gap di layar besar */
            }
        }

        /* Custom Swiper Pagination */
        .beritaSwiper {
            position: relative;
        }
        .beritaSwiper .swiper-pagination {
            bottom: 25px; /* Sesuaikan posisi vertikal */
            right: 25px; /* Sesuaikan posisi horizontal */
            left: auto;
            width: auto;
            text-align: right;
        }
        .beritaSwiper .swiper-pagination-bullet {
            background-color: #a5b4fc; /* Warna indigo-300 */
        }
        .beritaSwiper .swiper-pagination-bullet-active {
            background-color: #4f46e5; /* Warna indigo-600 */
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
            <a href="<?= base_url('member') ?>" class="font-medium text-indigo-600 border-b-2 border-indigo-600 pb-1">Home</a>
            <a href="<?= base_url('member/katalog') ?>" class="font-medium text-slate-600 hover:text-indigo-600 transition duration-300">Katalog Buku</a>
            <a href="<?= base_url('member/keranjang') ?>" class="relative font-medium text-slate-600 hover:text-indigo-600 transition duration-300">
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

    <!-- Main Content -->
    <main class="container mx-auto p-6">

        <!-- Welcome Banner -->
        <div class="relative bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-8 rounded-xl shadow-lg mb-10 overflow-hidden">
            <div class="absolute -right-10 -bottom-16 opacity-20">
                <i class="fas fa-book-open-reader text-[180px]"></i>
            </div>
            <div class="relative z-10">
                <h1 class="text-3xl font-bold">Selamat Datang, <?= esc($nama_member ?? 'Member') ?>!</h1>
                <p class="mt-2 text-indigo-200">Jelajahi koleksi buku kami dan temukan bacaan favoritmu.</p>
                <a href="<?= base_url('member/katalog') ?>" class="inline-block mt-4 bg-white text-indigo-600 font-semibold px-5 py-2 rounded-lg shadow-md hover:bg-slate-100 transition">
                    Lihat Katalog Buku
                </a>
            </div>
        </div>

        <!-- Bagian Berita Terbaru -->
        <section class="mb-12">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-slate-800">Berita & Informasi</h2>
                <a href="#" class="text-indigo-600 font-medium hover:underline">Lihat Semua <i class="fas fa-arrow-right text-xs ml-1"></i></a>
            </div>
            
            <?php if (empty($berita_list)): ?>
                <div class="text-center py-10 bg-white rounded-lg shadow-sm">
                    <i class="fas fa-newspaper text-4xl text-slate-300 mb-3"></i>
                    <p class="text-slate-500">Belum ada berita untuk ditampilkan.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($berita_list as $berita): ?>
                        <a href="<?= base_url('member/berita/detail/' . $berita['id']) ?>" title="<?= esc($berita['judul']) ?>" class="block rounded-xl overflow-hidden group transform hover:-translate-y-1 transition-all duration-300 shadow-lg">
                            <div class="relative h-64">
                                <!-- Gambar Latar -->
                                <img src="<?= base_url('uploads/berita/' . esc($berita['thumbnail'])) ?>" alt="<?= esc($berita['judul']) ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                <!-- Inner Shadow Overlay -->
                                <div class="absolute inset-0" style="box-shadow: inset 0 -80px 60px -40px rgba(0,0,0,0.8);"></div>
                                <!-- Konten Teks -->
                                <div class="absolute bottom-0 left-0 p-5 text-white">
                                    <span class="inline-block bg-indigo-500 text-white text-xs font-semibold px-2.5 py-1 rounded-full mb-2"><?= esc($berita['nama_kategori']) ?></span>
                                    <h3 class="text-base font-bold leading-tight [text-shadow:1px_1px_3px_rgba(0,0,0,0.5)]">
                                        <?= esc($berita['judul']) ?>
                                    </h3>
                                    <p class="text-xs text-slate-500 mt-2">
                                        <i class="far fa-clock mr-1"></i> <?= date('d F Y', strtotime($berita['created_at'])) ?>
                                    </p>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <!-- Bagian Buku Terbaru -->
        <section>
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-slate-800">Buku Terbaru</h2>
                <a href="<?= base_url('member/katalog') ?>" class="text-indigo-600 font-medium hover:underline">
                    Lihat Semua <i class="fas fa-arrow-right text-xs ml-1"></i>
                </a>
            </div>

            <!-- Skeleton Loader -->
            <div id="buku-terbaru-skeleton" class="book-grid grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-7 xl:grid-cols-8 gap-4">
                <?php for ($i = 0; $i < 8; $i++): ?>
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden animate-pulse">
                        <div class="w-full bg-slate-200" style="aspect-ratio: 2/3;"></div>
                        <div class="p-3">
                            <div class="h-3 bg-slate-200 rounded-full w-3/4 mb-2"></div>
                            <div class="h-2 bg-slate-200 rounded-full w-1/2"></div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>

            <!-- Actual Content (Hidden Initially) -->
            <div id="buku-terbaru-content" style="display: none;">
                <div class="book-grid grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-7 xl:grid-cols-8 gap-4">
                <?php foreach ($buku_terbaru as $buku): ?>
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden group border border-slate-200 hover:shadow-lg hover:-translate-y-1 hover:scale-105 transition-all duration-300">
                        <a href="<?= base_url('member/buku/' . $buku['id']) ?>">
                            <div class="relative">
                                <img src="<?= base_url('uploads/' . $buku['image']) ?>" 
                                    alt="<?= esc($buku['judul_buku']) ?>" 
                                    class="book-cover">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300"></div>
                            </div>
                            <div class="p-3">
                                <h3 class="font-semibold text-xs text-slate-800 leading-tight truncate group-hover:text-indigo-600" 
                                    title="<?= esc($buku['judul_buku']) ?>">
                                    <?= esc($buku['judul_buku']) ?>
                                </h3>
                                <p class="text-xs text-slate-500 mt-1 truncate">
                                    <?= esc($buku['pengarang']) ?>
                                </p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            </div>
        </section>

        <!-- Bagian Berita Online -->
        <section class="mt-12">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-slate-800">Berita Online Terkini</h2>
                <a href="https://news.google.com/topics/CAAqIQgKIhtodHRwczovL25ld3MuZ29vZ2xlLmNvbS9fP2hlPWlkJmdsPUlEJmNlSUQ9SVBoZWxJRFBnbElEQ2VJRJ?hl=id&gl=ID&ceid=ID:id"
                target="_blank" rel="noopener noreferrer"
                class="text-indigo-600 font-medium hover:underline">
                Lebih Banyak <i class="fas fa-arrow-right text-xs ml-1"></i>
                </a>
            </div>

            <?php if (empty($online_berita)): ?>
                <div class="text-center py-10 bg-white rounded-lg shadow-sm">
                    <i class="fas fa-wifi-slash text-4xl text-slate-300 mb-3"></i>
                    <p class="text-slate-500">Gagal memuat berita online saat ini.</p>
                </div>
            <?php else: ?>
                <div class="swiper beritaSwiper">
                    <div class="swiper-wrapper">
                        <?php foreach ($online_berita as $berita): ?>
                            <div class="swiper-slide h-auto">
                                <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col md:flex-row h-64 group">
                                    <!-- Thumbnail -->
                                    <div class="w-full md:w-2/5 flex-shrink-0">
                                        <img src="<?= esc($berita['urlToImage'] ?? base_url('image_assets/placeholder.jpg')) ?>"
                                            alt="<?= esc($berita['title']) ?>"
                                            class="w-full h-52 md:h-full object-cover bg-slate-200">
                                    </div>

                                    <!-- Info berita -->
                                    <div class="w-full md:w-3/5 p-5 flex flex-col justify-between overflow-hidden">
                                        <div>
                                            <span class="block text-indigo-700 text-xs font-semibold mb-1">
                                                <?= esc($berita['source']['name'] ?? 'Tidak diketahui') ?>
                                            </span>
                                            <h3 class="text-lg font-bold text-slate-800 group-hover:text-indigo-600 transition-colors duration-300 leading-snug line-clamp-3">
                                                <a href="<?= esc($berita['url']) ?>" target="_blank" rel="noopener noreferrer">
                                                    <?= esc($berita['title']) ?>
                                                </a>
                                            </h3>

                                            <?php if (!empty($berita['author'])): ?>
                                                <p class="text-xs text-slate-500 mt-2 italic">
                                                    Oleh <?= esc($berita['author']) ?>
                                                </p>
                                            <?php endif; ?>

                                            <?php if (!empty($berita['description'])): ?>
                                                <p class="text-sm text-slate-600 mt-3 line-clamp-3">
                                                    <?= esc($berita['description']) ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                        <?php if (!empty($berita['publishedAt'])): ?>
                                            <p class="text-xs text-slate-400 mt-4">
                                                <?= date('d M Y, H:i', strtotime($berita['publishedAt'])) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Navigasi panah -->
                    <div class="swiper-button-next text-indigo-600"></div>
                    <div class="swiper-button-prev text-indigo-600"></div>
                    <div class="swiper-pagination"></div>
                </div>
            <?php endif; ?>
        </section>

        <script>
            new Swiper(".beritaSwiper", {
                slidesPerView: 1,
                spaceBetween: 15,
                loop: true,
                autoplay: {
                    delay: 5000, // Pindah setiap 5 detik
                    disableOnInteraction: false, // Tetap autoplay setelah interaksi user
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
            });

            // Skeleton loading for "Buku Terbaru"
            document.addEventListener('DOMContentLoaded', function() {
                // Simulate loading time
                setTimeout(function() {
                    const skeleton = document.getElementById('buku-terbaru-skeleton');
                    const content = document.getElementById('buku-terbaru-content');
                    if (skeleton) skeleton.style.display = 'none';
                    if (content) content.style.display = 'block';
                }, 500); // 0.5 detik delay
            });
        </script>

    </main>

    <footer class="text-center py-6 mt-12 bg-white border-t border-slate-200">
        <p class="text-slate-500">&copy; <?= date('Y') ?> Pustaka Booking. All rights reserved.</p>
    </footer>
</body>
</html>