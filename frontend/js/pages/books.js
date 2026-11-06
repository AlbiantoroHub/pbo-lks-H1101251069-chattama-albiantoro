const BooksPage = {
    async render() {
        const content = document.getElementById('content');
        content.innerHTML = '<p class="text-gray-500 italic">Memuat data buku...</p>';

        try {
            const res = await BookService.getAll();
            const books = res.data || [];

            content.innerHTML = `
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <h2 class="text-2xl font-bold text-gray-800">Daftar Buku</h2>
                    <button onclick="BooksPage.openModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded shadow transition flex items-center gap-2">
                        <span>+ Tambah Buku</span>
                    </button>
                </div>

                <div class="overflow-x-auto bg-white rounded border border-gray-200">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-100 text-gray-700 text-sm">
                            <tr>
                                <th class="py-3 px-4 border-b">ID</th>
                                <th class="py-3 px-4 border-b">Judul</th>
                                <th class="py-3 px-4 border-b">Penulis</th>
                                <th class="py-3 px-4 border-b">Tahun</th>
                                <th class="py-3 px-4 border-b">Stok</th>
                                <th class="py-3 px-4 border-b text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${books.length === 0 ? `
                                <tr>
                                    <td colspan="6" class="text-center py-6 text-gray-500 italic">Belum ada buku tersedia.</td>
                                </tr>
                            ` : books.map(book => `
                                <tr class="border-b hover:bg-gray-50 text-sm">
                                    <td class="py-3 px-4 text-gray-600">${book.id}</td>
                                    <td class="py-3 px-4 font-medium text-gray-900">${escapeHtml(book.judul)}</td>
                                    <td class="py-3 px-4 text-gray-600">${escapeHtml(book.penulis)}</td>
                                    <td class="py-3 px-4 text-gray-600">${book.tahun}</td>
                                    <td class="py-3 px-4 text-gray-600">${book.stok}</td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="inline-flex space-x-2">
                                            <button onclick="BooksPage.editBook(${book.id}, '${escapeHtml(book.judul)}', '${escapeHtml(book.penulis)}', ${book.tahun}, ${book.stok})" class="text-blue-600 hover:text-blue-800 text-xs font-semibold py-1 px-2 border border-blue-600 rounded">Edit</button>
                                            <button onclick="BooksPage.deleteBook(${book.id})" class="text-red-600 hover:text-red-800 text-xs font-semibold py-1 px-2 border border-red-600 rounded">Hapus</button>
                                        </div>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>

                <!-- Modal Form Buku -->
                <div id="book-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 id="modal-title" class="text-lg font-bold text-gray-800">Tambah Buku</h3>
                            <button onclick="BooksPage.closeModal()" class="text-gray-400 hover:text-gray-600 font-bold text-xl">&times;</button>
                        </div>
                        <form id="book-form" onsubmit="BooksPage.handleSubmit(event)">
                            <input type="hidden" id="book-id">
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Buku</label>
                                <input type="text" id="book-judul" required class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Penulis</label>
                                <input type="text" id="book-penulis" required class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                                    <input type="number" id="book-tahun" required class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                                    <input type="number" id="book-stok" required class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            <div class="flex justify-end space-x-2">
                                <button type="button" onclick="BooksPage.closeModal()" class="px-4 py-2 border rounded text-sm text-gray-600 hover:bg-gray-100">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            `;
        } catch (error) {
            content.innerHTML = `<div class="p-4 bg-red-100 text-red-700 rounded">Gagal memuat buku: ${escapeHtml(error.message)}</div>`;
        }
    },

    openModal() {
        document.getElementById('modal-title').innerText = 'Tambah Buku';
        document.getElementById('book-id').value = '';
        document.getElementById('book-judul').value = '';
        document.getElementById('book-penulis').value = '';
        document.getElementById('book-tahun').value = '';
        document.getElementById('book-stok').value = '';
        document.getElementById('book-modal').classList.remove('hidden');
    },

    editBook(id, judul, penulis, tahun, stok) {
        document.getElementById('modal-title').innerText = 'Edit Buku';
        document.getElementById('book-id').value = id;
        document.getElementById('book-judul').value = judul;
        document.getElementById('book-penulis').value = penulis;
        document.getElementById('book-tahun').value = tahun;
        document.getElementById('book-stok').value = stok;
        document.getElementById('book-modal').classList.remove('hidden');
    },

    closeModal() {
        document.getElementById('book-modal').classList.add('hidden');
    },

    async handleSubmit(e) {
        e.preventDefault();
        const id = document.getElementById('book-id').value;
        const data = {
            judul: document.getElementById('book-judul').value,
            penulis: document.getElementById('book-penulis').value,
            tahun: parseInt(document.getElementById('book-tahun').value),
            stok: parseInt(document.getElementById('book-stok').value)
        };

        try {
            if (id) {
                await BookService.update(id, data);
                showToast('Buku berhasil diperbarui!');
            } else {
                await BookService.create(data);
                showToast('Buku berhasil ditambahkan!');
            }
            this.closeModal();
            this.render();
        } catch (error) {
            showToast(error.message, 'error');
        }
    },

    async deleteBook(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus buku ini?')) return;
        try {
            await BookService.delete(id);
            showToast('Buku berhasil dihapus!');
            this.render();
        } catch (error) {
            showToast(error.message, 'error');
        }
    }
};