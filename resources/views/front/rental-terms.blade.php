@extends('layouts.front')

@section('title', 'S&K Sewa Motor Online')

@section('content')
<section class="section info-page-section">
    <div class="info-page-hero">
        <span class="info-page-kicker">Syarat & Ketentuan</span>
        <h1 class="info-page-title">S&K Sewa Motor Online</h1>
        <p class="info-page-subtitle">
            Ringkasan ketentuan umum agar proses sewa transparan bagi penyewa dan vendor. Isi dapat disesuaikan kebijakan operasional Renmote.
        </p>
    </div>

    <div class="terms-grid">
        <article class="terms-card">
            <h3>1. Persyaratan Penyewa</h3>
            <ul>
                <li>Wajib memiliki identitas resmi yang masih berlaku (contoh: KTP).</li>
                <li>Wajib memiliki SIM C aktif untuk mengendarai sepeda motor.</li>
                <li>Data pemesanan harus sesuai dengan identitas penyewa.</li>
            </ul>
        </article>

        <article class="terms-card">
            <h3>2. Pemesanan dan Pembayaran</h3>
            <ul>
                <li>Pemesanan dianggap aktif setelah pembayaran terkonfirmasi sistem/vendor.</li>
                <li>Harga sewa mengikuti durasi, tipe unit, dan periode pemesanan.</li>
                <li>Deposit dapat diberlakukan sesuai profil risiko dan kebijakan vendor.</li>
            </ul>
        </article>

        <article class="terms-card">
            <h3>3. Penggunaan Kendaraan</h3>
            <ul>
                <li>Kendaraan hanya digunakan untuk aktivitas legal dan wajar.</li>
                <li>Dilarang memindahtangankan unit kepada pihak lain tanpa izin vendor.</li>
                <li>Penyewa wajib mematuhi aturan lalu lintas selama masa sewa.</li>
            </ul>
        </article>

        <article class="terms-card">
            <h3>4. Keterlambatan, Perpanjangan, Pembatalan</h3>
            <ul>
                <li>Keterlambatan pengembalian dapat dikenakan biaya tambahan per jam/hari.</li>
                <li>Permintaan perpanjangan sewa wajib diajukan sebelum waktu sewa berakhir.</li>
                <li>Pembatalan mengikuti jendela waktu dan kebijakan refund yang berlaku.</li>
            </ul>
        </article>

        <article class="terms-card">
            <h3>5. Kerusakan dan Kehilangan</h3>
            <ul>
                <li>Penyewa bertanggung jawab atas kerusakan akibat kelalaian selama masa sewa.</li>
                <li>Kehilangan unit atau dokumen kendaraan ditangani sesuai prosedur hukum.</li>
                <li>Biaya perbaikan/kompensasi disesuaikan hasil pemeriksaan bersama.</li>
            </ul>
        </article>

        <article class="terms-card">
            <h3>6. Keselamatan dan Tanggung Jawab</h3>
            <ul>
                <li>Penyewa wajib menggunakan helm dan perlengkapan berkendara yang layak.</li>
                <li>Vendor wajib menyerahkan unit dalam kondisi laik jalan.</li>
                <li>Kedua pihak wajib menjaga komunikasi aktif saat terjadi kendala di perjalanan.</li>
            </ul>
        </article>
    </div>

    <div class="info-reference-box">
        <h2>Rujukan Relevan</h2>
        <p>
            Struktur S&K ini disusun dari praktik umum industri rental motor online di Indonesia serta prinsip legal berkendara
            (termasuk kepemilikan SIM sesuai jenis kendaraan pada UU No. 22 Tahun 2009).
            Silakan sesuaikan detail nominal denda, deposit, dan refund sesuai kebijakan operasional final.
        </p>
        <ul class="info-reference-list">
            <li><a href="https://peraturan.bpk.go.id/Details/38654/uu-no-22-tahun-2009" target="_blank" rel="noopener noreferrer">UU No. 22 Tahun 2009 (BPK RI)</a></li>
            <li><a href="https://korlantas.polri.go.id/sim/" target="_blank" rel="noopener noreferrer">Informasi SIM dan legalitas pengemudi - Korlantas Polri</a></li>
            <li><a href="https://jdih.dephub.go.id/" target="_blank" rel="noopener noreferrer">JDIH Kemenhub untuk penelusuran regulasi transportasi</a></li>
        </ul>
    </div>
</section>
@endsection
