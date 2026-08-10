<td class="py-3 px-2 text-gray-600">{{ $loan->tanggal_pinjam->format('d M Y') }}</td>


---

## 🔐 1. Sistem Otorisasi (Hak Akses)
Sistem ini memiliki dua level pengguna (`role`) di dalam tabel `users`:

### A. Admin
Memiliki akses penuh (Super User) terhadap sistem. Fokus tugas Admin adalah pengelolaan data induk (master data), konfigurasi sistem, dan monitoring laporan.
- Bisa mengakses **semua menu**.
- Bisa menambah, mengubah, dan menghapus data master (Buku, Kategori, Rak, Penulis, Penerbit).
- Bisa mengelola akun petugas/admin lain.
- Bisa mengubah pengaturan sistem (Denda, Lama Pinjam, Nama Perpustakaan).

### B. Petugas
Hak akses operasional sehari-hari (Frontliner). Fokus tugas Petugas adalah melayani anggota di loket perpustakaan.
- **Tidak bisa** mengakses menu Master Data (Kategori, Penulis, Rak, Pengaturan, Manajemen User). Jika memaksa akses via URL, sistem akan menolak (403/Redirect).
- **Bisa melihat** data Buku (untuk mengecek stok), tetapi **tidak bisa** mengubah/menghapusnya.
- Bisa melakukan CRUD Data Anggota.
- Bisa melakukan transaksi Peminjaman dan Pengembalian.
- Bisa mengelola pembayaran Denda.

---

## 🔄 2. Alur Aplikasi (Workflow Operasional)

### Alur 1: Persiapan Data (Oleh Admin)
1. Admin login ke sistem.
2. Admin menginput data pendukung: **Kategori**, **Penulis**, **Penerbit**, dan **Rak/Etalase**.
3. Admin menambahkan **Buku** beserta stok jumlahnya. Sistem otomatis mengisi stok `tersedia` sama dengan `jumlah` awal.
4. Admin mengatur **Pengaturan Sistem** (Settings), seperti Denda Rp1.000/hari dan Batas Pinjam 7 hari.

### Alur 2: Pendaftaran Anggota (Oleh Petugas/Admin)
1. Calon anggota datang ke loket.
2. Petugas menginput data anggota baru (Nama, NIS/NIP, Alamat, dll).
3. Sistem otomatis generate `kode_member`.
4. Anggota siap untuk meminjam buku.

### Alur 3: Peminjaman Buku (Oleh Petugas)
1. Anggota membawa buku ke loket.
2. Petugas mencari data anggota di sistem. Sistem mengecek:
   - Apakah anggota berstatus `aktif`?
   - Apakah anggota memiliki denda `belum_bayar`? (Jika ya, tidak boleh meminjam).
   - Apakah anggota sudah mencapai `batas_jumlah_buku` (dari settings)?
3. Petugas memindai/mencari buku. Sistem mengecek apakah stok `tersedia` > 0.
4. Petugas menyimpan transaksi `loans`. Sistem otomatis:
   - Mengurangi stok `tersedia` pada tabel `books`.
   - Menentukan `tanggal_kembali` (Tanggal hari ini + `maksimal_hari_pinjam`).
   - Status transaksi menjadi `dipinjam`.

### Alur: Pengembalian Buku & Denda (Oleh Petugas)
1. Anggota datang mengembalikan buku.
2. Petugas mencari transaksi peminjaman aktif (berdasarkan kode/nama anggota).
3. Petugas memproses tombol "Kembalikan". Sistem otomatis:
   - Mengecek tanggal hari ini vs `tanggal_kembali`.
   - Jika terlambat, sistem menghitung: *(Selisih Hari × `denda_per_hari`)*.
   - Menambah kembali stok `tersedia` pada tabel `books`.
   - Mengubah status transaksi `loans` menjadi `dikembalikan`.
4. Jika ada denda, sistem mencatatnya di tabel `fines` dengan status `belum_bayar`. Anggota membayar ke petugas, lalu petugas mengubah status denda menjadi `lunas`.

---

## 🛠️ 3. Daftar Fitur Aplikasi

### 1. Fitur Autentikasi & Profil
- **Login System**: Menggunakan Username atau Email beserta Password.
- **Logout**: Mengakhiri sesi pengguna.
- **Profil Pengguna**: Mengganti foto profil, nama, dan password masing-masing.

### 2. Dashboard (Admin & Petugas)
- **Ringkasan Data (Cards)**: Total Buku, Total Anggota, Buku Sedang Dipinjam, Denda Belum Lunas.
- **Aktivitas Terbaru**: List 5 transaksi peminjaman/pengembalian terakhir.

### 3. Modul Master Data (Khusus Admin)
- **Manajemen User**: CRUD akun Admin/Petugas.
- **Manajemen Kategori**: CRUD kategori buku (Fiksi, Sains, Sejarah, dll).
- **Manajemen Penulis**: CRUD data penulis.
- **Manajemen Penerbit**: CRUD data penerbit.
- **Manajemen Rak/Etalase**: CRUD lokasi penyimpanan buku lengkap dengan kapasitas rak.
- **Manajemen Buku**:
  - CRUD data buku.
  - Upload cover buku.
  - Relasi ke Kategori, Penulis, Penerbit, dan Rak.
  - Auto-update stok tersedia.

### 4. Modul Operasional (Admin & Petugas)
- **Manajemen Anggota**:
  - CRUD data anggota perpustakaan.
  - Cetak Kartu Anggota (PDF).
- **Transaksi Peminjaman**:
  - Form pencarian anggota cepat (Ajax).
  - Form pencarian buku cepat.
  - Validasi otomatis (cek denda, cek batas maksimal pinjaman).
  - Keranjang peminjaman (bisa pinjam beberapa buku sekali transaksi).
- **Transaksi Pengembalian**:
  - Form pencarian data peminjaman aktif.
  - Kalkulasi otomatis denda keterlambatan.
  - Proses restorasi stok buku otomatis.
- **Manajemen Denda**:
  - Daftar anggota yang memiliki denda tertunggak.
  - Proses pelunasan denda (Update status bayar).

### 5. Modul Pengaturan (Khusus Admin)
- **Settings Form**: Form tunggal (singleton) untuk mengubah:
  - Nama, Email, Telepon, Alamat Perpustakaan.
  - Logo Perpustakaan.
  - Nominal Denda per Hari.
  - Maksimal Hari Peminjaman.
  - Maksimal Jumlah Buku per Transaksi.

### 6. Modul Laporan (Khusus Admin)
- **Laporan Data Buku**: Rekapitulasi stok buku (Total vs Tersedia).
- **Laporan Transaksi**: History peminjaman & pengembalian dengan filter rentang tanggal.
- **Laporan Keuangan Denda**: Rekap pemasukan denda dengan filter bulan/tanggal.
- **Export Data**: Semua laporan dapat di-export ke format PDF atau Excel.

---