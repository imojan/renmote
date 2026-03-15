# Flow System Web Rental (User, Vendor, Admin)

## 1. Gambaran Umum Sistem

Platform ini adalah web rental kendaraan berbasis Laravel dengan 3 role utama:

- User: mencari kendaraan, membuat booking, mengelola alamat, memantau status booking.
- Vendor: mendaftarkan toko, upload dokumen verifikasi, mengelola kendaraan, memproses booking.
- Admin: memverifikasi vendor, memantau kendaraan dan booking, melakukan kontrol operasional.

Pilar alur sistem:

- Role-based access control melalui middleware role.
- Booking berbasis rentang tanggal dengan pengecekan overlap availability.
- Siklus status booking: pending -> confirmed -> completed (atau cancelled).
- Siklus status vendor: pending -> approved/rejected.
- Pembayaran awal menggunakan DP (30%) dengan status payment pending/paid.

## 2. Alur End-to-End (Big Picture)

1. Pengunjung membuka halaman utama, melakukan pencarian kendaraan, lalu melihat detail kendaraan/vendor.
2. Pengguna registrasi/login sebagai user atau vendor.
3. User membuat booking kendaraan dengan tanggal sewa.
4. Sistem mengecek ketersediaan kendaraan dan membuat booking status pending.
5. Sistem membuat data pembayaran DP 30% status pending.
6. Vendor menerima daftar booking untuk kendaraan miliknya dan bisa konfirmasi/selesaikan booking.
7. Admin memonitor seluruh data dan dapat mengubah status booking bila diperlukan.
8. Untuk sisi vendor baru, admin melakukan verifikasi vendor berdasarkan dokumen yang diunggah.

## 3. Flow Detail Per Role

## 3.1 User Flow

### A. Discovery & Browse

1. User mengakses landing page.
2. User dapat mencari kendaraan berdasarkan:
   - keyword (nama/kategori/deskripsi),
   - district,
   - kategori,
   - rentang tanggal (start_date dan end_date).
3. Sistem menampilkan kendaraan yang statusnya available.
4. Jika filter tanggal dipakai, sistem melakukan pengecekan availability untuk tiap kendaraan.

### B. Account & Verification

1. User registrasi dengan role user atau vendor.
2. User dapat login/logout via auth standar.
3. User bisa verifikasi nomor HP dengan OTP:
   - kirim OTP (rate limited),
   - verifikasi OTP,
   - status is_phone_verified menjadi true jika valid.

### C. Address Management

1. User menambah alamat.
2. Jika alamat pertama, otomatis jadi default.
3. Jika set alamat baru sebagai default, default lama dinonaktifkan.
4. User bisa update/hapus alamat sendiri.

### D. Booking Flow

1. User pilih kendaraan lalu isi start_date dan end_date.
2. Validasi input tanggal:
   - start_date minimal hari ini,
   - end_date >= start_date.
3. Sistem cek ketersediaan kendaraan (tidak boleh overlap dengan booking non-cancelled).
4. Jika tersedia, sistem membuat booking:
   - status: pending,
   - total_price: price_per_day x jumlah hari (inklusif).
5. Sistem otomatis membuat payment DP:
   - payment_type: dp,
   - amount: 30% dari total_price,
   - status: pending.
6. User melihat daftar booking dan detail booking di dashboard.
7. User dapat cancel booking hanya jika status masih pending.

### E. User Dashboard

1. Active booking: status pending dan confirmed.
2. Riwayat booking: status completed dan cancelled.

## 3.2 Vendor Flow

### A. Vendor Registration

1. User login lalu submit pendaftaran vendor.
2. Sistem membuat data vendor dengan status pending dan verified=false.
3. Sistem menerima upload dokumen vendor (ktp wajib, permit/photo opsional sesuai validasi), lalu simpan ke private storage.
4. Sistem mengubah role user menjadi vendor.
5. Sistem trigger event VendorRegistered dan kirim notifikasi ke admin.

### B. Vendor Verification Lifecycle

1. Setelah pendaftaran, vendor menunggu proses review admin.
2. Dokumen vendor bisa direview melalui signed URL sementara.
3. Jika disetujui admin:
   - vendor verified=true,
   - status=approved.
4. Jika ditolak admin:
   - vendor verified=false,
   - status=rejected.

### C. Vehicle Management

1. Vendor membuat data kendaraan:
   - nama, kategori, harga/hari, tahun, deskripsi, image, stock.
2. Kendaraan baru default status available.
3. Vendor bisa edit/hapus kendaraan miliknya sendiri.
4. Otorisasi kepemilikan kendaraan dicek sebelum edit/delete.

### D. Booking Processing

1. Vendor melihat booking untuk kendaraan miliknya.
2. Vendor bisa membuka detail booking.
3. Vendor bisa confirm booking jika status pending.
4. Vendor bisa complete booking jika status confirmed.
5. Vendor tidak dapat memproses booking di luar kepemilikannya (403).

### E. Vendor API Support

1. Vendor profile API menampilkan data profil, dokumen, kendaraan, status verifikasi.
2. Vendor document API mendukung upload/reupload dokumen per tipe (ktp/permit/photo), dengan reset status dokumen ke pending saat replace.

## 3.3 Admin Flow

### A. Monitoring Dashboard

1. Admin melihat metrik utama:
   - total user, vendor, kendaraan, booking,
   - pending vendors,
   - pending documents,
   - recent users,
   - pending vendor list,
   - recent bookings.

### B. Vendor Governance

1. Admin membuka list vendor (bisa filter pending/verified).
2. Admin membuka detail vendor termasuk dokumen dan kendaraan.
3. Admin dapat verify (approve) vendor:
   - update status vendor,
   - kirim notifikasi VendorApproved.
4. Admin dapat unverify/reject vendor:
   - update status vendor,
   - kirim notifikasi VendorRejected (+ alasan opsional).
5. Admin bisa menghapus vendor.

### C. Vehicle Oversight

1. Admin melihat seluruh kendaraan lintas vendor.
2. Admin bisa melihat detail kendaraan dan relasi booking.
3. Admin bisa menghapus kendaraan.

### D. Booking Oversight

1. Admin melihat seluruh booking lintas user/vendor.
2. Admin membuka detail booking.
3. Admin bisa update status booking secara manual:
   - pending, confirmed, cancelled, completed.

## 4. Status & Transition Matrix

## 4.1 Booking Status

- pending: booking baru dibuat.
- confirmed: booking sudah disetujui vendor/admin.
- completed: rental selesai.
- cancelled: booking dibatalkan.

Transisi umum:

- pending -> confirmed (vendor/admin)
- pending -> cancelled (user/admin)
- confirmed -> completed (vendor/admin)

## 4.2 Vendor Status

- pending: vendor menunggu verifikasi.
- approved: vendor lolos verifikasi.
- rejected: vendor ditolak atau verifikasi dicabut.

Transisi umum:

- pending -> approved (admin verify)
- pending/approved -> rejected (admin unverify/reject)

## 4.3 Payment Status

- pending: payment dibuat tapi belum lunas.
- paid: payment sudah dibayar.

Tipe payment:

- dp: down payment (30%).
- full: pembayaran penuh (fitur service tersedia).

## 5. Ringkasan Rules Bisnis Penting

1. Route role-protected: user, vendor, admin dipisah dengan middleware.
2. Booking tidak bisa dibuat bila kendaraan tidak available pada rentang tanggal.
3. Booking overlap dianggap konflik jika booking existing bukan cancelled.
4. Cancel oleh user hanya di status pending.
5. Confirm oleh vendor hanya saat pending; complete hanya saat confirmed.
6. Vendor hanya bisa kelola kendaraan/booking miliknya sendiri.
7. Dokumen vendor disimpan private; akses review via signed URL bertempo.
8. OTP memiliki TTL dan batas percobaan kirim/verify.

## 6. Catatan Arsitektur untuk Tahap Brainstorming Lanjutan

1. Alur pembayaran saat ini baru sampai pembuatan record DP/full dan perubahan status paid secara service-level; integrasi payment gateway belum terlihat pada route/controller utama.
2. Mekanisme stock kendaraan sudah ada di schema, tetapi availability saat ini berbasis overlap booking per vehicle, belum menghitung kapasitas stock > 1.
3. Beberapa endpoint API dan web sudah siap sebagai fondasi untuk dashboard/mobile integration.

Dokumen ini dapat dipakai sebagai baseline flow sistem untuk diskusi lanjutan: detail UX, SOP operasional admin, integrasi payment gateway, dan kebijakan refund/cancellation.