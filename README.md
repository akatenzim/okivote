<div align="center">

  # ⚡ OkiVote — Digital Voting Platform

  **Platform Perhitungan Suara & Voting Digital Real-Time High-Performance untuk Ajang Pemilihan & Event Regional.**

</div>

---

## 📌 Tentang Proyek

**OkiVote** adalah platform *pay-per-vote* dan kompetisi digital berbasis web yang dirancang untuk mendukung penyelenggaraan ajang pemilihan seperti pageant, talent show, duta wisata, dan penghargaan regional.

Mengusung visual **Editorial Warm Elegance**, OkiVote dibangun dengan fokus pada kestabilan tinggi saat *peak traffic*, kemudahan *checkout* bagi pemilih, serta proteksi audit log ketat untuk mencegah kecurangan perhitungan suara.

---

## ✨ Fitur Utama

### 🌐 Pengalaman Publik & Pemilih (Voter Experience)
* **Editorial Event Directory:** Filter status kompetisi interaktif (`Sedang Berlangsung`, `Akan Datang`, `Selesai`).
* **Optional Voter Identity:** Opsi pemilih anonim (*Anonymous*) untuk fleksibilitas privasi voter tanpa mengurangi validitas transaksi.
* **Live Vote Toast Notifications:** Widget notifikasi *top-center* interaktif berbasis Alpine.js yang menampilkan 10 transaksi *real-time* terbaru.
* **Leaderboard & Vote Ledger:** Transparansi perhitungan persentase dan akumulasi dukungan secara langsung.
* **Social Sharing Integration:** Berbagi profil kandidat ke WhatsApp/Clipboard dengan metadata OpenGraph yang terstruktur.

### 🛡️ Panel Administrator & Keamanan
* **Audit Trail & Security Logs:** Recording otomatis setiap tindakan sensitif admin (perubahan harga vote, status event, kredensial, IP address).
* **Event Pause / Suspend Mechanism:** Penguncian sesi voting secara instan tanpa menyembunyikan katalog event dari publik.
* **Admin Profile Management:** Pengelolaan kredensial admin secara mandiri dan terenkripsi.
* **Payment Gateway Ready:** Integrasi pembayaran hemat biaya (*pay-as-you-use*) via QRIS dan Virtual Account.

---

## 🛠️ Stack Teknologi & Arsitektur

* **Backend:** Laravel 11 (PHP 8.2+)
* **Frontend:** Blade, Tailwind CSS, Alpine.js (Vite)
* **Database:** MySQL / MariaDB (Arsitektur *Vote Ledger* & *Database Transaction Handling*)
* **Security & Auth:** Custom Guard, Form Request Validation, Dedicated Audit Log Service

---

## 💼 Hak Cipta & Lisensi

© 2026 **OkiVote**. Hak Cipta Dilindungi Undang-Undang.

Proyek ini dikembangkan sebagai portofolio profesional dan perangkat lunak komersial milik pribadi (*Proprietary Software*). Kode sumber tidak disediakan untuk penggunaan umum atau redistribusi open-source tanpa izin tertulis dari pemilik hak cipta.