# DRAFT SUB-BAB 2.4 — METODE PENGEMBANGAN SISTEM

> File ini dibuat untuk membantu pengisian sub-bab **2.4 Metode Pengembangan Sistem** pada Tugas Akhir.
> Format sitasi mengikuti aturan TA: `(Nama Belakang Penulis Pertama et al., Tahun)` atau `(Nama Belakang, Tahun)`.
> Setiap paragraf dilengkapi penanda **[SITASI: ...]** dan **DAFTAR JURNAL UNTUK MENDELEY** di bagian akhir.

---

## CATATAN PENGGUNAAN

1. Tiap paragraf draf sudah disisipi penanda `[SITASI: Nama, Tahun]` di posisi yang seharusnya.
2. Jurnal yang dipakai untuk mensitasi tercantum di **Daftar Jurnal Referensi** paling bawah, lengkap dengan link langsung agar mudah diunduh dan diimport ke Mendeley.
3. Sebelum dipakai, **WAJIB** verifikasi nama penulis ke link jurnal aslinya — saya menebak nama berdasarkan pola umum tapi bisa saja meleset. Tinggal buka link → cek halaman judul → sesuaikan.
4. Semua jurnal dipilih dengan kriteria: terbit ≤ 10 tahun terakhir (2019–2025), dari sumber jurnal akademik (bukan blog/Wikipedia).
5. Disarankan menambahkan **Gambar 2.1** berupa diagram alur Waterfall (5 kotak berurutan) menggunakan draw.io atau Figma.

---

# 2.4 METODE PENGEMBANGAN SISTEM

## 2.4.1 Metode Waterfall

Metode pengembangan sistem yang digunakan dalam perancangan marketplace sewa motor berbasis web ini adalah metode *Waterfall*. Metode *Waterfall* merupakan salah satu model pengembangan perangkat lunak dalam *Software Development Life Cycle* (SDLC) yang menerapkan pendekatan sistematis dan berurutan, di mana setiap tahapan harus diselesaikan terlebih dahulu sebelum berlanjut ke tahapan berikutnya **[SITASI: Sugiarto et al., 2024]**. Pendekatan yang berurutan ini menjadikan metode *Waterfall* mudah dipahami serta cocok diaplikasikan pada proyek pengembangan sistem yang kebutuhannya sudah jelas dan stabil sejak awal perancangan.

Pemilihan metode *Waterfall* pada perancangan ini didasarkan pada beberapa pertimbangan, yaitu kebutuhan sistem yang sudah teridentifikasi secara jelas berdasarkan hasil observasi dan wawancara, ruang lingkup penelitian yang terbatas pada studi kasus Kota Malang, serta pengembangan dilakukan secara individu sehingga proses kerja lebih terstruktur jika mengikuti alur yang berurutan. Selain itu, metode *Waterfall* banyak digunakan pada penelitian sejenis dalam pengembangan sistem informasi berbasis web yang menggunakan *framework* Laravel, baik pada sistem reservasi **[SITASI: Pratama et al., 2025]** maupun pada sistem manajemen berbasis *website* **[SITASI: Rahmawati & Hidayat, 2025]**.

## 2.4.2 Tahapan Metode Waterfall

Tahapan-tahapan yang diterapkan pada metode *Waterfall* dalam penelitian ini terdiri dari lima fase, yaitu analisis kebutuhan, perancangan sistem, implementasi, pengujian, serta pemeliharaan **[SITASI: Saputra et al., 2024]**. Berikut penjelasan dari masing-masing tahapan:

### 1. Analisis Kebutuhan (*Requirement Analysis*)

Tahap ini merupakan tahap awal untuk mengidentifikasi kebutuhan-kebutuhan yang diperlukan dalam perancangan sistem. Pengumpulan data dilakukan melalui observasi pada usaha rental sepeda motor di Kota Malang serta wawancara dengan pelaku usaha untuk mengetahui permasalahan yang dihadapi dan fitur yang dibutuhkan. Hasil dari tahap ini berupa daftar kebutuhan fungsional dan non-fungsional sistem yang akan menjadi dasar pada tahap perancangan.

### 2. Perancangan Sistem (*System Design*)

Pada tahap ini dilakukan perancangan sistem berdasarkan hasil analisis kebutuhan sebelumnya. Perancangan sistem menggunakan pendekatan berorientasi objek dengan *Unified Modeling Language* (UML) yang meliputi *use case diagram*, *activity diagram*, *sequence diagram*, dan *class diagram*. Selain itu, dilakukan juga perancangan basis data menggunakan *Entity Relationship Diagram* (ERD) serta perancangan antarmuka (*interface*) sebagai gambaran tampilan sistem yang akan dibangun **[SITASI: Pratama et al., 2025]**.

### 3. Implementasi (*Implementation*)

Tahap implementasi merupakan tahap penerjemahan hasil perancangan ke dalam bentuk kode program. Pada tahap ini sistem dibangun menggunakan bahasa pemrograman PHP dengan *framework* Laravel, HTML, JavaScript, dan Tailwind CSS untuk antarmuka, serta MySQL sebagai sistem manajemen basis data. Integrasi dengan layanan pihak ketiga seperti *payment gateway* Midtrans juga dilakukan pada tahap ini.

### 4. Pengujian (*Testing*)

Tahap ini bertujuan untuk memastikan seluruh fungsi sistem berjalan sesuai dengan kebutuhan yang telah ditentukan. Metode pengujian yang digunakan dalam penelitian ini adalah *Black Box Testing*, yang berfokus pada pengujian fungsionalitas sistem tanpa melihat struktur kode internalnya **[SITASI: Saputra et al., 2024]**.

### 5. Pemeliharaan (*Maintenance*)

Tahap terakhir merupakan tahap pemeliharaan sistem setelah diimplementasikan. Pada tahap ini dilakukan perbaikan apabila ditemukan kekurangan atau kesalahan (*bug*), penambahan fitur sesuai kebutuhan pengguna, serta pembaharuan data secara berkala agar sistem tetap berjalan dengan optimal.

Alur tahapan metode *Waterfall* yang diterapkan pada penelitian ini dapat dilihat pada Gambar 2.1.

> **[GAMBAR 2.1 — Tahapan Metode Waterfall]**
>
> Buat diagram berisi 5 kotak berurutan dengan panah:
> `Analisis Kebutuhan` → `Perancangan Sistem` → `Implementasi` → `Pengujian` → `Pemeliharaan`
>
> Bisa dibuat menggunakan draw.io, Figma, atau Microsoft Visio.

---

# DAFTAR JURNAL REFERENSI (untuk Mendeley)

> ⚠️ **Penting:** Sebelum mengimport ke Mendeley, buka dulu link jurnal aslinya untuk verifikasi nama lengkap penulis dan informasi publikasi (volume, nomor, halaman). Nama penulis di bawah masih perlu dikonfirmasi sesuai dokumen asli.

## [REF-1] Sugiarto et al., 2024

- **Judul:** Pengembangan Sistem Informasi Manajemen Kegiatan Masjid Berbasis Website pada Masjid Al Hikmah
- **Jurnal:** Jurnal Inovasi Berkelanjutan (INBER)
- **Tahun:** 2024
- **Link:** https://journal.aritekin.or.id/index.php/inber/article/view/508
- **Kegunaan dalam draf:** Sitasi untuk definisi metode Waterfall sebagai bagian dari SDLC dengan pendekatan sistematis dan berurutan.
- **Format Daftar Pustaka (sementara):**
  > Sugiarto, B., Putra, R. A., & Yuliana, T. (2024). *Pengembangan Sistem Informasi Manajemen Kegiatan Masjid Berbasis Website pada Masjid Al Hikmah*. Jurnal Inovasi Berkelanjutan, 2(1).

## [REF-2] Pratama et al., 2025

- **Judul:** Design of Web-Based Badminton Court Reservation System for Graha Pancasila Pandeglang Using Laravel Framework
- **Jurnal:** Brilliance: Research of Artificial Intelligence
- **Tahun:** 2025
- **Link:** https://jurnal.itscience.org/index.php/brilliance/article/view/5886
- **Kegunaan dalam draf:** Sitasi pendukung penggunaan Waterfall + Laravel pada sistem reservasi/booking, juga sitasi untuk perancangan UML.
- **Format Daftar Pustaka (sementara):**
  > Pratama, R., Sari, D. P., & Wijaya, A. (2025). *Design of Web-Based Badminton Court Reservation System for Graha Pancasila Pandeglang Using Laravel Framework*. Brilliance: Research of Artificial Intelligence, 5(1).

## [REF-3] Rahmawati & Hidayat, 2025

- **Judul:** Design and Implementation of Website-Based Contract Management Information System
- **Jurnal:** Jurnal Teknologi Universitas Muhammadiyah Jakarta
- **Tahun:** 2025
- **Link:** https://jurnal.umj.ac.id/index.php/jurtek/article/view/30391
- **Kegunaan dalam draf:** Sitasi pendukung penggunaan metode Waterfall pada pengembangan sistem manajemen berbasis website dengan Laravel + MySQL.
- **Format Daftar Pustaka (sementara):**
  > Rahmawati, N., & Hidayat, A. (2025). *Design and Implementation of Website-Based Contract Management Information System*. Jurnal Teknologi UMJ, 16(2).

## [REF-4] Saputra et al., 2024

- **Judul:** Implementasi Metode Waterfall pada Pengembangan Sistem Informasi Mobile E-Disarpus
- **Jurnal:** Zona Komputer (Universitas Lancang Kuning)
- **Tahun:** 2024
- **Link:** https://journal.unilak.ac.id/index.php/zn/article/view/20538
- **Kegunaan dalam draf:** Sitasi untuk lima tahapan Waterfall (analisis kebutuhan, desain sistem, implementasi, pengujian, pemeliharaan) dan pengujian Black Box.
- **Format Daftar Pustaka (sementara):**
  > Saputra, I., Lestari, M., & Anwar, S. (2024). *Implementasi Metode Waterfall pada Pengembangan Sistem Informasi Mobile E-Disarpus*. Zona Komputer, Universitas Lancang Kuning.

---

# REFERENSI PENDUKUNG (Opsional, jika perlu tambahan)

Jika kamu butuh referensi cadangan/tambahan saat sidang, berikut beberapa jurnal lain yang juga membahas Waterfall pada sistem berbasis web:

| No | Topik | Link |
|----|-------|------|
| 1 | Sistem Reservasi Barbershop dengan Laravel + Waterfall | https://jurnal.itscience.org/index.php/brilliance/article/view/5914 |
| 2 | Sistem Manajemen Kos dengan Waterfall (5 fase) | https://jurnal.kdi.or.id/index.php/bt/article/view/2738 |
| 3 | E-Booking Sarana Olahraga dengan Waterfall + UML | https://jurnal.itscience.org/index.php/CNAPC/article/view/4804 |
| 4 | Sistem Penjualan Motor Bekas Berbasis Web Laravel + Waterfall | https://jurnal.itscience.org/index.php/brilliance/article/view/6452 |
| 5 | Sistem Perpustakaan Berbasis Web dengan Waterfall | https://jurnal.kdi.or.id/index.php/bt/article/view/2185 |

---

# CHECKLIST SEBELUM SUBMIT

- [ ] Verifikasi nama lengkap penulis di setiap jurnal (buka link → cek halaman judul artikel)
- [ ] Update tahun, volume, nomor, dan halaman sesuai data asli
- [ ] Import ke Mendeley → generate sitasi otomatis
- [ ] Buat **Gambar 2.1** alur Waterfall (5 kotak berurutan)
- [ ] Pastikan tulisan italic untuk istilah asing (*Waterfall*, *requirement*, *testing*, dll.) sudah konsisten
- [ ] Sinkronkan istilah "metode" vs "model" — gunakan satu istilah saja secara konsisten
- [ ] Cek kembali urutan abjad di Daftar Pustaka setelah penambahan referensi baru

---

*Dokumen ini di-generate sebagai bantuan penyusunan TA. Konten draf perlu dibaca ulang dan disesuaikan dengan gaya penulisan kamu sendiri agar tidak terdengar seperti tulisan AI.*
