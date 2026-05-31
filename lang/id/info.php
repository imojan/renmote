<?php

return [
    /* ─────────── How to Rent (Cara Sewa) ─────────── */
    'guide' => [
        'kicker' => 'Panduan',
        'title' => 'Cara Sewa Motor di Renmote',
        'subtitle' => 'Alur lengkap memesan motor di Renmote, mulai dari pencarian hingga pengembalian unit. Disesuaikan dengan flow pembayaran DP 30% via Midtrans dan verifikasi dokumen.',

        'steps' => [
            [
                'no' => '01',
                'title' => 'Cari motor sesuai kebutuhan',
                'desc' => 'Gunakan search bar atau filter kategori (matic, manual, sport, bebek, trail, skutik premium, big bike). Saring berdasarkan kecamatan, tanggal sewa, dan range harga.',
            ],
            [
                'no' => '02',
                'title' => 'Cek detail unit dan ketersediaan',
                'desc' => 'Buka halaman motor untuk melihat foto, deskripsi, harga per hari, lokasi vendor, serta jadwal yang sudah dibooking. Tambahkan ke wishlist atau langsung klik Sewa Sekarang.',
            ],
            [
                'no' => '03',
                'title' => 'Login atau daftar dulu',
                'desc' => 'Hanya akun terdaftar yang bisa booking. Daftar sebagai Penyewa cukup pakai email aktif. Belum punya akun? Klik Daftar di pojok kanan atas.',
            ],
            [
                'no' => '04',
                'title' => 'Konfirmasi tanggal & alamat',
                'desc' => 'Pilih tanggal mulai dan selesai sewa. Tentukan metode penerimaan: ambil di tempat vendor (pickup) atau diantar ke alamat (delivery). Lengkapi alamat tujuan jika delivery.',
            ],
            [
                'no' => '05',
                'title' => 'Upload KTP/KTM (wajib) dan SIM C',
                'desc' => 'KTP/KTM wajib dilampirkan untuk verifikasi identitas. SIM C dianjurkan dan akan diperiksa vendor. Dokumen tersimpan aman dan hanya bisa dilihat admin/vendor terkait.',
            ],
            [
                'no' => '06',
                'title' => 'Bayar DP 30% via Midtrans',
                'desc' => 'Lanjut ke pembayaran DP sebesar 30% dari total sewa lewat QRIS, GoPay, ShopeePay, atau Virtual Account. Token Snap aktif selama 30 menit.',
            ],
            [
                'no' => '07',
                'title' => 'Upload bukti pembayaran',
                'desc' => 'Setelah DP terbayar, unggah bukti transfer/pembayaran. Admin atau vendor akan memverifikasi maksimal 1×24 jam. Status booking otomatis update ke Confirmed setelah disetujui.',
            ],
            [
                'no' => '08',
                'title' => 'Ambil/terima motor & cek kondisi',
                'desc' => 'Pada hari H, dokumentasikan kondisi motor (foto/video bodi, ban, lampu, bensin) sebelum berangkat. Lunasi sisa pembayaran 70% langsung ke vendor sesuai metode yang disepakati.',
            ],
            [
                'no' => '09',
                'title' => 'Kembalikan tepat waktu & download invoice',
                'desc' => 'Kembalikan motor sesuai jadwal untuk menghindari biaya keterlambatan. Invoice PDF bisa diunduh dari halaman Riwayat Pemesanan untuk arsip pribadi.',
            ],
        ],

        'tips_title' => 'Tips supaya proses lancar',
        'tips' => [
            'Pilih vendor verified (lencana biru) dengan rating dan ulasan jelas.',
            'Konfirmasi titik jemput, jam operasional, dan biaya tambahan sebelum hari H lewat fitur Chat.',
            'Selalu pakai metode pembayaran resmi yang tercatat sistem agar ada bukti transaksi.',
            'Kalau ada perubahan jadwal, hubungi vendor segera lewat Chat agar booking tetap aman.',
            'Simpan invoice PDF dan bukti pembayaran sampai motor selesai dikembalikan.',
        ],

        'reference_title' => 'Rujukan Konten',
        'reference_text' => 'Panduan ini disesuaikan dengan operasional Renmote dan praktik umum rental motor di Indonesia, termasuk prinsip legal berkendara seperti kepemilikan SIM sesuai jenis kendaraan (UU No. 22 Tahun 2009, Pasal 77 ayat 1).',
        'ref_uu_2009' => 'UU No. 22 Tahun 2009 tentang Lalu Lintas dan Angkutan Jalan (BPK RI)',
        'ref_korlantas' => 'Informasi SIM - Korlantas Polri',
        'ref_jdih' => 'Portal JDIH Kementerian Perhubungan',
    ],

    /* ─────────── Rental Terms (S&K) ─────────── */
    'terms' => [
        'kicker' => 'Syarat & Ketentuan',
        'title' => 'S&K Sewa Motor di Renmote',
        'subtitle' => 'Ketentuan lengkap berlaku untuk seluruh transaksi sewa di platform Renmote, baik untuk penyewa maupun vendor terdaftar. Dengan menggunakan layanan ini, kamu menyetujui isi berikut.',

        'tab_renter' => 'Untuk Penyewa',
        'tab_vendor' => 'Untuk Vendor',

        'renter_sections' => [
            [
                'title' => '1. Persyaratan Penyewa',
                'items' => [
                    'Wajib berusia minimal 18 tahun atau memiliki KTP/KTM aktif.',
                    'Wajib mengunggah KTP/KTM melalui menu akun saat pertama kali memesan.',
                    'SIM C aktif sangat dianjurkan dan dapat diminta vendor sebelum unit diserahkan.',
                    'Data pemesanan (nama, alamat, kontak) harus sesuai identitas pribadi.',
                ],
            ],
            [
                'title' => '2. Pemesanan & Pembayaran',
                'items' => [
                    'Pemesanan aktif setelah pembayaran DP 30% berhasil dan bukti pembayaran diverifikasi.',
                    'DP dibayar lewat Midtrans (QRIS, GoPay, ShopeePay, atau Virtual Account). Sisa 70% dilunasi saat serah-terima.',
                    'Token pembayaran Snap berlaku 30 menit. Setelah expired, kamu bisa mengajukan ulang invoice.',
                    'Vendor dan admin punya hak menolak booking jika dokumen/pembayaran tidak valid.',
                ],
            ],
            [
                'title' => '3. Pembatalan & Refund',
                'items' => [
                    'Pembatalan berstatus Pending oleh penyewa: DP dapat di-refund sesuai kebijakan vendor.',
                    'Pembatalan setelah Confirmed: DP biasanya hangus karena unit sudah diblokir untuk kamu.',
                    'Vendor menolak bukti pembayaran: kamu wajib upload ulang dalam 1×24 jam.',
                    'Refund diproses melalui transfer manual; lama proses 3–7 hari kerja sesuai bank.',
                ],
            ],
            [
                'title' => '4. Penggunaan Kendaraan',
                'items' => [
                    'Motor hanya boleh digunakan untuk aktivitas legal dan wajar (tidak untuk balap, ojek online, atau kegiatan ilegal).',
                    'Dilarang memindahtangankan unit ke pihak lain tanpa izin tertulis dari vendor.',
                    'Patuhi rambu lalu lintas, batas kecepatan, dan area operasional yang disepakati.',
                    'Helm wajib dipakai oleh pengendara dan penumpang.',
                ],
            ],
            [
                'title' => '5. Keterlambatan & Perpanjangan',
                'items' => [
                    'Keterlambatan pengembalian dapat dikenakan biaya tambahan per jam atau per hari sesuai kebijakan vendor.',
                    'Permintaan perpanjangan wajib diajukan minimal 6 jam sebelum waktu sewa berakhir lewat Chat.',
                    'Perpanjangan disetujui jika unit belum dipesan oleh penyewa lain.',
                ],
            ],
            [
                'title' => '6. Kerusakan & Kehilangan',
                'items' => [
                    'Penyewa bertanggung jawab atas kerusakan akibat kelalaian selama masa sewa.',
                    'Kehilangan unit, kunci, STNK, atau plat nomor diselesaikan sesuai prosedur hukum.',
                    'Biaya perbaikan dihitung berdasarkan estimasi bengkel resmi atau hasil pemeriksaan bersama.',
                    'Renmote bukan pihak yang menanggung kerusakan; tanggung jawab ada pada penyewa dan vendor.',
                ],
            ],
        ],

        'vendor_sections' => [
            [
                'title' => '1. Persyaratan Pendaftaran Vendor',
                'items' => [
                    'Wajib memiliki KTP aktif dan dokumen izin usaha (SIUP/NIB/TDP) atau surat keterangan usaha dari kelurahan.',
                    'Wajib menyerahkan foto profil/lokasi usaha untuk verifikasi.',
                    'Vendor harus berdomisili di area operasional Renmote (saat ini fokus Kota Malang dan sekitarnya).',
                    'Pendaftaran ditinjau admin maksimal 3×24 jam kerja. Vendor tidak bisa menerima booking sebelum status Approved.',
                ],
            ],
            [
                'title' => '2. Kewajiban Vendor',
                'items' => [
                    'Memastikan motor dalam kondisi laik jalan sebelum diserahkan ke penyewa.',
                    'Menyediakan helm dan perlengkapan keamanan dasar (minimal 1 helm per booking).',
                    'Memverifikasi bukti pembayaran DP penyewa maksimal 1×24 jam.',
                    'Konfirmasi/menolak booking sebelum hari H, tidak boleh ghosting penyewa.',
                    'Update status booking secara berkala (Confirmed, Completed, Cancelled).',
                ],
            ],
            [
                'title' => '3. Harga & Komisi Platform',
                'items' => [
                    'Vendor bebas menentukan harga sewa per hari; harga ditampilkan transparan ke penyewa.',
                    'Renmote menerapkan komisi platform sesuai perjanjian kerja sama (dijelaskan saat onboarding).',
                    'Pencairan dana ke rekening vendor mengikuti siklus settlement yang berlaku.',
                ],
            ],
            [
                'title' => '4. Pembatalan Sepihak oleh Vendor',
                'items' => [
                    'Vendor dilarang membatalkan booking secara sepihak tanpa alasan jelas.',
                    'Pembatalan akibat kondisi unit (rusak/tabrakan) wajib diinformasikan ke penyewa dan admin.',
                    'Vendor wajib me-refund DP 100% jika pembatalan sepihak terbukti tanpa alasan operasional.',
                    'Pelanggaran berulang dapat berujung suspensi atau pencabutan status vendor.',
                ],
            ],
            [
                'title' => '5. Tanggung Jawab Hukum',
                'items' => [
                    'Vendor wajib memastikan kelengkapan dokumen kendaraan (STNK aktif, plat valid, pajak terbayar).',
                    'Kerusakan/kehilangan akibat kondisi motor yang tidak laik jalan menjadi tanggung jawab vendor.',
                    'Vendor wajib bekerja sama dengan penyewa dan pihak berwenang jika terjadi kecelakaan.',
                ],
            ],
            [
                'title' => '6. Pelanggaran & Sanksi',
                'items' => [
                    'Pelanggaran ringan (terlambat verifikasi, lambat respon chat): peringatan tertulis.',
                    'Pelanggaran berat (penipuan, motor tidak laik, mark-up sepihak): suspensi 7–30 hari.',
                    'Pelanggaran sangat berat (kekerasan ke penyewa, dokumen palsu): pencabutan permanen + pelaporan hukum.',
                ],
            ],
        ],

        'reference_title' => 'Rujukan Hukum',
        'reference_text' => 'S&K ini disusun mengikuti UU No. 22 Tahun 2009 tentang Lalu Lintas dan Angkutan Jalan, KUH Perdata mengenai sewa-menyewa, serta praktik umum platform digital di Indonesia.',
        'ref_uu_2009' => 'UU No. 22 Tahun 2009 (BPK RI)',
        'ref_korlantas' => 'Informasi SIM dan legalitas pengemudi - Korlantas Polri',
        'ref_jdih' => 'JDIH Kemenhub untuk regulasi transportasi',
    ],
];
