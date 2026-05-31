<?php

return [
    'kicker' => 'Pusat Bantuan',
    'title' => 'FAQ & Help Center',
    'subtitle' => 'Temukan jawaban untuk pertanyaan umum atau hubungi tim support kami.',

    'tab_renter' => 'Penyewa',
    'tab_vendor' => 'Vendor',

    'sidebar_title' => 'Butuh bantuan lebih?',
    'sidebar_subtitle' => 'Tim support kami siap membantu kamu kapan saja.',
    'whatsapp_label' => 'WhatsApp Support',
    'email_label' => 'Email Support',
    'hours_label' => 'Jam Operasional',
    'hours_value' => '24/7 Support',
    'hours_subtitle' => 'Tim kami siap melayani kapan saja.',

    'tip_title' => 'Tips cepat',
    'tip_message' => 'Sebelum chat support, coba cari di FAQ — siapa tahu jawabannya sudah ada.',

    'renter_groups' => [
        [
            'title' => 'Akun & Profil',
            'items' => [
                [
                    'q' => 'Bagaimana cara membuat akun penyewa?',
                    'a' => 'Klik tombol Daftar di pojok kanan atas, pilih peran Penyewa, isi nama, email, dan password aktif. Setelah itu kamu bisa langsung login dan mulai pesan motor.',
                ],
                [
                    'q' => 'Apakah saya wajib upload KTP?',
                    'a' => 'Iya, KTP/KTM wajib diunggah saat pemesanan pertama untuk verifikasi identitas. Dokumen disimpan aman dan hanya bisa diakses admin/vendor terkait booking kamu.',
                ],
                [
                    'q' => 'Bagaimana cara mengubah foto profil dan data akun?',
                    'a' => 'Buka menu Akun Saya dari topbar, lalu klik tab Profil. Kamu bisa edit nama, email, foto profil, gender, tanggal lahir, dan nomor HP. Klik Simpan untuk menyimpan perubahan.',
                ],
                [
                    'q' => 'Saya lupa password, bagaimana?',
                    'a' => 'Di halaman Masuk, klik Lupa password? lalu isi email kamu. Link reset akan dikirim ke email aktif yang terdaftar. Klik link tersebut untuk membuat password baru.',
                ],
            ],
        ],
        [
            'title' => 'Pemesanan & Pembayaran',
            'items' => [
                [
                    'q' => 'Bagaimana cara memesan motor?',
                    'a' => 'Pilih motor di halaman Beranda atau Search, klik Sewa Sekarang, pilih tanggal mulai dan selesai, tentukan metode penerimaan (pickup/delivery), upload dokumen, lalu lanjut ke pembayaran DP 30%.',
                ],
                [
                    'q' => 'Berapa besar DP yang harus dibayar?',
                    'a' => 'DP sebesar 30% dari total sewa. Sisa 70% dilunasi langsung ke vendor saat serah-terima motor.',
                ],
                [
                    'q' => 'Apa saja metode pembayaran yang didukung?',
                    'a' => 'DP dibayar lewat Midtrans dengan opsi: QRIS, GoPay, ShopeePay, dan Virtual Account (BCA, BNI, BRI, Permata). Token pembayaran berlaku 30 menit.',
                ],
                [
                    'q' => 'Token Midtrans saya expired, bagaimana?',
                    'a' => 'Buka halaman pembayaran booking kamu, klik tombol Ajukan Ulang Pembayaran. Sistem akan generate invoice dan token Snap baru.',
                ],
                [
                    'q' => 'Bagaimana cara upload bukti pembayaran?',
                    'a' => 'Setelah DP terbayar, kamu akan diarahkan ke halaman upload bukti. Pilih file (JPG/PNG/PDF maks 6MB), tambahkan catatan opsional, lalu Submit. Admin/vendor akan verifikasi maksimal 1×24 jam.',
                ],
            ],
        ],
        [
            'title' => 'Selama Masa Sewa',
            'items' => [
                [
                    'q' => 'Saya mau perpanjang sewa, bagaimana caranya?',
                    'a' => 'Hubungi vendor melalui Chat minimal 6 jam sebelum waktu sewa berakhir. Vendor akan cek ketersediaan unit dan menginformasikan biaya tambahan.',
                ],
                [
                    'q' => 'Apa yang harus saya cek saat menerima motor?',
                    'a' => 'Cek kondisi rem, lampu, ban, klakson, level bensin, dan dokumentasikan bodi motor (foto/video). Pastikan kunci, STNK, dan helm diserahkan lengkap.',
                ],
                [
                    'q' => 'Motor mogok di tengah jalan, harus hubungi siapa?',
                    'a' => 'Hubungi vendor terlebih dahulu via Chat. Kalau tidak bisa dihubungi, kontak admin Renmote di WhatsApp resmi yang tertera di footer.',
                ],
                [
                    'q' => 'Bagaimana kalau saya telat mengembalikan motor?',
                    'a' => 'Keterlambatan dapat dikenakan biaya tambahan per jam atau per hari sesuai kebijakan vendor. Hubungi vendor segera kalau ada kendala.',
                ],
            ],
        ],
        [
            'title' => 'Pembatalan & Refund',
            'items' => [
                [
                    'q' => 'Bisa cancel booking?',
                    'a' => 'Booking berstatus Pending bisa kamu cancel sendiri dari halaman Riwayat Pemesanan. Booking yang sudah Confirmed perlu konfirmasi vendor untuk dibatalkan.',
                ],
                [
                    'q' => 'DP saya bisa di-refund?',
                    'a' => 'Refund tergantung kebijakan vendor dan status booking. Pembatalan saat Pending biasanya bisa di-refund, sedangkan setelah Confirmed umumnya hangus.',
                ],
                [
                    'q' => 'Berapa lama proses refund?',
                    'a' => 'Refund diproses lewat transfer manual ke rekening kamu, biasanya 3–7 hari kerja tergantung bank.',
                ],
            ],
        ],
    ],

    'vendor_groups' => [
        [
            'title' => 'Pendaftaran Vendor',
            'items' => [
                [
                    'q' => 'Apa syarat untuk jadi vendor di Renmote?',
                    'a' => 'KTP aktif, dokumen izin usaha (SIUP/NIB) atau surat keterangan usaha kelurahan, foto profil/lokasi, dan domisili di area operasional Renmote (Malang dan sekitarnya).',
                ],
                [
                    'q' => 'Bagaimana cara mendaftar sebagai vendor?',
                    'a' => 'Klik Jadi Vendor di topbar, daftar akun baru dengan peran Vendor. Setelah login, kamu akan diminta lengkapi data toko dan upload dokumen verifikasi.',
                ],
                [
                    'q' => 'Berapa lama proses verifikasi?',
                    'a' => 'Admin meninjau pengajuan maksimal 3×24 jam kerja. Status akan berubah dari Pending menjadi Approved atau Rejected dengan alasan.',
                ],
                [
                    'q' => 'Pendaftaran saya ditolak, bisa daftar lagi?',
                    'a' => 'Bisa. Kamu bisa edit ulang form pendaftaran dengan dokumen yang valid, lalu submit ulang dari menu yang sama.',
                ],
            ],
        ],
        [
            'title' => 'Manajemen Kendaraan',
            'items' => [
                [
                    'q' => 'Bagaimana cara menambahkan motor ke katalog?',
                    'a' => 'Setelah status Approved, buka menu Kendaraan di dashboard vendor, klik Tambah Kendaraan, lengkapi nama, kategori, harga per hari, tahun, deskripsi, dan upload foto.',
                ],
                [
                    'q' => 'Bisa edit harga setiap saat?',
                    'a' => 'Bisa. Buka menu Kendaraan, klik Edit pada motor yang dimaksud, ubah harga, lalu Save. Perubahan langsung berlaku untuk booking baru.',
                ],
                [
                    'q' => 'Cara menonaktifkan motor sementara?',
                    'a' => 'Edit motor dan ubah status menjadi Unavailable. Motor tidak akan muncul di pencarian sampai diaktifkan kembali.',
                ],
            ],
        ],
        [
            'title' => 'Mengelola Booking',
            'items' => [
                [
                    'q' => 'Bagaimana cara verifikasi bukti pembayaran penyewa?',
                    'a' => 'Buka detail booking di dashboard vendor, lihat bukti pembayaran yang diunggah, lalu klik Approve (jika valid) atau Reject dengan alasan.',
                ],
                [
                    'q' => 'Kapan harus konfirmasi atau menolak booking?',
                    'a' => 'Idealnya dalam 1×24 jam setelah penyewa upload bukti pembayaran. Konfirmasi cepat menjaga rating vendor dan kepercayaan penyewa.',
                ],
                [
                    'q' => 'Bagaimana kalau motor tiba-tiba rusak sebelum hari H?',
                    'a' => 'Hubungi penyewa secepatnya via Chat dan informasikan ke admin Renmote. Penyewa berhak menerima refund DP 100% jika pembatalan murni karena kondisi unit.',
                ],
                [
                    'q' => 'Bisa export laporan booking?',
                    'a' => 'Bisa. Di halaman Booking, klik tombol Export Excel. File akan ter-download berisi semua booking sesuai filter aktif.',
                ],
            ],
        ],
        [
            'title' => 'Pencairan Dana',
            'items' => [
                [
                    'q' => 'Komisi platform berapa persen?',
                    'a' => 'Komisi platform ditentukan saat onboarding vendor dan dijelaskan di perjanjian kerja sama. Hubungi admin untuk detail terbaru.',
                ],
                [
                    'q' => 'Kapan dana DP cair ke rekening saya?',
                    'a' => 'DP yang dibayar penyewa via Midtrans masuk ke escrow Renmote, lalu disettle ke rekening vendor sesuai siklus settlement (biasanya H+3 setelah booking selesai).',
                ],
                [
                    'q' => 'Cara mengubah rekening tujuan settlement?',
                    'a' => 'Buka menu Profil di dashboard vendor, edit Bank Name dan Bank Account, lalu Save. Perubahan akan diverifikasi admin sebelum siklus settlement berikutnya.',
                ],
            ],
        ],
    ],
];
