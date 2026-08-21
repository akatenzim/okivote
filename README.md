<div align="center">

  # ⚡ OkiVote — Digital Voting Platform

  **Platform Perhitungan Suara & Voting Digital Real-Time yang Aman, Transparan, dan Berkinerja Tinggi.**

</div>

---

## 📌 Tentang OkiVote

**OkiVote** adalah platform voting dan kompetisi digital berbasis web yang dirancang khusus untuk mendukung penyelenggaraan ajang pemilihan, pageant, talent show, dan penghargaan (*awards*). Mengusung bahasa desain **Editorial Warm Elegance**, OkiVote menyajikan pengalaman pengguna yang intuitif, responsif, dan interaktif di berbagai ukuran layar.

Dibangun di atas **Laravel 11**, arsitektur sistem ini mengutamakan **keamanan data, integritas transaksi, dan efisiensi biaya operasional** (menggunakan skema *Pay-As-You-Use* tanpa beban bulanan).

---

## ✨ Fitur Unggulan

### 🌐 Sisi Publik (Voter & Pendukung)
* **Editorial Event Directory:** Katalog kompetisi interaktif dengan tab filter status (`Sedang Berlangsung`, `Akan Datang`, `Selesai`).
* **Optional Voter Identity:** Mendukung pemilih anonim (*Anonymous*) untuk privasi voter yang lebih fleksibel.
* **Live Vote Toast Notifications:** Widget notifikasi melayang interaktif yang menampilkan 10 transaksi *real-time* terbaru per event.
* **Leaderboard & Vote Ledger:** Perhitungan persentase dan total dukungan yang transparan serta terverifikasi secara presisi.
* **Social Sharing Integration:** Fitur bagikan link profil kandidat langsung ke WhatsApp/Clipboard dengan metadata OpenGraph dinamis.

### 🛡️ Sisi Admin (Penyelenggara & Keamanan)
* **Audit Trail & Security Logs:** Pencatatan otomatis setiap tindakan sensitif admin (perubahan harga vote, status event, perubahan kredensial, IP address).
* **Event Pause / Suspend Mechanism:** Kemampuan mengunci sesi voting secara instan tanpa menyembunyikan halaman event dari publik.
* **Admin Profile Management:** Pengelolaan profil mandiri untuk memperbarui alamat email dan kata sandi secara aman.
* **Payment Gateway Ready:** Terintegrasi dengan saluran pembayaran QRIS dan Virtual Account.

---

## 🛠️ Stack Teknologi

* **Backend Framework:** Laravel 11 (PHP 8.2+)
* **Frontend Framework:** Blade, Tailwind CSS, Alpine.js (Vite)
* **Database:** MySQL / MariaDB (disertai arsitektur *Vote Ledger* & *Transaction Handling*)
* **Security & Auth:** Custom Guard, Form Request Validation, Audit Log Service
* **Payment Integration Strategy:** Midtrans / Xendit (Skema tanpa biaya langganan / *zero setup fee*)

---

## 🚀 Panduan Instalasi Lokal

Ikuti langkah-langkah berikut untuk menjalankan OkiVote di lingkungan pengembangan lokal (*local environment*):

1. **Clone Repositori**
   git clone https://github.com/username/okivote.git
   cd okivote

2. **Install Dependensi PHP & JavaScript**
   composer install
   npm install

3. **Konfigurasi Environment (.env)**
   Salin file konfigurasi `.env.example` menjadi `.env`:
   cp .env.example .env

   Sesuaikan konfigurasi database dan timezone pada file `.env`:
   APP_NAME=OkiVote
   APP_TIMEZONE=Asia/Jakarta

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=okivote_db
   DB_USERNAME=root
   DB_PASSWORD=

4. **Generate Application Key & Migrasi Database**
   php artisan key:generate
   php artisan migrate --seed

5. **Buat Storage Symlink**
   php artisan storage:link

6. **Jalankan Development Server**
   Terminal 1 (Laravel Server):
   php artisan serve

   Terminal 2 (Asset Bundler):
   npm run dev

Buka browser dan akses platform melalui http://localhost:8000.

---

## 📄 Commit Conventions

Proyek ini menerapkan standar Conventional Commits:

* `feat(...)`: Penambahan fitur baru.
* `fix(...)`: Perbaikan bug atau penanganan eror.
* `style(...)`: Penyesuaian antarmuka/UI, CSS, atau refactor tampilan.
* `refactor(...)`: Perapihan struktur kode tanpa mengubah fungsionalitas.
* `docs(...)`: Perubahan dokumentasi proyek.

---

## ⚖️ Lisensi

Sistem ini dirilis di bawah Lisensi MIT. Bebas untuk dikembangkan dan disesuaikan untuk kebutuhan komersial maupun non-komersial.