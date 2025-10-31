<div class="flex items-center space-x-3">
  <?php 
    // Mengambil data dari sesi dengan fallback default
    $userImage = session()->get('image') ?? 'default.png';
    $userName = session()->get('nama') ?? 'User';
    $userRole = session()->get('role') ?? 'Peran';
  ?>
  <div class="text-right">
    <span class="font-medium block text-sm"><?= esc($userName) ?></span>
    <span class="text-xs text-gray-500 block"><?= esc($userRole) ?></span>
  </div>
  <img src="<?= base_url('uploads/' . $userImage) ?>" alt="User" class="w-8 h-8 rounded-full object-cover">
</div>