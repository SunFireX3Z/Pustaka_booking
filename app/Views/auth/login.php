<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <!-- Tambahkan Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="w-full max-w-sm bg-white rounded-2xl shadow-lg p-8">
    <h2 class="text-2xl font-bold text-center text-blue-600 mb-6">Login Perpustakaan</h2>

    <?php if(session()->getFlashdata('msg')): ?>
      <div class="mb-4 text-sm text-red-700 bg-red-100 border border-red-300 rounded p-3">
        <?= session()->getFlashdata('msg') ?>
      </div>
    <?php endif; ?>

    <form action="<?= base_url('login') ?>" method="post" class="space-y-4">
      <?= csrf_field() ?>
      <div>
        <label class="block text-gray-700 font-medium">Email:</label>
        <input type="text" name="email" required 
               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
      </div>

      <div>
        <label class="block text-gray-700 font-medium">Password:</label>
        <input type="password" name="password" required 
               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
      </div>

      <button type="submit" 
              class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors">
        Login
      </button>
    </form>

    <p class="mt-4 text-center text-gray-600 text-sm">
      Belum punya akun? 
      <a href="<?= base_url('register') ?>" class="text-blue-600 hover:underline">Register</a>
    </p>
  </div>
</body>
</html>