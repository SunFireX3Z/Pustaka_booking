<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Katalog Buku') ?> - Pustaka Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .pagination-link {
            padding: 0.5rem 1rem;
            border: 1px solid #e2e8f0;
            color: #4a5568;
            border-radius: 0.375rem;
            transition: all 0.2s;
        }
        .pagination-link:hover {
            background-color: #f7fafc;
            border-color: #cbd5e0;
        }
        .pagination-link.active {
            background-color: #4f46e5;
            color: white;
            border-color: #4f46e5;
            font-weight: 600;
        }
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
            <a href="<?= base_url('member') ?>" class="font-medium text-slate-600 hover:text-indigo-600 transition duration-300">Home</a>
            <a href="<?= base_url('member/katalog') ?>" class="font-semibold text-indigo-600">Katalog Buku</a>
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

    <div class="container mx-auto p-6">
        <!-- Header & Filter -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="w-full md:w-auto">
                    <h1 class="text-2xl font-bold text-slate-800">Katalog Buku</h1>
                    <p class="text-sm text-slate-500 mt-1">Jelajahi semua koleksi buku kami.</p>
                </div>
                <form action="<?= base_url('member/katalog') ?>" method="get" class="flex flex-col sm:flex-row items-center gap-2 w-full md:w-auto">
                    <div class="relative w-full sm:w-auto flex-grow">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-search text-slate-400"></i>
                        </span>
                        <input type="search" name="keyword" placeholder="Cari judul atau penulis..." value="<?= esc($keyword ?? '') ?>" class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>
                    <select name="kategori" onchange="this.form.submit()" class="w-full sm:w-auto px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition">
                        <option value="">Semua Kategori</option>
                        <?php foreach($kategori as $kat): ?>
                            <option value="<?= $kat['id_kategori'] ?>" <?= (isset($selected_kategori) && $selected_kategori == $kat['id_kategori']) ? 'selected' : '' ?>>
                                <?= esc($kat['nama_kategori']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
        </div>

        <!-- Daftar Buku -->
        <?php if (!empty($grouped_buku) && is_array($grouped_buku)) : ?>
            <div class="space-y-12">
                <?php foreach ($grouped_buku as $categoryName => $booksInCategory): ?>
                    <section>
                        <h2 class="text-2xl font-bold text-slate-800 mb-4 border-l-4 border-indigo-500 pl-3"><?= esc($categoryName) ?></h2>
                        <div class="horizontal-scroll flex gap-5 overflow-x-auto pb-4 -mb-4">
                            <?php foreach ($booksInCategory as $item): ?>
                                <div class="flex-shrink-0 w-48 group bg-white rounded-xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-xl hover:border-indigo-300 transition-all duration-300 hover:-translate-y-1">
                                    <div class="relative">
                                        <div class="h-72 bg-slate-100 flex items-center justify-center p-1">
                                            <img src="<?= base_url('uploads/' . esc($item['image'], 'attr')) ?>" alt="Cover <?= esc($item['judul_buku'], 'attr') ?>" class="w-auto h-full object-contain transition-transform duration-300 group-hover:scale-105">
                                        </div>
                                        <span class="absolute top-2 left-2 bg-indigo-500 text-white text-xs font-semibold px-2.5 py-1 rounded-full">Stok: <?= esc($item['stok']) ?></span>
                                        <div class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <div class="flex items-center gap-3">
                                                <form action="<?= base_url('member/book/' . $item['id']) ?>" method="post">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-full bg-green-500 text-white hover:bg-green-600 transition-all transform scale-90 group-hover:scale-100 <?= $item['stok'] > 0 ? '' : 'opacity-50 cursor-not-allowed' ?>" title="Booking" <?= $item['stok'] > 0 ? '' : 'disabled' ?>>
                                                        <i class="fas fa-bookmark"></i>
                                                    </button>
                                                </form>
                                                <a href="<?= base_url('member/buku/' . esc($item['id'], 'url')) ?>" class="w-10 h-10 flex items-center justify-center rounded-full bg-indigo-500 text-white hover:bg-indigo-600 transition-all transform scale-90 group-hover:scale-100" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-4">
                                        <h3 class="font-bold text-sm text-slate-800 truncate group-hover:text-indigo-600 transition-colors" title="<?= esc($item['judul_buku']) ?>"><?= esc($item['judul_buku']) ?></h3>
                                        <p class="text-xs text-slate-500 mt-1 truncate" title="<?= esc($item['pengarang']) ?>"><?= esc($item['pengarang']) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endforeach; ?>
            </div>

        <?php else : ?>
            <div class="bg-white shadow-md rounded-lg p-12 text-center">
                <i class="fas fa-book-dead text-5xl text-slate-300 mb-4"></i>
                <h2 class="text-xl font-semibold text-slate-700">Buku Tidak Ditemukan</h2>
                <p class="text-slate-500 mt-2">Tidak ada buku yang cocok dengan kriteria pencarian Anda.</p>
            </div>
        <?php endif; ?>
    </div>

    <footer class="text-center py-4 mt-8 bg-white border-t">
        <p class="text-slate-500">&copy; <?= date('Y') ?> Pustaka Booking. All rights reserved.</p>
    </footer>

</body>
</html>