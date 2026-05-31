<?php

return [
    'kicker' => 'Tentang Kami',
    'title' => 'Renmote: rental motor online andalan Malang',
    'subtitle' => 'Renmote adalah platform digital yang menghubungkan penyewa dengan vendor rental motor terverifikasi di Kota Malang. Tujuan kami sederhana: mempermudah orang menyewa motor secara aman, transparan, dan cepat.',

    'mission_title' => 'Misi kami',
    'mission_text' => 'Membangun ekosistem sewa motor yang terdigitalisasi penuh, mengurangi friksi pemesanan, dan memberi standar transparansi harga, dokumen, dan komunikasi antara penyewa dan vendor.',

    'vision_title' => 'Visi kami',
    'vision_text' => 'Menjadi platform sewa motor online nomor satu di Indonesia yang dipercaya oleh pelancong, mahasiswa, dan komunitas motor karena keamanan transaksi, kualitas vendor, dan kemudahan teknologinya.',

    'values_title' => 'Nilai yang kami pegang',
    'values' => [
        [
            'title' => 'Aman & Terverifikasi',
            'desc' => 'Setiap vendor melalui proses verifikasi dokumen oleh admin. Penyewa mengunggah identitas resmi sebelum booking pertama.',
        ],
        [
            'title' => 'Transparan',
            'desc' => 'Harga, biaya, dan kebijakan vendor tertera jelas. Tidak ada biaya tersembunyi.',
        ],
        [
            'title' => 'Cepat & Mudah',
            'desc' => 'Booking, bayar DP, upload dokumen, semua di satu platform. Vendor mendapat notifikasi otomatis.',
        ],
        [
            'title' => 'Adil & Bertanggung Jawab',
            'desc' => 'Standar pelayanan jelas untuk dua sisi. Mekanisme komplain dan refund tersedia kalau ada kendala.',
        ],
    ],

    'stats_title' => 'Sekilas Renmote',
    'stat_vendors' => 'vendor terdaftar',
    'stat_vehicles' => 'motor aktif',
    'stat_bookings' => 'pemesanan terlayani',
    'stat_districts' => 'kecamatan terjangkau',

    'privacy_title' => 'Kebijakan Privasi',
    'privacy_subtitle' => 'Kami berkomitmen melindungi data penyewa dan vendor yang bergabung di Renmote.',

    'privacy_sections' => [
        [
            'title' => '1. Data yang kami kumpulkan',
            'items' => [
                'Data akun: nama, email, password (terenkripsi), nomor HP, gender, tanggal lahir, foto profil.',
                'Data identitas: KTP/KTM (penyewa), KTP + dokumen usaha (vendor) untuk verifikasi.',
                'Data transaksi: detail booking, bukti pembayaran, riwayat sewa, alamat pengantaran.',
                'Data komunikasi: isi chat antara penyewa dan vendor untuk audit kalau ada sengketa.',
                'Data teknis: IP address, jenis perangkat, dan cookie session untuk keamanan login.',
            ],
        ],
        [
            'title' => '2. Bagaimana kami menggunakan data',
            'items' => [
                'Memproses pemesanan, pembayaran, dan komunikasi antara penyewa dengan vendor.',
                'Verifikasi identitas untuk mencegah penipuan dan penyalahgunaan platform.',
                'Mengirim notifikasi penting (status booking, verifikasi pembayaran, pembaruan akun).',
                'Analisis internal untuk peningkatan layanan dan pengembangan fitur baru.',
                'Memenuhi kewajiban hukum jika diminta oleh otoritas yang berwenang.',
            ],
        ],
        [
            'title' => '3. Akses dan keamanan',
            'items' => [
                'Dokumen sensitif (KTP, izin usaha) hanya bisa diakses admin dan vendor terkait booking aktif.',
                'Password disimpan dalam bentuk hash bcrypt; tim Renmote tidak bisa melihat password kamu.',
                'Bukti pembayaran dilindungi dengan kontrol akses berbasis peran.',
                'Server menggunakan koneksi terenkripsi (HTTPS) untuk semua transmisi data.',
            ],
        ],
        [
            'title' => '4. Hak kamu sebagai pengguna',
            'items' => [
                'Meminta salinan data pribadi yang kami simpan kapan saja.',
                'Memperbarui atau menghapus data akun dari menu Akun Saya.',
                'Menonaktifkan akun secara permanen lewat menu hapus akun (data akan dianonimkan).',
                'Mengajukan keberatan jika ada penggunaan data yang tidak sesuai.',
            ],
        ],
        [
            'title' => '5. Berbagi data dengan pihak ketiga',
            'items' => [
                'Midtrans (gateway pembayaran) untuk memproses transaksi DP secara aman.',
                'Kami tidak menjual data pribadi kamu ke pihak mana pun.',
                'Berbagi data hanya dilakukan jika diminta oleh aparat hukum dengan dasar legal yang jelas.',
            ],
        ],
        [
            'title' => '6. Cookie & teknologi pelacakan',
            'items' => [
                'Cookie session digunakan untuk menjaga status login dan preferensi bahasa.',
                'Tidak ada pelacakan pihak ketiga untuk iklan personalisasi.',
                'Kamu bisa menonaktifkan cookie lewat browser, tapi beberapa fitur mungkin tidak optimal.',
            ],
        ],
    ],

    'contact_title' => 'Pertanyaan tentang privasi?',
    'contact_text' => 'Kalau kamu punya pertanyaan tentang kebijakan privasi atau ingin meminta hak data, hubungi tim kami:',
    'contact_email' => 'renmotebusiness@gmail.com',
    'contact_whatsapp' => '+62 895-2313-2567',
];
