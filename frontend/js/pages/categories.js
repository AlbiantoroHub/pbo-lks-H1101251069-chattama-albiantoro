const CategoriesPage = {
    async render() {
        const content = document.getElementById('content');
        content.innerHTML = '<p class="text-gray-500 italic">Memuat data kategori...</p>';

        try {
            const res = await CategoryService.getAll();
            const categories = res.data || [];

            content.innerHTML = `
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <h2 class="text-2xl font-bold text-gray-800">Daftar Kategori</h2>
                    <button onclick="CategoriesPage.openModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded shadow transition flex items-center gap-2">
                        <span>+ Tambah Kategori</span>
                    </button>
                </div>

                <div class="overflow-x-auto bg-white rounded border border-gray-200">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-100 text-gray-700 text-sm">
                            <tr>
                                <th class="py-3 px-4 border-b">ID</th>
                                <th class="py-3 px-4 border-b">Nama Kategori</th>
                                <th class="py-3 px-4 border-b">Deskripsi</th>
                                <th class="py-3 px-4 border-b text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${categories.length === 0 ? `
                                <tr>
                                    <td colspan="4" class="text-center py-6 text-gray-500 italic">Belum ada kategori tersedia.</td>
                                </tr>
                            ` : categories.map(cat => `
                                <tr class="border-b hover:bg-gray-50 text-sm">
                                    <td class="py-3 px-4 text-gray-600">${cat.id}</td>
                                    <td class="py-3 px-4 font-medium text-gray-900">${escapeHtml(cat.nama)}</td>
                                    <td class="py-3 px-4 text-gray-600">${escapeHtml(cat.deskripsi || '-')}</td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="inline-flex space-x-2">
                                            <button onclick="CategoriesPage.editCategory(${cat.id}, '${escapeHtml(cat.nama)}', '${escapeHtml(cat.deskripsi || '')}')" class="text-blue-600 hover:text-blue-800 text-xs font-semibold py-1 px-2 border border-blue-600 rounded">Edit</button>
                                            <button onclick="CategoriesPage.deleteCategory(${cat.id})" class="text-red-600 hover:text-red-800 text-xs font-semibold py-1 px-2 border border-red-600 rounded">Hapus</button>
                                        </div>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>

                <!-- Modal Form Kategori -->
                <div id="category-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 id="modal-category-title" class="text-lg font-bold text-gray-800">Tambah Kategori</h3>
                            <button onclick="CategoriesPage.closeModal()" class="text-gray-400 hover:text-gray-600 font-bold text-xl">&times;</button>
                        </div>
                        <form id="category-form" onsubmit="CategoriesPage.handleSubmit(event)">
                            <input type="hidden" id="category-id">
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
                                <input type="text" id="category-nama" required class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                                <textarea id="category-deskripsi" rows="3" class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            </div>
                            <div class="flex justify-end space-x-2">
                                <button type="button" onclick="CategoriesPage.closeModal()" class="px-4 py-2 border rounded text-sm text-gray-600 hover:bg-gray-100">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            `;
        } catch (error) {
            content.innerHTML = `<div class="p-4 bg-red-100 text-red-700 rounded">Gagal memuat kategori: ${escapeHtml(error.message)}</div>`;
        }
    },

    openModal() {
        document.getElementById('modal-category-title').innerText = 'Tambah Kategori';
        document.getElementById('category-id').value = '';
        document.getElementById('category-nama').value = '';
        document.getElementById('category-deskripsi').value = '';
        document.getElementById('category-modal').classList.remove('hidden');
    },

    editCategory(id, nama, deskripsi) {
        document.getElementById('modal-category-title').innerText = 'Edit Kategori';
        document.getElementById('category-id').value = id;
        document.getElementById('category-nama').value = nama;
        document.getElementById('category-deskripsi').value = deskripsi;
        document.getElementById('category-modal').classList.remove('hidden');
    },

    closeModal() {
        document.getElementById('category-modal').classList.add('hidden');
    },

    async handleSubmit(e) {
        e.preventDefault();
        const id = document.getElementById('category-id').value;
        const data = {
            nama: document.getElementById('category-nama').value,
            deskripsi: document.getElementById('category-deskripsi').value
        };

        try {
            if (id) {
                await CategoryService.update(id, data);
                showToast('Kategori berhasil diperbarui!');
            } else {
                await CategoryService.create(data);
                showToast('Kategori berhasil ditambahkan!');
            }
            this.closeModal();
            this.render();
        } catch (error) {
            showToast(error.message, 'error');
        }
    },

    async deleteCategory(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus kategori ini?')) return;
        try {
            await CategoryService.delete(id);
            showToast('Kategori berhasil dihapus!');
            this.render();
        } catch (error) {
            showToast(error.message, 'error');
        }
    }
};