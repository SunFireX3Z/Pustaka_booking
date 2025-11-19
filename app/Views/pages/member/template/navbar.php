<?php
$active_class = 'font-medium text-lg text-indigo-600 border-b-2 border-indigo-600 pb-1';
$inactive_class = 'font-medium text-lg text-slate-600 hover:text-indigo-600 transition duration-300';
?>

<nav 
    x-data="{
        scrolled: false,
        init() {
            this.checkScroll();
            window.addEventListener('scroll', () => this.checkScroll());
        },
        checkScroll() {
            this.scrolled = window.pageYOffset > 10;
        }
    }"
    :class="{
        'bg-white shadow-md border-b border-slate-200 transition-colors duration-300': scrolled,
        'bg-transparent border-b border-transparent': !scrolled
    }"
    class="p-4 flex justify-between items-center sticky top-0 z-50 transition-shadow duration-300"
    x-cloak
>
    <!-- Kiri: Logo + Navigasi -->
    <div class="flex items-center flex-1">
        <!-- Logo -->
        <div class="flex-shrink-0">
            <a href="<?= base_url('member') ?>" 
               class="flex items-center transform transition-all duration-500"
               :class="scrolled ? 'opacity-100 translate-y-0' : 'opacity-0 -translate-y-3 pointer-events-none'"
            >
                <img src="<?= base_url('uploads/profile/' . esc($web_profile['logo'] ?? 'default_logo.png')) ?>" alt="Logo" class="h-9 w-auto">
            </a>
        </div>

        <!-- Navigasi Tengah -->
        <div class="hidden md:flex flex-1 transition-all duration-300" 
             :class="scrolled ? 'justify-center ml-0' : 'ml-8'">
            <div class="flex items-center space-x-6">
                <a href="<?= base_url('member') ?>" class="<?= ($current_page ?? '') === 'home' ? $active_class : $inactive_class ?>">Home</a>
                <a href="<?= base_url('member/katalog') ?>" class="<?= ($current_page ?? '') === 'katalog' ? $active_class : $inactive_class ?>">Katalog Buku</a>
                <a href="<?= base_url('member/keranjang') ?>" class="relative <?= ($current_page ?? '') === 'keranjang' ? $active_class : $inactive_class ?>">
                    Keranjang
                    <?php if (isset($cart_count) && $cart_count > 0): ?>
                        <span class="absolute -top-2 -right-4 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                            <?= $cart_count ?>
                        </span>
                    <?php endif; ?>
                </a>
                <a href="https://smkasyafiiyah.perpustakaan.co.id/home.ks" target="_blank" rel="noopener noreferrer" class="<?= $inactive_class ?>">Perpus Digital</a>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="hidden lg:block mr-4">
        <form action="<?= base_url('member/katalog') ?>" method="get" class="relative w-56">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-slate-400"></i>
            </div>
            <input type="search" name="keyword" id="search" value="<?= esc($keyword ?? '') ?>" class="block w-full pl-10 pr-3 py-2 border border-slate-300 rounded-md leading-5 bg-white placeholder-slate-500 focus:outline-none focus:placeholder-slate-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Cari judul buku...">
        </form>
    </div>
        <!-- User Dropdown -->
        <?= view('pages/member/template/user_dropdown'); ?>
</nav>

<style>
    /* Hilangkan flicker Alpine saat load */
    [x-cloak] { display: none !important; }
</style>