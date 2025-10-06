<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome untuk ikon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .form-card {
      opacity: 0;
      transform: translateY(25px) scale(0.98);
      transition: opacity 0.4s ease-out, transform 0.4s ease-out;
    }
    .form-card.is-visible {
      opacity: 1;
      transform: translateY(0) scale(1);
    }

    /* Custom Scrollbar */
     ::-webkit-scrollbar {
      width: 6px;
      height: 6px;
     }
     ::-webkit-scrollbar-track {
      background: transparent;
     }
     ::-webkit-scrollbar-thumb {
      background: #cbd5e1; /* slate-300 */
      border-radius: 10px;
     }
     ::-webkit-scrollbar-thumb:hover {
      background: #94a3b8; /* slate-400 */
     }
  </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-purple-100 flex items-center justify-center min-h-screen p-4">
  <div id="login-card" class="form-card w-full max-w-sm bg-white rounded-2xl shadow-xl p-8 transition-transform duration-300 hover:scale-105">
    <div class="flex flex-col items-center mb-6">
      <div class="bg-blue-100 p-3 rounded-full mb-3">
        <i class="fas fa-book-reader text-3xl text-blue-600"></i>
      </div>
      <h2 class="text-2xl font-bold text-center text-gray-800">Login Perpustakaan</h2>
      <p class="text-sm text-gray-500">Silakan masuk untuk melanjutkan</p>
    </div>

    <?php if(session()->getFlashdata('msg')): ?>
      <div class="mb-4 text-sm text-red-800 bg-red-100 border-l-4 border-red-500 rounded-r p-3 flex items-center">
        <i class="fas fa-exclamation-triangle mr-2"></i>
        <?= session()->getFlashdata('msg') ?>
      </div>
    <?php endif; ?>

    <form action="<?= base_url('login') ?>" method="post" class="space-y-4">
      <?= csrf_field() ?>
      <div class="relative">
        <label for="email" class="sr-only">Email:</label>
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fas fa-envelope"></i></span>
        <input type="email" name="email" id="email" placeholder="Email" required 
               class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition">
      </div>

      <div class="relative">
        <label for="password" class="sr-only">Password:</label>
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fas fa-lock"></i></span>
        <input type="password" name="password" id="password" placeholder="Password" required
               class="w-full pl-10 pr-10 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition">
        <span id="togglePassword" class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer text-gray-400 hover:text-gray-600">
          <i class="fas fa-eye"></i>
        </span>
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

  <script>
    // Animasi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
      const loginCard = document.getElementById('login-card');
      loginCard.classList.add('is-visible');
    });

    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    const icon = togglePassword.querySelector('i');

    togglePassword.addEventListener('click', function () {
      // Toggle tipe atribut input
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      
      // Toggle ikon mata
      icon.classList.toggle('fa-eye');
      icon.classList.toggle('fa-eye-slash');
    });
  </script>
</body>
</html>