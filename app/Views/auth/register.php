<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <!-- Tambahkan Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">
    <h2 class="text-2xl font-bold text-center text-blue-600 mb-6">Form Registrasi</h2>
    
    <?php if(isset($validation)): ?>
      <div class="mb-4 text-red-600 text-sm bg-red-100 p-3 rounded">
        <?= $validation->listErrors() ?>
      </div>
    <?php endif; ?>

    <form action="<?= base_url('register') ?>" method="post" class="space-y-4">
      <?= csrf_field() ?>
      <div>
        <label class="block text-gray-700 font-medium">Nama:</label>
        <input type="text" name="nama" value="<?= set_value('nama') ?>" 
               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
      </div>

      <div>
        <label class="block text-gray-700 font-medium">Email:</label>
        <input type="email" name="email" value="<?= set_value('email') ?>" 
               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
      </div>

      <div>
        <label class="block text-gray-700 font-medium">Password:</label>
        <input type="password" name="password" 
               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
      </div>

      <div>
        <label class="block text-gray-700 font-medium">Konfirmasi Password:</label>
        <input type="password" name="passconf" 
               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
      </div>

      <button type="submit" 
              class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors">
        Register
      </button>
    </form>

    <p class="mt-4 text-center text-gray-600 text-sm">
      Sudah punya akun? 
      <a href="<?= base_url('login') ?>" class="text-blue-600 hover:underline">Login</a>
    </p>
  </div>
</body>
</html>