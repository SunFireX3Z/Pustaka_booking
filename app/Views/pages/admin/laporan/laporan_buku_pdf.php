<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title) ?></title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            margin: 30px;
        }

        /* ==== HEADER ==== */
        .header {
            display: flex;
            align-items: flex-start; /* Naikin posisi logo */
            justify-content: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
            text-align: center;
            position: relative;
        }

        .header .logo {
            position: absolute;
            left: 0;
            top: -15px; /* Sedikit naik biar sejajar */
            display: flex;
            align-items: center;
        }

        .header .logo img {
            width: 75px;
            height: 75px;
            object-fit: contain;
        }

        .header .info {
            flex: 1;
            text-align: center;
            margin-left: 20px;
        }

        .header .info h1 {
            margin: 0;
            font-size: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header .info h2 {
            margin: 3px 0 0;
            font-size: 20px;
            font-weight: normal;
        }

        .header .info p {
            margin: 2px 0 0;
            font-size: 12px; /* Dipendekin dikit */
            color: #555;
            line-height: 1.2; /* Rapat tapi tetap terbaca */
        }

        /* ==== TABEL ==== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 10px; /* lebih kecil biar proporsional */
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px 8px; /* lebih rapat dari sebelumnya */
            text-align: left;
        }

        th {
            background-color: #f3f3f3;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
        }

        /* ==== FOOTER ==== */
        .footer {
            position: fixed;
            bottom: -25px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #777;
        }

        .page-number:before {
            content: "Halaman " counter(page);
        }
    </style>
</head>
<body>
    <?php
        $logoFileName = $web_profile['logo'] ?? 'default_logo.png';
        $logoPath = FCPATH . 'uploads/profile/' . $logoFileName;
        if (!file_exists($logoPath)) {
            $logoPath = FCPATH . 'uploads/profile/default_logo.png';
        }
        $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
        $logoData = file_get_contents($logoPath);
        $logoBase64 = 'data:image/' . $logoType . ';base64,' . base64_encode($logoData);
    ?>

    <!-- HEADER -->
    <div class="header">
        <div class="logo">
            <img src="<?= $logoBase64 ?>" alt="Logo">
        </div>
        <div class="info">
            <h1><?= esc($web_profile['nama_instansi'] ?? 'Nama Instansi') ?></h1>
            <h2><?= esc($web_profile['nama_aplikasi'] ?? 'Aplikasi Perpustakaan') ?></h2>
            <p><?= esc($web_profile['alamat'] ?? 'Alamat Instansi') ?>, <?= esc($web_profile['kabupaten_kota'] ?? 'Kota') ?></p>
        </div>
    </div>

    <!-- JUDUL -->
    <h3 style="text-align: center; text-decoration: underline; margin-bottom: 15px;">
        <?= esc($title) ?>
    </h3>

    <!-- TABEL -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Judul Buku</th>
                <th>Pengarang</th>
                <th>Penerbit</th>
                <th>Tahun</th>
                <th>ISBN</th>
                <th>Stok</th>
                <th>Kategori</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($buku as $key => $item): ?>
                <tr>
                    <td><?= $key + 1 ?></td>
                    <td><?= esc($item['judul_buku']) ?></td>
                    <td><?= esc($item['pengarang']) ?></td>
                    <td><?= esc($item['penerbit']) ?></td>
                    <td><?= esc($item['tahun_terbit']) ?></td>
                    <td><?= esc($item['isbn']) ?></td>
                    <td><?= esc($item['stok']) ?></td>
                    <td><?= esc($item['kategori_nama'] ?? 'Tidak ada kategori') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        <span class="page-number"></span>
    </div>
</body>
</html>