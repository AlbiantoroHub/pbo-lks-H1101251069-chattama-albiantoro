# Proyek REST API Perpustakaan & Frontend Terpisah

Portofolio Praktikum Pemrograman Berbasis Objek (PBO) - Program Studi Sistem Informasi, Universitas Tanjungpura.

## 👤 Identitas Mahasiswa
- **Nama Lengkap:** Chattama Albiantoro
- **NIM:** H1101251069
- **Program Studi:** Sistem Informasi
- **Mata Kuliah:** Pemrograman Berbasis Objek (PBO)
- **Dosen Pengampu:** Syahru Rahmayuda, M.Kom

---

## 🚀 Fitur Utama
1. **Arsitektur REST API Modern:** Menerapkan PSR-4 Autoloading, pola MVC, Repository Pattern, dan Dependency Injection Container mini.
2. **Prinsip SOLID:** Memisahkan tanggung jawab (Single Responsibility) antara Controller, Service, Repository, dan Model.
3. **Penyimpanan MySQL:** Skema database relasional untuk entitas `books` dan `categories`.
4. **Keamanan & Validasi:** Error handling terstruktur (HTTP 200, 201, 404, 422), validasi input, serta header CORS untuk integrasi lintas origin.
5. **Frontend Terpisah:** Dibangun menggunakan Vanilla JavaScript, Tailwind CSS CDN, serta routing dinamis berbasis URL Hash.

---

## 🛠️ Cara Menjalankan Proyek

### 1. Setup Backend (Port 8000)
1. Salin file environment:
   ```bash
   cp .env.example .env