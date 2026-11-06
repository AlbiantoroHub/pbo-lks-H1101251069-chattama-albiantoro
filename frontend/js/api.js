// Client HTTP terpusat untuk komunikasi dengan backend REST API
const ApiClient = {
    async request(endpoint, options = {}) {
        const url = `${BASE_URL}${endpoint}`;
        const defaultHeaders = {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        };

        const config = {
            ...options,
            headers: {
                ...defaultHeaders,
                ...options.headers
            }
        };

        try {
            const response = await fetch(url, config);
            const data = await response.json().catch(() => null);

            if (!response.ok) {
                const message = data && data.message ? data.message : 'Terjadi kesalahan sistem';
                throw new Error(message);
            }
            return data;
        } catch (error) {
            console.error(`API Error pada ${url}:`, error);
            throw error;
        }
    },

    get(endpoint) {
        return this.request(endpoint, { method: 'GET' });
    },

    post(endpoint, body) {
        return this.request(endpoint, {
            method: 'POST',
            body: JSON.stringify(body)
        });
    },

    put(endpoint, body) {
        return this.request(endpoint, {
            method: 'PUT',
            body: JSON.stringify(body)
        });
    },

    delete(endpoint) {
        return this.request(endpoint, { method: 'DELETE' });
    }
};

// Layanan spesifik entitas
const BookService = {
    getAll: () => ApiClient.get('/books'),
    getById: (id) => ApiClient.get(`/books/${id}`),
    create: (data) => ApiClient.post('/books', data),
    update: (id, data) => ApiClient.put(`/books/${id}`, data),
    delete: (id) => ApiClient.delete(`/books/${id}`)
};

const CategoryService = {
    getAll: () => ApiClient.get('/categories'),
    getById: (id) => ApiClient.get(`/categories/${id}`),
    create: (data) => ApiClient.post('/categories', data),
    update: (id, data) => ApiClient.put(`/categories/${id}`, data),
    delete: (id) => ApiClient.delete(`/categories/${id}`)
};