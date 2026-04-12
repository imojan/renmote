@extends('layouts.front')

@section('title', 'Cara Sewa Motor Online')

@section('content')
<section class="section info-page-section">
    <div class="info-page-hero">
        <span class="info-page-kicker">Panduan</span>
        <h1 class="info-page-title">Cara Sewa Motor Online</h1>
        <p class="info-page-subtitle">
            Alur praktis untuk booking motor secara online dengan aman, cepat, dan sesuai praktik umum rental motor di Indonesia.
        </p>
    </div>

    <div class="info-card-grid">
        <article class="info-step-card">
            <span class="info-step-number">01</span>
            <h3>Pilih Lokasi dan Tanggal Sewa</h3>
            <p>Tentukan kecamatan, tanggal mulai, dan tanggal selesai agar unit yang tampil benar-benar tersedia sesuai kebutuhan perjalanan.</p>
        </article>
        <article class="info-step-card">
            <span class="info-step-number">02</span>
            <h3>Pilih Unit yang Sesuai</h3>
            <p>Bandingkan tipe motor, kapasitas mesin, harga per hari, serta ulasan. Prioritaskan motor yang cocok untuk rute dan jumlah penumpang.</p>
        </article>
        <article class="info-step-card">
            <span class="info-step-number">03</span>
            <h3>Lengkapi Data Penyewa</h3>
            <p>Siapkan identitas aktif seperti KTP dan SIM C. Verifikasi data yang jelas membantu vendor mempercepat proses konfirmasi pesanan.</p>
        </article>
        <article class="info-step-card">
            <span class="info-step-number">04</span>
            <h3>Konfirmasi Pembayaran</h3>
            <p>Lanjutkan pembayaran sesuai instruksi sistem. Simpan bukti transaksi dan pastikan nominal, durasi, serta titik serah-terima sudah sesuai.</p>
        </article>
        <article class="info-step-card">
            <span class="info-step-number">05</span>
            <h3>Serah Terima dan Cek Unit</h3>
            <p>Periksa rem, lampu, ban, klakson, dan bensin. Dokumentasikan kondisi bodi (foto/video) sebelum berangkat untuk menghindari miskomunikasi.</p>
        </article>
        <article class="info-step-card">
            <span class="info-step-number">06</span>
            <h3>Gunakan dan Kembalikan Tepat Waktu</h3>
            <p>Gunakan motor sesuai ketentuan vendor, patuhi aturan lalu lintas, lalu kembalikan unit sesuai jadwal untuk menghindari biaya keterlambatan.</p>
        </article>
    </div>

    <div class="info-highlight">
        <h2>Tips Singkat Biar Proses Makin Aman</h2>
        <ul>
            <li>Pilih vendor dengan profil jelas dan kanal komunikasi aktif.</li>
            <li>Pastikan titik jemput/pengembalian, jam operasional, dan biaya tambahan sudah disepakati di awal.</li>
            <li>Gunakan metode pembayaran yang tercatat agar ada bukti transaksi.</li>
            <li>Jika ada perubahan jadwal, segera informasikan vendor agar status booking tetap aman.</li>
        </ul>
    </div>

    <div class="info-reference-box">
        <h2>Rujukan Konten</h2>
        <p>
            Konten panduan ini dirangkum dari praktik umum layanan sewa motor online di Indonesia dan prinsip berkendara legal,
            termasuk kewajiban kepemilikan SIM sesuai jenis kendaraan (rujukan umum: UU No. 22 Tahun 2009, Pasal 77 ayat 1).
        </p>
        <ul class="info-reference-list">
            <li><a href="https://peraturan.bpk.go.id/Details/38654/uu-no-22-tahun-2009" target="_blank" rel="noopener noreferrer">UU No. 22 Tahun 2009 tentang Lalu Lintas dan Angkutan Jalan (BPK RI)</a></li>
            <li><a href="https://korlantas.polri.go.id/sim/" target="_blank" rel="noopener noreferrer">Informasi SIM - Korlantas Polri</a></li>
            <li><a href="https://jdih.dephub.go.id/" target="_blank" rel="noopener noreferrer">Portal JDIH Kementerian Perhubungan</a></li>
        </ul>
    </div>
</section>
@endsection
