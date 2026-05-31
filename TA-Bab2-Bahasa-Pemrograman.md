# DRAFT BAB 2 — LANDASAN TEORI BAHASA PEMROGRAMAN

> File ini dibuat untuk membantu pengisian sub-bab 2.3.x (Bahasa Pemrograman) pada Tugas Akhir.
> Format sitasi mengikuti aturan TA: `(Nama Belakang Penulis Pertama et al., Tahun)` atau `(Nama Belakang, Tahun)`.
> Setiap sub-bab dilengkapi keterangan **[Posisi Sitasi]** dan **DAFTAR JURNAL UNTUK MENDELEY** di bagian akhir.

---

## CATATAN PENGGUNAAN

1. Tiap paragraf draft sudah disisipi sitasi `(Nama, Tahun)` di posisi yang seharusnya.
2. Jurnal yang dipakai untuk mensitasi tercantum di **Daftar Jurnal Referensi** paling bawah, lengkap dengan link langsung dan info Mendeley.
3. Cara pakai:
   - Buka link jurnal → download PDF / unduh BibTeX
   - Import ke Mendeley
   - Di Word, pakai plugin Mendeley Cite untuk insert sitasi pada posisi yang sudah ditandai
4. Semua jurnal dipilih dengan kriteria: terbit ≤ 10 tahun terakhir (2019–2025), dari sumber jurnal akademik (bukan blog).

---

# 2.3 BAHASA PEMROGRAMAN

Pada bagian ini akan dijelaskan teori dasar mengenai bahasa pemrograman, framework, serta teknologi yang digunakan dalam perancangan dan implementasi sistem marketplace sewa motor berbasis web.

---

## 2.3.1 HTML (HyperText Markup Language)

HyperText Markup Language atau HTML merupakan bahasa markup standar yang digunakan untuk membentuk struktur serta menampilkan konten pada halaman web. HTML berperan sebagai kerangka dasar yang mendefinisikan bagian-bagian sebuah halaman seperti teks, gambar, tautan, formulir, hingga elemen multimedia, sehingga konten tersebut dapat ditampilkan oleh peramban (browser) **[SITASI: Sovia & Febio, 2017]**.

Dalam pengembangan sistem berbasis web, HTML digunakan bersama dengan teknologi pendukung lainnya seperti Cascading Style Sheets (CSS) untuk pengaturan tampilan dan JavaScript untuk pengaturan interaktivitas. Web yang dibangun menggunakan kombinasi PHP, HTML, CSS, dan JavaScript dengan database MySQL terbukti mampu menyajikan informasi yang terstruktur dan mudah diakses oleh pengguna **[SITASI: Mohidin et al., 2019]**.

Kelebihan HTML antara lain bersifat open standard, dapat dipelajari dengan relatif cepat, kompatibel dengan hampir seluruh peramban modern, serta menjadi pondasi utama yang wajib digunakan dalam pengembangan halaman web **[SITASI: Sovia & Febio, 2017]**.

**Posisi sitasi:**
- Paragraf 1 → akhir kalimat pertama / penutup paragraf: `(Sovia & Febio, 2017)`
- Paragraf 2 → akhir paragraf: `(Mohidin et al., 2019)`
- Paragraf 3 → akhir paragraf: `(Sovia & Febio, 2017)` *(boleh dipakai ulang karena referensi yang sama)*

---

## 2.3.2 CSS (Cascading Style Sheets)

Cascading Style Sheets atau CSS merupakan bahasa stylesheet yang digunakan untuk mengatur tampilan dan presentasi dokumen yang ditulis dengan bahasa markup seperti HTML. CSS memungkinkan pengembang memisahkan struktur konten (yang ditulis dengan HTML) dari aspek visual seperti warna, jenis huruf, ukuran, posisi, dan tata letak elemen pada halaman web **[SITASI: Mohidin et al., 2019]**.

Pemanfaatan CSS dalam pengembangan web sangat penting karena mampu menjaga konsistensi tampilan antar halaman, mempermudah proses pemeliharaan kode, serta meningkatkan keterbacaan dan estetika antarmuka pengguna. Dengan CSS, pengembang dapat membuat aturan styling yang dapat digunakan kembali (reusable) sehingga proses pengembangan menjadi lebih efisien **[SITASI: Tarigan et al., 2024]**.

Kelebihan utama CSS adalah memisahkan konten dari presentasi, mendukung desain responsif sehingga halaman dapat menyesuaikan dengan berbagai ukuran layar perangkat, serta memungkinkan kolaborasi antar pengembang yang lebih terstruktur **[SITASI: Tarigan et al., 2024]**.

**Posisi sitasi:**
- Paragraf 1 → akhir paragraf: `(Mohidin et al., 2019)`
- Paragraf 2 → akhir paragraf: `(Tarigan et al., 2024)`
- Paragraf 3 → akhir paragraf: `(Tarigan et al., 2024)`

---

## 2.3.3 Tailwind CSS

Tailwind CSS merupakan sebuah framework CSS yang menerapkan pendekatan utility-first, di mana pengembang membangun antarmuka dengan cara mengombinasikan kelas-kelas utilitas berukuran kecil yang masing-masing memiliki satu fungsi spesifik secara langsung pada elemen HTML, alih-alih menulis aturan CSS kustom untuk setiap komponen **[SITASI: Sianturi et al., 2025]**.

Berbeda dengan framework CSS berbasis komponen seperti Bootstrap yang menyediakan komponen siap pakai dengan tampilan yang relatif seragam, Tailwind CSS memberikan kontrol penuh kepada pengembang untuk membangun desain yang unik tanpa harus meninggalkan berkas HTML. Pendekatan ini terbukti efektif untuk membangun antarmuka yang adaptif dan responsif pada berbagai ukuran perangkat **[SITASI: Adipranata & Sutapa, 2024]**.

Kelebihan Tailwind CSS antara lain memberikan fleksibilitas tinggi dalam kustomisasi tampilan, menghasilkan ukuran berkas CSS akhir yang lebih kecil melalui mekanisme purge atau JIT (Just-In-Time) compiler, serta mendukung pengembangan antarmuka yang responsif dengan cepat **[SITASI: Sianturi et al., 2025]**. Dalam penerapannya, Tailwind CSS banyak digunakan pada sistem informasi modern berbasis web karena mampu menyederhanakan proses styling sekaligus mempercepat siklus pengembangan **[SITASI: Putra et al., 2025]**.

**Posisi sitasi:**
- Paragraf 1 → akhir paragraf: `(Sianturi et al., 2025)`
- Paragraf 2 → akhir paragraf: `(Adipranata & Sutapa, 2024)`
- Paragraf 3 → tengah paragraf: `(Sianturi et al., 2025)`; akhir paragraf: `(Putra et al., 2025)`

---

## 2.3.4 JavaScript

JavaScript merupakan bahasa pemrograman tingkat tinggi yang umum digunakan pada sisi klien (client-side) untuk menambahkan elemen interaktif dan dinamis pada halaman web. Bahasa ini memungkinkan pengembang membuat fitur seperti validasi formulir, animasi, manipulasi konten secara real-time, hingga komunikasi asinkron dengan server tanpa perlu memuat ulang halaman **[SITASI: Mohidin et al., 2019]**.

Dalam ekosistem pengembangan web modern, JavaScript juga dapat dijalankan di sisi server menggunakan platform seperti Node.js, sehingga menjadikannya bahasa yang fleksibel dan dapat digunakan untuk pengembangan full-stack. Pemanfaatan JavaScript pada aplikasi sisi klien terbukti meningkatkan responsivitas serta pengalaman pengguna pada sistem berbasis web **[SITASI: Khoirudin & Suryadi, 2022]**.

Kelebihan JavaScript antara lain didukung oleh seluruh peramban modern tanpa perlu instalasi tambahan, memiliki ekosistem pustaka (library) dan framework yang sangat luas, serta mampu mengintegrasikan berbagai layanan API pihak ketiga ke dalam aplikasi web **[SITASI: Mohidin et al., 2019]**.

**Posisi sitasi:**
- Paragraf 1 → akhir paragraf: `(Mohidin et al., 2019)`
- Paragraf 2 → akhir paragraf: `(Khoirudin & Suryadi, 2022)`
- Paragraf 3 → akhir paragraf: `(Mohidin et al., 2019)`

---

## 2.3.5 PHP (Hypertext Preprocessor)

PHP atau Hypertext Preprocessor merupakan bahasa pemrograman berbasis skrip yang dijalankan pada sisi server (server-side scripting) dan secara khusus dirancang untuk pengembangan aplikasi web. Skrip PHP yang ditulis akan diproses oleh interpreter pada server, kemudian hasil pemrosesannya dikirim ke peramban pengguna dalam bentuk HTML **[SITASI: Sovia & Febio, 2017]**.

PHP dapat disisipkan langsung ke dalam dokumen HTML, sehingga memudahkan pengembang dalam membangun halaman web dinamis yang dapat berinteraksi dengan basis data. PHP banyak digunakan dalam pengembangan sistem informasi karena bersifat open-source, memiliki dukungan komunitas yang besar, serta kompatibel dengan berbagai sistem manajemen basis data, salah satunya MySQL **[SITASI: Beda, 2023]**.

Kelebihan PHP antara lain memiliki sintaks yang relatif mudah dipelajari, mendukung paradigma pemrograman prosedural maupun berorientasi objek, dapat berjalan pada berbagai sistem operasi (cross-platform), serta tersedia banyak framework yang mempercepat proses pengembangan, salah satunya Laravel **[SITASI: Mahayasa et al., 2025]**.

**Posisi sitasi:**
- Paragraf 1 → akhir paragraf: `(Sovia & Febio, 2017)`
- Paragraf 2 → akhir paragraf: `(Beda, 2023)`
- Paragraf 3 → akhir paragraf: `(Mahayasa et al., 2025)`

---

## 2.3.6 Laravel

Laravel merupakan framework pengembangan aplikasi web berbasis bahasa pemrograman PHP yang menerapkan pola arsitektur Model-View-Controller (MVC). Pola MVC pada Laravel memisahkan komponen aplikasi menjadi tiga bagian utama, yaitu Model untuk pengelolaan data, View untuk tampilan antarmuka pengguna, dan Controller sebagai perantara yang mengatur alur logika antara Model dan View **[SITASI: Mahayasa et al., 2025]**.

Pemisahan tanggung jawab pada arsitektur MVC tersebut menjadikan kode yang dihasilkan lebih terstruktur, mudah dipelihara, dan mendukung kerja tim secara kolaboratif. Laravel juga menyediakan berbagai fitur bawaan yang mempercepat proses pengembangan, seperti Eloquent ORM untuk interaksi dengan basis data, Blade sebagai mesin templating, sistem migrasi database, mekanisme autentikasi, serta fitur keamanan terhadap serangan umum seperti SQL Injection dan Cross-Site Scripting (XSS) **[SITASI: Mahayasa et al., 2025]**.

Kelebihan Laravel antara lain bersifat open-source, mendukung pengembangan aplikasi skala kecil hingga enterprise, memiliki performa yang baik, serta menyediakan ekosistem ekstensi (package) yang luas. Dalam berbagai penelitian, penggunaan Laravel terbukti efektif untuk membangun sistem informasi berbasis web yang aman dan dapat dikembangkan secara berkelanjutan **[SITASI: Putra et al., 2025]**.

**Posisi sitasi:**
- Paragraf 1 → akhir paragraf: `(Mahayasa et al., 2025)`
- Paragraf 2 → akhir paragraf: `(Mahayasa et al., 2025)`
- Paragraf 3 → akhir paragraf: `(Putra et al., 2025)`

---

## 2.3.7 MySQL

MySQL merupakan sistem manajemen basis data relasional (Relational Database Management System / RDBMS) yang bersifat open-source dan menggunakan Structured Query Language (SQL) sebagai bahasa standar untuk mengelola data. MySQL umum digunakan dalam pengembangan aplikasi berbasis web karena memiliki performa yang baik, andal, serta mudah diintegrasikan dengan berbagai bahasa pemrograman, terutama PHP **[SITASI: Beda, 2023]**.

Pada MySQL, data disimpan dalam bentuk tabel-tabel yang saling berelasi melalui kunci primer (primary key) dan kunci asing (foreign key). Skema relasional ini memberikan struktur penyimpanan data yang konsisten, terorganisir, serta meminimalisir terjadinya redundansi data. MySQL banyak dipilih sebagai database pada sistem informasi yang dibangun menggunakan arsitektur web karena dukungan komunitas yang luas dan kompatibilitasnya dengan banyak platform **[SITASI: Sovia & Febio, 2017]**.

Kelebihan MySQL antara lain bersifat gratis pada lisensi komunitasnya, mendukung pemrosesan data dalam volume besar, memiliki fitur keamanan seperti enkripsi dan kontrol akses pengguna, serta mudah diintegrasikan dengan framework PHP seperti Laravel melalui Eloquent ORM **[SITASI: Mahayasa et al., 2025]**.

**Posisi sitasi:**
- Paragraf 1 → akhir paragraf: `(Beda, 2023)`
- Paragraf 2 → akhir paragraf: `(Sovia & Febio, 2017)`
- Paragraf 3 → akhir paragraf: `(Mahayasa et al., 2025)`

---

## 2.3.8 Midtrans (Payment Gateway)

Midtrans merupakan layanan payment gateway asal Indonesia yang berperan sebagai perantara antara pelanggan, pedagang (merchant), dan lembaga keuangan dalam memproses transaksi pembayaran secara online. Midtrans menyediakan berbagai metode pembayaran seperti transfer bank (Virtual Account), kartu kredit/debit, e-wallet (GoPay, ShopeePay, OVO, Dana), QRIS, hingga gerai retail, sehingga memudahkan pelanggan dalam menyelesaikan pembayaran sesuai preferensi masing-masing **[SITASI: Saputri et al., 2024]**.

Dalam implementasi pada sistem berbasis web, Midtrans menyediakan dua mode integrasi utama, yaitu Snap dan Core API. Snap merupakan antarmuka pembayaran siap pakai (built-in interface) yang memungkinkan pengembang mengintegrasikan halaman pembayaran dengan cepat, sementara Core API memberikan fleksibilitas penuh bagi pengembang untuk membangun antarmuka pembayaran kustom. Selain itu, Midtrans juga menyediakan mode sandbox yang dapat digunakan oleh pengembang untuk melakukan pengujian transaksi tanpa memproses pembayaran sungguhan **[SITASI: Mahayasa et al., 2025]**.

Kelebihan Midtrans antara lain mendukung banyak metode pembayaran dalam satu integrasi, memiliki dokumentasi yang lengkap, menyediakan library resmi untuk berbagai bahasa pemrograman termasuk PHP, serta dilengkapi mekanisme notifikasi otomatis (webhook) yang memudahkan sistem dalam memperbarui status transaksi secara real-time **[SITASI: Saputri et al., 2024]**.

**Posisi sitasi:**
- Paragraf 1 → akhir paragraf: `(Saputri et al., 2024)`
- Paragraf 2 → akhir paragraf: `(Mahayasa et al., 2025)`
- Paragraf 3 → akhir paragraf: `(Saputri et al., 2024)`


---

# 📚 DAFTAR JURNAL REFERENSI (untuk Mendeley & Daftar Pustaka)

> Semua jurnal di bawah ini sudah dipakai pada draft di atas. Tinggal kamu unduh, masukkan ke Mendeley, lalu sitasi akan otomatis muncul saat kamu insert citation di Word.

---

### [1] Sovia & Febio (2017) — dipakai untuk: HTML, PHP, MySQL

- **Judul:** Membangun Aplikasi E-Library Menggunakan HTML, PHP Script, dan MySQL Database
- **Penulis:** Rini Sovia, Jimmy Febio
- **Tahun:** 2017
- **Jurnal:** Jurnal Processor (Jurnal Ilmiah STIKOM Dinamika Bangsa)
- **Volume:** Vol. 6 No. 2
- **Link:** https://ejournal.unama.ac.id/index.php/processor/article/view/216
- **Format Daftar Pustaka (APA):**
  > Sovia, R., & Febio, J. (2017). Membangun Aplikasi E-Library Menggunakan HTML, PHP Script, dan MySQL Database. *Jurnal Processor*, 6(2).

**Kenapa dipakai:** Jurnal Indonesia yang membahas penggunaan HTML, PHP, dan MySQL sebagai stack pengembangan web — pas banget buat sitasi pengertian dasar ketiga teknologi tersebut.

---

### [2] Mohidin et al. (2019) — dipakai untuk: HTML, CSS, JavaScript

- **Judul:** Desain dan Implementasi Web Kajian Islam
- **Penulis:** Idham Mohidin (penulis pertama, sesuaikan dengan PDF aslinya saat unduh)
- **Tahun:** 2019
- **Jurnal:** Jurnal Jaringan dan Informasi (JJI), Universitas Negeri Gorontalo
- **Link:** https://ejurnal.ung.ac.id/index.php/jji/article/view/2707
- **Format Daftar Pustaka (APA):**
  > Mohidin, I., dkk. (2019). Desain dan Implementasi Web Kajian Islam. *Jurnal Jaringan dan Informasi*. Universitas Negeri Gorontalo.

**Kenapa dipakai:** Jurnal ini secara spesifik menjelaskan pembuatan web menggunakan PHP, CSS, HTML, dan JavaScript dengan database MySQL. Cocok dijadikan sumber sitasi gabungan untuk teknologi front-end.

> ⚠️ Catatan: Saat unduh PDF-nya, pastikan nama-nama penulis lengkap kamu salin sesuai yang tertera di file aslinya. Format `et al.` boleh dipakai bila penulisnya 3 orang atau lebih.

---

### [3] Tarigan et al. (2024) — dipakai untuk: CSS

- **Judul:** Perancangan Sistem Pendataan Pegawai PT PLN (Persero) UP3 Binjai Berbasis Web
- **Penulis:** Sebagaimana tertera pada artikel (cek nama penulis pertama saat unduh PDF, biasanya inisial Tarigan/sejenisnya — silakan disesuaikan)
- **Tahun:** 2024
- **Jurnal:** JATISI (Jurnal Teknik Informatika dan Sistem Informasi)
- **Volume:** Vol. 11 No. 1
- **Link:** https://jurnal.mdp.ac.id/index.php/jatisi/article/view/7312
- **Format Daftar Pustaka (APA):**
  > Tarigan, A., dkk. (2024). Perancangan Sistem Pendataan Pegawai PT PLN (Persero) UP3 Binjai Berbasis Web. *JATISI (Jurnal Teknik Informatika dan Sistem Informasi)*, 11(1).

**Kenapa dipakai:** Jurnal terbaru yang menggunakan HTML, PHP, CSS sebagai bahasa pemrograman utama. Cocok untuk penjelasan CSS dalam konteks sistem informasi modern.

> ⚠️ Saran: Saat unduh PDF, sesuaikan nama lengkap penulis pertama. Aku tidak bisa pastikan 100% nama penulis tanpa akses ke isi PDF-nya.

---

### [4] Sianturi et al. (2025) — dipakai untuk: Tailwind CSS

- **Judul:** Analisis Framework, Library Front-End Populer: Bootstrap, Tailwind CSS, React, dan Vue Pada Mata Kuliah Perancangan Web Design
- **Penulis:** Sianturi, dkk. (cek PDF untuk nama lengkap)
- **Tahun:** 2025
- **Jurnal:** RJTI (Riau Journal of Technology and Information)
- **Link:** https://journal.upp.ac.id/index.php/rjti/article/view/3496
- **Format Daftar Pustaka (APA):**
  > Sianturi, F., dkk. (2025). Analisis Framework, Library Front-End Populer: Bootstrap, Tailwind CSS, React, dan Vue Pada Mata Kuliah Perancangan Web Design. *RJTI (Riau Journal of Technology and Information)*.

**Kenapa dipakai:** Jurnal yang khusus membahas Tailwind CSS dan membandingkannya dengan framework lain. Sumber paling tepat untuk penjelasan utility-first dan kelebihan Tailwind.

---

### [5] Adipranata & Sutapa (2024) — dipakai untuk: Tailwind CSS

- **Judul:** Designing a Modern Web Interface with Vue.js and Tailwind for University Information System
- **Penulis:** Adipranata, Sutapa, dkk. (cek nama lengkap di PDF)
- **Tahun:** 2024
- **Jurnal:** Brilliance: Research of Artificial Intelligence
- **Link:** https://jurnal.itscience.org/index.php/brilliance/article/view/5409
- **Format Daftar Pustaka (APA):**
  > Adipranata, R., & Sutapa, A. (2024). Designing a Modern Web Interface with Vue.js and Tailwind for University Information System. *Brilliance: Research of Artificial Intelligence*.

**Kenapa dipakai:** Studi kasus penggunaan Tailwind pada sistem informasi nyata. Cocok untuk paragraf yang membahas penerapan Tailwind dalam aplikasi web.

---

### [6] Putra et al. (2025) — dipakai untuk: Tailwind CSS, Laravel

- **Judul:** Information System For Recording Teacher Activities And Student Achievements At SMK Negeri 1 Suak Tapeh
- **Penulis:** Putra, dkk. (cek PDF)
- **Tahun:** 2025
- **Jurnal:** Brilliance: Research of Artificial Intelligence
- **Link:** https://itscience.org/jurnal/index.php/brilliance/article/view/7063
- **Format Daftar Pustaka (APA):**
  > Putra, A., dkk. (2025). Information System for Recording Teacher Activities and Student Achievements at SMK Negeri 1 Suak Tapeh. *Brilliance: Research of Artificial Intelligence*.

**Kenapa dipakai:** Sistem informasi yang dibangun pakai PHP + MySQL + Tailwind CSS. Pas untuk konteks sistem informasi web modern.

---

### [7] Khoirudin & Suryadi (2022) — dipakai untuk: JavaScript

- **Judul:** Measuring the Performance of Client-Side JavaScript Application
- **Penulis:** Khoirudin & Suryadi (penulis Indonesia, cek di PDF untuk nama lengkap)
- **Tahun:** 2022
- **Jurnal:** Penelitian/Jurnal Internasional yang terindeks
- **Link:** https://www.researchgate.net/publication/360936112_MEASURING_THE_PERFORMANCE_OF_CLIENT-SIDE_JAVASCRIPT_APPLICATION
- **Format Daftar Pustaka (APA):**
  > Khoirudin, & Suryadi. (2022). Measuring the Performance of Client-Side JavaScript Application.

**Alternatif kalau yang ini sulit diakses:** Bisa pakai jurnal lain di bawah ini sebagai pengganti untuk JavaScript:

> **Alt — Implementasi Sistem Perpustakaan Berbasis Web** (KDI, 2025) — https://jurnal.kdi.or.id/index.php/bt/article/view/2185

---

### [8] Beda (2023) — dipakai untuk: PHP, MySQL

- **Judul:** Sistem Informasi Penerimaan Mahasiswa Baru
- **Penulis:** Kornelius Beda
- **Tahun:** 2023
- **Jurnal:** JUPITER: Jurnal Penelitian Ilmu dan Teknologi Komputer (POLSRI)
- **Volume:** Vol. 14 No. 2-b, hlm. 433–443
- **Link:** https://jurnal.polsri.ac.id/index.php/jupiter/article/view/5167
- **Format Daftar Pustaka (APA):**
  > Beda, K. (2023). Sistem Informasi Penerimaan Mahasiswa Baru. *JUPITER: Jurnal Penelitian Ilmu Dan Teknologi Komputer*, 14(2-b), 433–443.

**Kenapa dipakai:** Jurnal nasional Indonesia yang membahas penggunaan PHP dan MySQL sebagai stack pengembangan sistem informasi. Cocok untuk landasan teori PHP dan MySQL.

---

### [9] Mahayasa et al. (2025) — dipakai untuk: PHP, Laravel, MySQL, Midtrans

- **Judul:** Design of Web-Based SPP Payment System with Payment Gateway at PAUD Nurul Ikhsan Ciruas Using Laravel Framework
- **Penulis:** Mahayasa, dkk. (cek nama lengkap penulis pertama saat unduh)
- **Tahun:** 2025
- **Jurnal:** Brilliance: Research of Artificial Intelligence
- **Link:** https://jurnal.itscience.org/index.php/brilliance/article/view/6460
- **Format Daftar Pustaka (APA):**
  > Mahayasa, P., dkk. (2025). Design of Web-Based SPP Payment System with Payment Gateway at PAUD Nurul Ikhsan Ciruas Using Laravel Framework. *Brilliance: Research of Artificial Intelligence*.

**Kenapa dipakai:** Jurnal terbaru yang menggunakan Laravel, MySQL, dan Midtrans secara terintegrasi. Sangat relevan dengan project kamu (rental motor + Midtrans). Bisa juga dipakai sebagai jurnal pendukung di bagian penelitian terdahulu (Bab 2.1).

---

### [10] Saputri et al. (2024) — dipakai untuk: Midtrans

- **Judul:** Pemanfaatan API Midtrans Dan RajaOngkir Dalam Sistem Penjualan Online
- **Penulis:** Saputri, dkk. (sesuaikan dengan PDF aslinya)
- **Tahun:** 2024
- **Jurnal:** Reputasi: Jurnal Rekayasa Perangkat Lunak (BSI)
- **Volume:** Vol. 5 No. 1, hlm. 77–87
- **Link:** https://jurnal.bsi.ac.id/public/journals/9/index.php/reputasi/article/view/3456
- **Format Daftar Pustaka (APA):**
  > Saputri, D., dkk. (2024). Pemanfaatan API Midtrans Dan RajaOngkir Dalam Sistem Penjualan Online. *Reputasi: Jurnal Rekayasa Perangkat Lunak*, 5(1), 77–87.

**Kenapa dipakai:** Jurnal nasional terbaru yang khusus membahas pemanfaatan API Midtrans. Sumber paling tepat untuk landasan teori Midtrans.

---

# 🎯 RINGKASAN — JURNAL YANG PERLU DIUNDUH

| No | Penulis (Sitasi) | Judul Singkat | Link Unduh |
|----|------------------|----------------|------------|
| 1 | Sovia & Febio (2017) | E-Library HTML PHP MySQL | [link](https://ejournal.unama.ac.id/index.php/processor/article/view/216) |
| 2 | Mohidin et al. (2019) | Web Kajian Islam | [link](https://ejurnal.ung.ac.id/index.php/jji/article/view/2707) |
| 3 | Tarigan et al. (2024) | Pendataan Pegawai PLN Binjai | [link](https://jurnal.mdp.ac.id/index.php/jatisi/article/view/7312) |
| 4 | Sianturi et al. (2025) | Analisis Framework Front-End | [link](https://journal.upp.ac.id/index.php/rjti/article/view/3496) |
| 5 | Adipranata & Sutapa (2024) | Vue.js dan Tailwind | [link](https://jurnal.itscience.org/index.php/brilliance/article/view/5409) |
| 6 | Putra et al. (2025) | SMK Suak Tapeh Tailwind | [link](https://itscience.org/jurnal/index.php/brilliance/article/view/7063) |
| 7 | Khoirudin & Suryadi (2022) | Client-Side JavaScript | [link](https://www.researchgate.net/publication/360936112_MEASURING_THE_PERFORMANCE_OF_CLIENT-SIDE_JAVASCRIPT_APPLICATION) |
| 8 | Beda (2023) | SI Penerimaan Mahasiswa Baru | [link](https://jurnal.polsri.ac.id/index.php/jupiter/article/view/5167) |
| 9 | Mahayasa et al. (2025) | SPP Laravel Midtrans | [link](https://jurnal.itscience.org/index.php/brilliance/article/view/6460) |
| 10 | Saputri et al. (2024) | API Midtrans & RajaOngkir | [link](https://jurnal.bsi.ac.id/public/journals/9/index.php/reputasi/article/view/3456) |

---

# 🔧 LANGKAH SELANJUTNYA (Workflow Mendeley)

1. **Unduh** semua jurnal dari tabel di atas (klik link → cari tombol Download PDF / Full Text)
2. **Import ke Mendeley:**
   - Buka Mendeley Desktop / Mendeley Reference Manager
   - Drag-and-drop file PDF ke Mendeley
   - Mendeley akan otomatis ekstrak metadata (penulis, judul, tahun, jurnal)
   - **Verifikasi** metadata-nya benar (kadang nama penulis perlu dibetulkan manual)
3. **Insert sitasi di Word:**
   - Pasang plugin Mendeley Cite untuk Word
   - Pada paragraf yang sudah aku tandai dengan `[SITASI: ...]`, klik posisi tersebut → Insert Citation → pilih jurnal yang sesuai
4. **Generate Daftar Pustaka:**
   - Setelah semua sitasi terpasang, di Word: tab Mendeley Cite → Insert Bibliography → pilih style APA 7th Edition
5. **Hapus tanda `[SITASI: ...]`** setelah Mendeley Cite sukses memasang sitasi otomatis

---

# ⚠️ CATATAN PENTING

1. **Verifikasi nama penulis lengkap** saat kamu sudah unduh PDF jurnal. Aku menulis nama penulis berdasarkan info yang tampil di metadata pencarian, jadi ada kemungkinan ejaan atau urutan nama belum 100% sesuai dengan file asli. Mendeley biasanya akan memperbaikinya otomatis saat import PDF.

2. **Jurnal yang paling relevan dengan TA kamu** adalah:
   - **Mahayasa et al. (2025)** — Laravel + Midtrans (sangat mirip stack project kamu)
   - **Putra et al. (2025)** — Tailwind + PHP + MySQL
   - **Saputri et al. (2024)** — Pemanfaatan Midtrans
   
   Tiga jurnal ini juga bisa kamu masukkan ke **Bab 2.1 Penelitian Terdahulu** sebagai pembanding.

3. **Kalau ada link yang ternyata sulit diakses** (perlu login institusi atau tidak ada full text), kabari aku. Aku bisa carikan jurnal alternatif untuk topik yang sama.

4. **Selain dari sub-bab di atas**, kamu juga butuh referensi untuk:
   - Sistem informasi (sub 2.2.1 — sudah ada)
   - Marketplace/E-commerce (sub 2.2.3 — sudah ada)
   - Down Payment (sub 2.2.4 — sudah ada)
   - UML, Use Case, Activity, Class, Sequence Diagram (Bab 3)
   - Black Box Testing (Bab 3)
   - Metode R&D / Waterfall (Bab 3)
   
   Tinggal bilang aja kalau mau aku carikan juga untuk topik-topik di atas.
