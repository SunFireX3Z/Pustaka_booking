<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(2); // Jumlah angka di sekitar halaman aktif
?>
<nav aria-label="Page navigation">
  <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
    <!-- Info Halaman -->
    <div class="text-sm text-gray-600">
      <?php
        // Menghitung nomor item pertama dan terakhir di halaman ini
        $firstItem = (($pager->getCurrentPageNumber() - 1) * $pager->getPerPage()) + 1;
        $lastItem = min($pager->getTotal(), $pager->getCurrentPageNumber() * $pager->getPerPage());
      ?>
      Menampilkan
      <span class="font-semibold text-gray-800"><?= $firstItem ?></span>
      -
      <span class="font-semibold text-gray-800"><?= $lastItem ?></span>
      dari
      <span class="font-semibold text-gray-800"><?= $pager->getTotal() ?></span>
      hasil
    </div>

    <!-- Link Paginasi -->
    <div class="flex items-center gap-1">
      <!-- Tombol Previous -->
      <?php if ($pager->hasPrevious()) : ?>
        <a href="<?= $pager->getPrevious() ?>" class="flex items-center justify-center px-3 h-8 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-800 transition-colors">
          <i class="fas fa-chevron-left mr-2"></i>
          <span class="hidden md:inline">Sebelumnya</span>
        </a>
      <?php endif ?>

      <!-- Nomor Halaman -->
      <?php foreach ($pager->links() as $link) : ?>
        <a href="<?= $link['uri'] ?>" class="flex items-center justify-center px-3 h-8 text-sm font-medium border rounded-lg transition-colors <?= $link['active'] ? 'text-white bg-green-600 border-green-600 hover:bg-green-700' : 'text-gray-600 bg-white border-gray-300 hover:bg-gray-100 hover:text-gray-800' ?>">
          <?= $link['title'] ?>
        </a>
      <?php endforeach ?>

      <!-- Tombol Next -->
      <?php if ($pager->hasNext()) : ?>
        <a href="<?= $pager->getNext() ?>" class="flex items-center justify-center px-3 h-8 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-800 transition-colors">
          <span class="hidden md:inline">Berikutnya</span>
          <i class="fas fa-chevron-right ml-2"></i>
        </a>
      <?php endif ?>
    </div>
  </div>
</nav>