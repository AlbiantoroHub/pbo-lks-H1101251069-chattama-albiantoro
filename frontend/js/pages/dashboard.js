const DashboardPage = {
    async render() {
        const content = document.getElementById('content');
        content.innerHTML = '<p class="text-gray-500 italic">Memuat data dashboard...</p>';

        try {
            const [booksRes, catRes] = await Promise.all([
                BookService.getAll(),
                CategoryService.getAll()
            ]);

            const books = booksRes.data || [];
            const categories = catRes.data || [];
            const totalBooks = books.length;
            const totalCategories = categories.length;
            const recentBooks = books.slice(-5).reverse();

            content.innerHTML = `
                <h2 class="text-2xl font-bold mb-6 text-gray-800">Dashboard Perpustakaan</h2>
                
                <!-- Kartu Statistik -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-blue-600 text-white rounded-lg p-6 shadow-md flex items-center justify-between">
                        <div>
                            <p class="text-sm uppercase font-semibold text-blue-100">Total Buku</p>
                            <h3 class="text-4xl font-extrabold mt-1">${totalBooks}</h3>
                        </div>
                        <div class="text-5xl opacity-80">📚</div>
                    </div>
                    <div class="bg-orange-500 text-white rounded-lg p-6 shadow-md flex items-center justify-between">
                        <div>
                            <p class="text-sm uppercase font-semibold text-orange-100">Kategori</p>
                            <h3 class="text-4xl font-extrabold mt-1">${totalCategories}</h3>
                        </div>
                        <div class="text-5xl opacity-80">🏷️</div>
                    </div>
                </div>

                <!-- 5 Buku Terbaru -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <h3 class="text-lg font-bold mb-4 text-gray-700">5 Buku Terbaru</h3>
                    ${recentBooks.length === 0 ? '<p class="text-gray-500 italic">Belum ada buku tersedia.</p>' : `
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b text-gray-600 text-sm">
                                        <th class="py-2 px-3">Judul</th>
                                        <th class="py-2 px-3">Penulis</th>
                                        <th class="py-2 px-3">Tahun</th>
                                        <th class="py-2 px-3">Stok</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${recentBooks.map(b => `
                                        <tr class="border-b hover:bg-white text-sm">
                                            <td class="py-2 px-3 font-medium text-gray-800">${escapeHtml(b.judul)}</td>
                                            <td class="py-2 px-3 text-gray-600">${escapeHtml(b.penulis)}</td>
                                            <td class="py-2 px-3 text-gray-600">${escapeHtml(String(b.tahun))}</td>
                                            <td class="py-2 px-3 text-gray-600"><span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-xs font-semibold">${escapeHtml(String(b.stok))}</span></td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    `}
                </div>
            `;
        } catch (error) {
            content.innerHTML = `<div class="p-4 bg-red-100 text-red-700 rounded">Gagal memuat dashboard: ${escapeHtml(error.message)}</div>`;
        }
    }
};