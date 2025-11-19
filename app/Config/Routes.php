<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Rute default, akan langsung menampilkan halaman login.
$routes->get('/', 'Auth::index');

// Rute untuk Autentikasi
$routes->get('login', 'Auth::index');
$routes->post('login', 'Auth::login');
$routes->get('logout', 'Auth::logout');

// Grup Rute untuk Member (memerlukan login dan peran member)
$routes->group('member', ['filter' => 'member'], static function ($routes) {
    $routes->get('/', 'Member::index');
    $routes->get('profile', 'Member::profile');
    $routes->group('keranjang', static function ($routes) {
        $routes->get('/', 'Member::keranjang');
        $routes->post('selesaikan', 'Member::selesaikanBooking'); // URL menjadi: member/keranjang/selesaikan
        $routes->get('batal', 'Member::batalKeranjang');
        $routes->get('hapus/(:num)', 'Member::hapusDariKeranjang/$1');
    });
    $routes->get('katalog', 'Member::katalog');
    $routes->get('buku/(:num)', 'Member::detailBuku/$1');
    $routes->post('book/(:num)', 'Member::bookNow/$1'); // Perbaikan: Seharusnya mengarah ke bookNow
    $routes->get('berita/detail/(:num)', 'Member::detailBerita/$1');
});

// Grup Rute untuk Admin & Petugas (memerlukan login dan peran admin/petugas)
$routes->group('', ['filter' => 'admin'], static function ($routes) {
    // Rute utama setelah login
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('dashboard/active-users', 'Dashboard::getActiveUsersJson'); // Rute untuk API anggota aktif

    // Rute CRUD untuk Buku
    $routes->get('buku', 'Buku::index');
    $routes->post('buku', 'Buku::create');
    $routes->post('buku/update/(:num)', 'Buku::update/$1');
    $routes->get('buku/delete/(:num)', 'Buku::delete/$1');
    $routes->get('buku/qrcode/(:num)', 'Buku::generateQrCode/$1');

    // Rute CRUD untuk Anggota
    $routes->get('anggota', 'Anggota::index');
    $routes->get('anggota/new', 'Anggota::new');
    $routes->post('anggota/create', 'Anggota::create');
    $routes->get('anggota/edit/(:num)', 'Anggota::edit/$1');
    $routes->post('anggota/update/(:num)', 'Anggota::update/$1');
    $routes->get('anggota/delete/(:num)', 'Anggota::delete/$1');

    // Rute CRUD untuk Kategori
    $routes->get('kategori', 'Kategori::index');
    $routes->get('kategori/new', 'Kategori::new');
    $routes->post('kategori/create', 'Kategori::create');
    $routes->get('kategori/edit/(:num)', 'Kategori::edit/$1');
    $routes->post('kategori/update/(:num)', 'Kategori::update/$1');
    $routes->get('kategori/delete/(:num)', 'Kategori::delete/$1');

    // Rute CRUD untuk Berita
    $routes->get('berita', 'Berita::index');
    $routes->post('berita', 'Berita::create');
    $routes->post('berita/update/(:num)', 'Berita::update/$1');
    $routes->get('berita/delete/(:num)', 'Berita::delete/$1');
    $routes->post('berita/toggle-status/(:num)', 'Berita::toggleStatus/$1');

    // Rute CRUD untuk Kategori Berita
    $routes->get('kategori-berita', 'KategoriBerita::index');
    $routes->post('kategori-berita/create', 'KategoriBerita::create');
    $routes->post('kategori-berita/update/(:num)', 'KategoriBerita::update/$1');
    $routes->get('kategori-berita/delete/(:num)', 'KategoriBerita::delete/$1');

    // Rute untuk Transaksi & Laporan
    $routes->get('peminjaman', 'Peminjaman::index');
    $routes->get('peminjaman/return/(:num)', 'Peminjaman::returnBook/$1');
    $routes->get('peminjaman/detail/(:num)', 'Peminjaman::getDetail/$1');
    $routes->get('booking', 'Booking::index');
    $routes->get('booking/approve/(:num)', 'Booking::approve/$1');
    $routes->get('booking/cancel/(:num)', 'Booking::cancel/$1');
    $routes->get('booking/delete/(:num)', 'Booking::delete/$1');
    $routes->get('booking/getDetailBooking/(:num)', 'Booking::getDetailBooking/$1');
    $routes->get('denda', 'Denda::index');
    $routes->get('denda/bayar/(:num)', 'Denda::bayar/$1');
    $routes->get('denda/delete/(:num)', 'Denda::delete/$1');
    $routes->get('laporan', 'Laporan::index');
    $routes->get('laporan/buku-excel', 'Laporan::bukuExcel');
    $routes->get('laporan/buku-pdf', 'Laporan::bukuPdf');
    $routes->get('laporan/anggota-excel', 'Laporan::anggotaExcel');
    $routes->get('laporan/anggota-pdf', 'Laporan::anggotaPdf');
    $routes->get('laporan/peminjaman-excel', 'Laporan::peminjamanExcel');
    $routes->get('laporan/peminjaman-pdf', 'Laporan::peminjamanPdf');

    // Rute untuk Profil Website
    $routes->get('profile-web', 'ProfileWeb::index');
    $routes->post('profile-web/update', 'ProfileWeb::update');
});