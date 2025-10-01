<!-- Modal Tambah Buku -->
<div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-start justify-center z-50 overflow-y-auto py-10">
  <div id="addModalContent" class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6 relative transform transition-all">
    <button onclick="closeAddModal()" class="absolute top-2 right-2 text-gray-500 hover:text-red-500">
      <i class="fas fa-times"></i>
    </button>
    <h2 class="text-xl font-bold mb-4">Tambah Buku Baru</h2>
    <form action="<?= base_url('buku') ?>" method="post" enctype="multipart/form-data" class="space-y-3">
      <?= csrf_field() ?>
      <input type="text" name="judul_buku" placeholder="Judul Buku" class="w-full px-3 py-2 border rounded" value="<?= old('judul_buku') ?>" required>
      <select name="id_kategori" class="w-full px-3 py-2 border rounded" required>
        <option value="">Pilih Kategori</option>
        <?php foreach($kategori as $k): ?>
          <option value="<?= $k['id_kategori'] ?>" <?= old('id_kategori') == $k['id_kategori'] ? 'selected' : '' ?>><?= esc($k['nama_kategori']) ?></option>
        <?php endforeach; ?>
      </select>
      <input type="text" name="pengarang" placeholder="Pengarang" class="w-full px-3 py-2 border rounded" value="<?= old('pengarang') ?>" required>
      <input type="text" name="penerbit" placeholder="Penerbit" class="w-full px-3 py-2 border rounded" value="<?= old('penerbit') ?>" required>
      <input type="number" name="tahun_terbit" placeholder="Tahun Terbit" class="w-full px-3 py-2 border rounded" value="<?= old('tahun_terbit') ?>" required>
      <input type="text" name="isbn" placeholder="ISBN" class="w-full px-3 py-2 border rounded" value="<?= old('isbn') ?>" required>
      <input type="number" name="stok" placeholder="Stok" class="w-full px-3 py-2 border rounded" value="<?= old('stok') ?>" required>
      <textarea name="hipotesis" placeholder="Hipotesis Buku" class="w-full px-3 py-2 border rounded" rows="3" required><?= old('hipotesis') ?></textarea>
      <div>
        <label class="block text-sm font-medium text-gray-700">Cover Buku</label>
        <input type="file" name="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
      </div>
      <div class="flex justify-end space-x-2 pt-4">
        <button type="button" onclick="closeAddModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit Buku -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-start justify-center z-50 overflow-y-auto py-10">
  <div id="editModalContent" class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6 relative transform transition-all">
    <button onclick="closeEditModal()" class="absolute top-2 right-2 text-gray-500 hover:text-red-500">
      <i class="fas fa-times"></i>
    </button>
    <h2 class="text-xl font-bold mb-4">Edit Buku</h2>
    <form id="editForm" action="" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <div class="grid grid-cols-2 gap-4">
        <div class="col-span-2">
          <label for="edit_judul_buku" class="block text-sm font-medium text-gray-700">Judul Buku</label>
          <input type="text" name="judul_buku" id="edit_judul_buku" class="mt-1 w-full px-3 py-2 border rounded" required>
        </div>
        <div>
          <label for="edit_id_kategori" class="block text-sm font-medium text-gray-700">Kategori</label>
          <select name="id_kategori" id="edit_id_kategori" class="mt-1 w-full px-3 py-2 border rounded" required>
            <option value="">Pilih Kategori</option>
            <?php foreach($kategori as $k): ?>
              <option value="<?= $k['id_kategori'] ?>"><?= esc($k['nama_kategori']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label for="edit_pengarang" class="block text-sm font-medium text-gray-700">Pengarang</label>
          <input type="text" name="pengarang" id="edit_pengarang" class="mt-1 w-full px-3 py-2 border rounded" required>
        </div>
        <div>
          <label for="edit_penerbit" class="block text-sm font-medium text-gray-700">Penerbit</label>
          <input type="text" name="penerbit" id="edit_penerbit" class="mt-1 w-full px-3 py-2 border rounded" required>
        </div>
        <div>
          <label for="edit_tahun_terbit" class="block text-sm font-medium text-gray-700">Tahun Terbit</label>
          <input type="number" name="tahun_terbit" id="edit_tahun_terbit" class="mt-1 w-full px-3 py-2 border rounded" required>
        </div>
        <div>
          <label for="edit_isbn" class="block text-sm font-medium text-gray-700">ISBN</label>
          <input type="text" name="isbn" id="edit_isbn" class="mt-1 w-full px-3 py-2 border rounded" required>
        </div>
        <div>
          <label for="edit_stok" class="block text-sm font-medium text-gray-700">Stok</label>
          <input type="number" name="stok" id="edit_stok" class="mt-1 w-full px-3 py-2 border rounded" required>
        </div>
        <div class="col-span-2">
          <label for="edit_hipotesis" class="block text-sm font-medium text-gray-700">Hipotesis</label>
          <textarea name="hipotesis" id="edit_hipotesis" class="mt-1 w-full px-3 py-2 border rounded" rows="3" required></textarea>
        </div>
        <div class="col-span-2">
        <label class="block text-sm font-medium text-gray-700">Ganti Cover Buku (Opsional)</label>
        <img id="edit_current_image" src="" alt="Current Cover" class="w-24 h-auto my-2">
        <input type="file" name="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
      </div>
      </div>
      <div class="flex justify-end space-x-2 pt-4">
        <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>