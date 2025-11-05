<div x-data="{ open: false }" class="relative">
    <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
        <span class="hidden sm:block text-slate-700 font-medium hover:text-indigo-600"><?= esc(session()->get('nama')) ?></span>
        <img src="<?= base_url('uploads/' . esc(session()->get('image') ?? 'default.png')) ?>" alt="User" class="w-9 h-9 rounded-full border-2 border-slate-200 hover:border-indigo-300 transition">
    </button>
    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-1 z-50 border border-slate-200">
        <a href="<?= base_url('member/profile') ?>" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Profil Saya</a>
        <div class="border-t border-slate-100"></div>
        <a href="<?= base_url('logout') ?>" class="block px-4 py-2 text-sm text-red-600 hover:bg-slate-100">Logout</a>
    </div>
</div>