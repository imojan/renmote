<?php

return [
    /* ── Sidebar ─────────────────────────────────────────────────── */
    'sidebar' => [
        'menu_label' => 'MENU',
        'home' => 'Dashboard',
        'vendors' => 'Vendor',
        'users' => 'Penyewa',
        'vehicles' => 'Kendaraan',
        'bookings' => 'Pesanan',
        'bookings_admin' => 'Pemesanan',
        'chat' => 'Chat',
        'chat_customers' => 'Chat Pelanggan',
        'notifications' => 'Notifikasi',
        'articles' => 'Artikel',
        'documents' => 'Dokumen',
        'settings' => 'Pengaturan',
        'profile' => 'Profil',
        'addresses' => 'Alamat',
        'search_vehicles' => 'Cari Kendaraan',
        'my_bookings' => 'Booking Saya',
    ],

    /* ── Topbar ──────────────────────────────────────────────────── */
    'topbar' => [
        'title' => 'Dashboard',
        'profile' => 'Profil',
        'logout' => 'Logout',
        'notifications_aria' => 'Notifikasi',
    ],

    /* ── Vendor dashboard ────────────────────────────────────────── */
    'vendor' => [
        'page_title' => 'Vendor Dashboard',
        'status_verified' => 'Terverifikasi',
        'status_rejected' => 'Ditolak',
        'status_pending' => 'Menunggu Verifikasi',
        'rejected_heading' => 'Pengajuan ditolak',
        'rejected_no_reason' => 'Admin belum menuliskan alasan penolakan.',
        'rejected_action' => 'Perbaiki Data & Ajukan Ulang',

        'documents_status' => 'Status Dokumen Pengajuan',
        'document_no_notes' => 'Belum ada catatan tambahan dari admin untuk dokumen ini.',
        'view_document' => 'Lihat Dokumen',
        'doc_status_approved' => 'Disetujui',
        'doc_status_rejected' => 'Ditolak',
        'doc_status_pending' => 'Menunggu',

        'stat_total_vehicles' => 'Total Kendaraan',
        'stat_total_bookings' => 'Total Pesanan',
        'stat_pending_bookings' => 'Pesanan Pending',
        'stat_revenue' => 'Total Pendapatan',

        'quick_actions' => 'Aksi Cepat',
        'add_vehicle' => 'Tambah Kendaraan',
        'view_bookings' => 'Lihat Pesanan',
    ],

    /* ── Admin dashboard ─────────────────────────────────────────── */
    'admin' => [
        'page_title' => 'Dashboard',
        'welcome' => 'Selamat datang, :name! 👋',
        'welcome_subtitle' => 'Berikut ringkasan sistem Renmote hari ini.',

        'stat_total_users' => 'Total Penyewa',
        'stat_total_users_sub' => 'Pengguna terdaftar',
        'stat_total_vendors' => 'Total Vendor',
        'stat_total_vendors_sub' => ':count menunggu verifikasi',
        'stat_total_vehicles' => 'Total Kendaraan',
        'stat_total_vehicles_sub' => 'Terdaftar di platform',
        'stat_total_bookings' => 'Total Pesanan',
        'stat_total_bookings_sub' => 'Transaksi tercatat',
        'stat_pending_documents' => 'Dokumen Pending',
        'stat_pending_documents_sub' => 'Butuh review',

        'pending_vendors_title' => 'Vendor Menunggu Verifikasi',
        'view_all' => 'Lihat Semua →',
        'pending_vendors_empty' => 'Semua vendor sudah diverifikasi',

        'quick_menu' => 'Menu Cepat',
        'quick_manage_vendors' => 'Kelola Vendor',
        'quick_manage_vehicles' => 'Kelola Kendaraan',
        'quick_manage_bookings' => 'Kelola Pemesanan',
        'quick_documents' => 'Arsip Dokumen',
        'quick_settings' => 'Pengaturan Akun',

        'recent_users' => 'Penyewa Terbaru',
        'col_name' => 'Nama',
        'col_email' => 'Email',
        'col_role' => 'Peran',
        'col_joined' => 'Bergabung',
        'role_admin' => 'Admin',
        'role_vendor' => 'Vendor',
        'role_user' => 'Penyewa',
        'no_users' => 'Belum ada user terdaftar',

        'recent_bookings' => 'Pesanan Terbaru',
        'col_renter' => 'Penyewa',
        'col_vehicle' => 'Kendaraan',
        'col_status' => 'Status',
        'col_date' => 'Tanggal',
        'col_store' => 'Toko',
        'no_bookings' => 'Belum ada pesanan',

        'badge_pending' => 'Pending',
        'badge_confirmed' => 'Dikonfirmasi',
        'badge_completed' => 'Selesai',
        'badge_cancelled' => 'Dibatalkan',
    ],

    /* ── User dashboard ──────────────────────────────────────────── */
    'user' => [
        'page_title' => 'User Dashboard',
        'stat_active_bookings' => 'Pesanan Aktif',
        'stat_total_bookings' => 'Total Pesanan',
        'active_bookings_title' => 'Pesanan Aktif',
        'history_title' => 'Riwayat Pesanan',
        'col_vehicle' => 'Kendaraan',
        'col_dates' => 'Tanggal',
        'col_total' => 'Total',
        'col_status' => 'Status',
        'col_actions' => 'Aksi',
        'action_detail' => 'Detail',
        'action_cancel' => 'Batalkan',
        'no_active' => 'Tidak ada pesanan aktif.',
        'no_history' => 'Belum ada riwayat pesanan.',
        'cancel_confirm_title' => 'Batalkan pesanan?',
        'cancel_confirm_message' => 'Pesanan ini akan dibatalkan. Lanjutkan?',
        'cancel_confirm_yes' => 'Ya, Batalkan',
        'cancel_confirm_no' => 'Tidak',

        'status_pending' => 'Menunggu',
        'status_confirmed' => 'Dikonfirmasi',
        'status_completed' => 'Selesai',
        'status_cancelled' => 'Dibatalkan',
    ],
];
