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
$routes->get('register', 'Auth::registerForm');
$routes->post('register', 'Auth::register');

// Rute utama setelah login
$routes->get('dashboard', 'Dashboard::index');

// Rute untuk Member
$routes->get('member', 'Member::index');

// Rute CRUD untuk Buku
$routes->get('buku', 'Buku::index');
$routes->post('buku', 'Buku::create');
$routes->post('buku/update/(:num)', 'Buku::update/$1');
$routes->get('buku/delete/(:num)', 'Buku::delete/$1');

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