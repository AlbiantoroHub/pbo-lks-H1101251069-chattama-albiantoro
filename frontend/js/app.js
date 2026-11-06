const router = () => {
    const hash = window.location.hash || '#dashboard';

    // Highlight menu navigasi yang sedang aktif
    document.querySelectorAll('.nav-link').forEach(link => {
        if (link.getAttribute('href') === hash) {
            link.classList.add('text-blue-200', 'underline');
        } else {
            link.classList.remove('text-blue-200', 'underline');
        }
    });

    if (hash === '#dashboard') {
        DashboardPage.render();
    } else if (hash === '#books') {
        BooksPage.render();
    } else if (hash === '#categories') {
        if (typeof CategoriesPage !== 'undefined') {
            CategoriesPage.render();
        } else {
            document.getElementById('content').innerHTML = `
                <h2 class="text-2xl font-bold mb-4 text-gray-800">Daftar Kategori</h2>
                <p class="text-gray-500 italic">Halaman kategori akan diintegrasikan pada Langkah 19.</p>
            `;
        }
    }
};

window.addEventListener('hashchange', router);
window.addEventListener('DOMContentLoaded', router);