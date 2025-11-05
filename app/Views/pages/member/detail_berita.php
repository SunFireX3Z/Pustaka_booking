<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Detail Berita') ?> - Pustaka Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .prose-content h1, .prose-content h2, .prose-content h3 {
            font-weight: 700;
            margin-top: 1.5em;
            margin-bottom: 0.5em;
        }
        .prose-content h1 { font-size: 2.25rem; }
        .prose-content h2 { font-size: 1.875rem; }
        .prose-content h3 { font-size: 1.5rem; }
        .prose-content p { margin-bottom: 1em; line-height: 1.75; }
        .prose-content ul, .prose-content ol { margin-left: 1.5rem; margin-bottom: 1em; }
        .prose-content a { color: #4f46e5; text-decoration: underline; }
        .prose-content img { max-width: 100%; height: auto; border-radius: 0.5rem; margin-top: 1em; margin-bottom: 1em; }
        .prose-content iframe { max-width: 100%; aspect-ratio: 16 / 9; border-radius: 0.5rem; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800">

    <?= view('pages/member/template/navbar', [
        'current_page' => 'home', // Menandai 'Home' sebagai link aktif
        'cart_count'   => $cart_count ?? 0,
        'keyword'      => $keyword ?? '',
        'web_profile'  => $web_profile ?? []
    ]) ?>

    <!-- Main Content -->
    <main class="container mx-auto p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Breadcrumb -->
            <div class="mb-6 text-sm">
                <a href="<?= base_url('member') ?>" class="text-indigo-600 hover:underline">Home</a>
                <span class="mx-2 text-slate-400">/</span>
                <span class="text-slate-500">Detail Berita</span>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Kolom Konten Utama (Kiri) -->
                <div class="lg:col-span-2">
                    <article class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <img src="<?= base_url('uploads/berita/' . esc($berita['thumbnail'])) ?>" alt="Thumbnail" class="w-full h-64 md:h-96 object-cover">
                        <div class="p-6 md:p-10">
                            <div class="mb-4">
                                <span class="inline-block bg-indigo-100 text-indigo-800 text-sm font-semibold px-3 py-1 rounded-full mb-2"><?= esc($berita['nama_kategori']) ?></span>
                                <h1 class="text-3xl md:text-4xl font-bold text-slate-900 leading-tight"><?= esc($berita['judul']) ?></h1>
                                <p class="text-slate-500 mt-2 text-sm">
                                    <i class="far fa-clock mr-1"></i> Dipublikasikan pada <?= date('d F Y', strtotime($berita['created_at'])) ?>
                                </p>
                            </div>
                            <div class="prose-content text-slate-700 mt-6">
                                <?= $berita['isi_berita'] ?>
                            </div>
                        </div>
                    </article>
                </div>
                
                <!-- Sidebar (Kanan) -->
                <aside class="lg:col-span-1">
                    <div class="sticky top-24">
                        <h3 class="text-xl font-bold text-slate-800 mb-4 border-l-4 border-indigo-500 pl-3">Berita Lainnya</h3>
                        <?php if (!empty($online_berita_sidebar)): ?>
                            <div class="space-y-4">
                                <?php foreach ($online_berita_sidebar as $item): ?>
                                    <a href="<?= esc($item['url']) ?>" target="_blank" rel="noopener noreferrer" class="block bg-white rounded-lg shadow-md overflow-hidden group hover:shadow-xl transition-shadow duration-300">
                                        <div class="flex items-center">
                                            <img src="<?= esc($item['urlToImage'] ?? base_url('image_assets/placeholder.jpg')) ?>" alt="<?= esc($item['title']) ?>" class="w-24 h-24 object-cover flex-shrink-0">
                                            <div class="p-3">
                                                <span class="text-xs text-indigo-600 font-semibold block"><?= esc($item['source']['name'] ?? 'Unknown Source') ?></span>
                                                <h4 class="text-sm font-bold text-slate-800 leading-tight group-hover:text-indigo-700 transition-colors line-clamp-3">
                                                    <?= esc($item['title']) ?>
                                                </h4>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-slate-500 text-sm">Tidak ada berita lain untuk ditampilkan.</p>
                        <?php endif; ?>
                    </div>
                </aside>
            </div>
        </div>
    </main>

    <footer class="text-center py-6 mt-8 bg-white border-t">
        <p class="text-slate-500">&copy; <?= date('Y') ?> Pustaka Booking. All rights reserved.</p>
    </footer>

</body>
</html>