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
            align-items: flex-start;
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
            top: -15px;
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
            font-size: 12px;
            color: #555;
            line-height: 1.2;
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

    <!-- TABEL DATA ANGGOTA -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>NISN</th>
                <th>Kelas</th>
                <th>Jenis Kelamin</th>
                <th>No. HP</th>
                <th>Tgl Bergabung</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($anggota as $key => $item): ?>
                <tr>
                    <td><?= $key + 1 ?></td>
                    <td><?= esc($item['nama']) ?></td>
                    <td><?= esc($item['email']) ?></td>
                    <td><?= esc($item['nisn'] ?? '-') ?></td>
                    <td><?= esc($item['kelas'] ?? '-') ?></td>
                    <td><?= esc($item['jenis_kelamin'] ?? '-') ?></td>
                    <td><?= esc($item['no_hp'] ?? '-') ?></td>
                    <td><?= date('d M Y', strtotime($item['tanggal_input'])) ?></td>
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