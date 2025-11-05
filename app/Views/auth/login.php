<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login - <?= esc($web_profile['nama_aplikasi'] ?? 'Aplikasi Perpustakaan') ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body { 
      font-family: 'Poppins', sans-serif; 
      background-image: url('<?= base_url('image_assets/greenbackground.jpg') ?>');
      background-size: cover;
      background-position: center;
    }
    .glass-card {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(15px);
      box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.2);
      border: 1px solid rgba(255, 255, 255, 0.18);
    }
  </style>
</head>

<body class="flex items-center justify-center min-h-screen p-4">
  <div id="login-card" class="glass-card w-full max-w-sm rounded-2xl p-8">
    <div class="flex flex-col items-center mb-8 text-center">
      <img src="<?= base_url('uploads/profile/' . esc($web_profile['logo'] ?? 'default_logo.png')) ?>" alt="Logo" class="h-16 w-auto mb-4 object-contain">
      <h2 class="text-2xl font-bold text-white"><?= esc($web_profile['nama_instansi'] ?? 'Nama Instansi') ?></h2>
      <p class="text-sm text-gray-200">Selamat datang di <?= esc($web_profile['nama_aplikasi'] ?? 'Aplikasi Perpustakaan') ?></p>
    </div>

    <?php if(session()->getFlashdata('msg')): ?>
      <div class="mb-4 text-sm text-red-800 bg-red-100 border-l-4 border-red-500 rounded-r p-3 flex items-center">
        <i class="fas fa-exclamation-triangle mr-2"></i>
        <?= session()->getFlashdata('msg') ?>
      </div>
    <?php endif; ?>

    <form action="<?= base_url('login') ?>" method="post" class="space-y-5">
      <?= csrf_field() ?>

      <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-300"><i class="fas fa-envelope"></i></span>
        <input type="email" name="email" id="email" placeholder="Email"
               class="w-full pl-10 pr-4 py-2.5 border border-gray-500 bg-white/20 text-white placeholder-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-green-400 focus:outline-none transition"
               required>
      </div>

      <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-300"><i class="fas fa-lock"></i></span>
        <input type="password" name="password" id="password" placeholder="Password"
               class="w-full pl-10 pr-10 py-2.5 border border-gray-500 bg-white/20 text-white placeholder-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-green-400 focus:outline-none transition"
               required>
        <span id="togglePassword" class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer text-gray-300 hover:text-white">
          <i class="fas fa-eye"></i>
        </span>
      </div>

      <button type="submit" class="w-full bg-green-600 text-white py-2.5 rounded-lg font-semibold hover:bg-green-700 transition-colors shadow-md hover:shadow-lg">
        <i class="fas fa-sign-in-alt mr-2"></i>Login
      </button>
    </form>
  </div>

  <script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    const icon = togglePassword.querySelector('i');

    togglePassword.addEventListener('click', () => {
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      icon.classList.toggle('fa-eye');
      icon.classList.toggle('fa-eye-slash');
    });
  </script>
</body>
</html>