# PROPOSAL UJI KOMPETENSI KEAHLIAN

## RANCANG BANGUN SISTEM INFORMASI PERPUSTAKAAN BERBASIS WEB
### (E-PERPUS)

**Diajukan untuk memenuhi salah satu persyaratan Uji Kompetensi Keahlian (UKK)**

Disusun oleh:

**Nama:** [Nama Peserta Didik]  
**NIS/NISN:** [Nomor Induk]  
**Kelas:** [Kelas]

**[NAMA PROGRAM KEAHLIAN]**  
**[NAMA SEKOLAH]**  
**[KOTA/KABUPATEN]**  
**[TAHUN]**

---

# LEMBAR PENGESAHAN

Proposal Uji Kompetensi Keahlian dengan judul **“Rancang Bangun Sistem Informasi Perpustakaan Berbasis Web (E-Perpus)”** ini telah diperiksa dan disetujui sebagai salah satu dokumen pelaksanaan Uji Kompetensi Keahlian.

| | |
|---|---|
| Hari/Tanggal | [Hari, tanggal] |
| Tempat | [Nama sekolah] |

| Mengetahui, | Menyetujui, |
|---|---|
| Kepala Program Keahlian | Pembimbing |
|  |  |
|  |  |
| **[Nama Kepala Program]** | **[Nama Pembimbing]** |
| NIP. [Nomor NIP] | NIP. [Nomor NIP] |

Peserta didik,



**[Nama Peserta Didik]**  
NIS/NISN. [Nomor Induk]

---

# KATA PENGANTAR

Puji syukur ke hadirat Tuhan Yang Maha Esa karena atas rahmat dan karunia-Nya, proposal Uji Kompetensi Keahlian yang berjudul **“Rancang Bangun Sistem Informasi Perpustakaan Berbasis Web (E-Perpus)”** ini dapat disusun. Proposal ini dibuat sebagai gambaran rencana pengembangan aplikasi perpustakaan berbasis web yang membantu pengelolaan data buku, anggota, peminjaman, pengembalian, booking buku, dan denda.

E-Perpus dirancang agar kegiatan pengelolaan perpustakaan dapat dilakukan melalui satu aplikasi. Admin dan petugas mengelola data serta transaksi, sedangkan anggota dapat melihat katalog dan mengajukan booking setelah akunnya terhubung dengan data anggota. Informasi transaksi dan perubahan tertentu juga dicatat dalam riwayat sistem.

Penulis menyadari bahwa proposal ini masih perlu disesuaikan dengan kondisi perpustakaan, ketentuan sekolah, dan arahan pembimbing. Oleh karena itu, penulis mengharapkan saran dan masukan untuk menyempurnakan proposal maupun aplikasi yang dikembangkan.

Penulis mengucapkan terima kasih kepada kepala sekolah, kepala program keahlian, guru pembimbing, keluarga, dan semua pihak yang memberikan dukungan dalam penyusunan proposal ini. Semoga proposal ini bermanfaat sebagai pedoman pelaksanaan UKK dan pengembangan E-Perpus.

[Kota], [Tanggal penyusunan]

Penulis,

**[Nama Peserta Didik]**

---

# DAFTAR ISI

*Nomor halaman pada daftar isi perlu diperbarui otomatis setelah naskah ditata di Word atau aplikasi pengolah dokumen.*

- LEMBAR PENGESAHAN
- KATA PENGANTAR
- DAFTAR ISI
- BAB I PENDAHULUAN
  - 1.1 Latar Belakang
  - 1.2 Identifikasi Masalah
  - 1.3 Rumusan Masalah
  - 1.4 Batasan Masalah
  - 1.5 Tujuan
  - 1.6 Manfaat
- BAB II LANDASAN TEORI
  - 2.1 Sistem Informasi
  - 2.2 Perpustakaan dan Peminjaman
  - 2.3 Aplikasi Web
  - 2.4 Laravel, PHP, MySQL, Blade dan Vite
  - 2.5 Pengujian Black-Box
- BAB III ANALISIS DAN PERANCANGAN
  - 3.1 Gambaran Umum
  - 3.2 Pengguna Sistem
  - 3.3 Kebutuhan Fungsional
  - 3.4 Kebutuhan Nonfungsional
  - 3.5 Alur Proses
  - 3.6 Rancangan Data
  - 3.7 Arsitektur Sistem
- BAB IV METODE PELAKSANAAN DAN PENGUJIAN
  - 4.1 Metode Pengembangan
  - 4.2 Perangkat
  - 4.3 Rencana Pengujian
  - 4.4 Jadwal
  - 4.5 Perkiraan Biaya
- BAB V PENUTUP
  - 5.1 Kesimpulan
  - 5.2 Saran
- DAFTAR PUSTAKA

---

# BAB I PENDAHULUAN

## 1.1 Latar Belakang

Perpustakaan merupakan sarana pendukung pembelajaran yang menyediakan bahan bacaan dan sumber informasi bagi peserta didik maupun warga sekolah. Agar layanan perpustakaan berjalan dengan baik, data koleksi, anggota, dan transaksi perlu dicatat secara teratur. Pengelolaan yang masih bergantung pada pencatatan terpisah dapat menyulitkan petugas ketika mencari informasi, memeriksa ketersediaan buku, mengetahui status peminjaman, dan menelusuri riwayat transaksi.

Pemanfaatan teknologi informasi memungkinkan data perpustakaan dikelola secara terpusat melalui aplikasi. Aplikasi berbasis web dapat digunakan melalui peramban pada perangkat yang terhubung ke jaringan, sehingga petugas tidak harus berpindah-pindah berkas untuk memperoleh informasi operasional.

Berdasarkan kebutuhan tersebut, dikembangkan **E-Perpus**, yaitu sistem informasi perpustakaan berbasis web menggunakan Laravel. Sistem ini menyediakan pengelolaan data buku, kategori, penulis, penerbit, rak, anggota, pengguna, peminjaman, pengembalian, booking, denda, pengaturan, dan riwayat. Anggota yang sudah terhubung dengan data keanggotaannya dapat melihat katalog dan mengajukan booking. Petugas memproses booking dan transaksi peminjaman maupun pengembalian.

Proposal ini menjelaskan rencana dan rancangan pengembangan E-Perpus sebagai proyek Uji Kompetensi Keahlian. Kondisi operasional dan nama perpustakaan pada naskah akhir perlu disesuaikan dengan hasil konfirmasi atau pengamatan di sekolah.

## 1.2 Identifikasi Masalah

Permasalahan yang menjadi dasar pengembangan sistem ini adalah:

1. Data buku, anggota, serta transaksi perpustakaan perlu dikelola secara terstruktur dan mudah ditelusuri.
2. Petugas membutuhkan cara untuk mengetahui jumlah stok buku yang tersedia dan status peminjaman.
3. Penghitungan keterlambatan serta denda perlu dilakukan secara konsisten berdasarkan pengaturan yang berlaku.
4. Anggota membutuhkan akses untuk melihat katalog dan mengajukan booking buku tanpa harus mengandalkan pencarian manual.
5. Admin dan petugas memerlukan hak akses sesuai tugas masing-masing serta catatan riwayat kegiatan yang relevan.

## 1.3 Rumusan Masalah

1. Bagaimana merancang dan membangun sistem informasi perpustakaan berbasis web yang mengelola data koleksi, anggota, dan transaksi?
2. Bagaimana menerapkan proses peminjaman, pengembalian, booking, dan pencatatan denda secara terintegrasi?
3. Bagaimana menguji fungsi-fungsi utama E-Perpus agar sesuai dengan kebutuhan pengguna?

## 1.4 Batasan Masalah

Untuk menjaga ruang lingkup proyek tetap terarah, batasan masalah ditetapkan sebagai berikut:

1. Sistem dikembangkan sebagai aplikasi web untuk penggunaan perpustakaan pada satu lingkungan organisasi.
2. Pengguna sistem terdiri atas admin, petugas, dan anggota.
3. Data yang dikelola mencakup data pengguna, anggota, buku, kategori, penulis, penerbit, rak, peminjaman, detail peminjaman, pengembalian, booking, denda, pengaturan, dan riwayat.
4. Anggota dapat melihat katalog dan mengajukan atau membatalkan booking sesuai ketentuan sistem. Persetujuan booking dilakukan oleh admin atau petugas.
5. Peminjaman dan pengembalian fisik tetap dilayani oleh petugas. Sistem mencatat transaksi, status, stok, kondisi buku saat kembali, dan denda sesuai pengaturan.
6. Pengaturan mencakup identitas perpustakaan, lama pinjam, serta tarif denda keterlambatan, kerusakan, dan kehilangan.
7. Sistem tidak membahas pengadaan koleksi, akuntansi lengkap, integrasi pembayaran daring, maupun aplikasi seluler native.
8. Sistem menyediakan riwayat transaksi dan aktivitas. Fitur ekspor laporan PDF/Excel tidak dijadikan ruang lingkup proposal ini kecuali ditambahkan dan disetujui pembimbing.

## 1.5 Tujuan

Tujuan pengembangan E-Perpus adalah:

1. Menghasilkan sistem informasi perpustakaan berbasis web untuk membantu pengelolaan data dan transaksi.
2. Menyediakan informasi katalog dan ketersediaan buku secara lebih mudah diakses.
3. Membantu petugas mencatat peminjaman, pengembalian, booking, dan denda secara terintegrasi.
4. Menerapkan pengaturan hak akses bagi admin, petugas, dan anggota.
5. Menguji fungsi sistem berdasarkan masukan dan keluaran yang diharapkan.

## 1.6 Manfaat

### Bagi perpustakaan

- Membantu pengelolaan koleksi dan data anggota dalam satu sistem.
- Memudahkan pencarian data transaksi serta pemantauan ketersediaan buku.
- Membantu konsistensi pencatatan pengembalian dan denda.

### Bagi petugas dan admin

- Memudahkan pencatatan transaksi dan pengelolaan data sesuai hak akses.
- Menyediakan informasi transaksi serta riwayat yang dapat ditelusuri.
- Mengurangi penghitungan manual untuk lama pinjam dan denda yang telah dikonfigurasi.

### Bagi anggota

- Memudahkan penelusuran katalog melalui portal anggota.
- Memungkinkan pengajuan booking dan pemantauan status booking melalui sistem.

### Bagi penulis

- Menambah pengalaman dalam menganalisis kebutuhan, merancang basis data, membangun aplikasi web, dan melakukan pengujian perangkat lunak.

---

# BAB II LANDASAN TEORI

## 2.1 Sistem Informasi

Sistem informasi merupakan susunan komponen yang saling berhubungan untuk mengumpulkan, mengolah, menyimpan, dan menyajikan informasi guna mendukung kegiatan organisasi. Dalam E-Perpus, komponen tersebut meliputi pengguna, prosedur layanan perpustakaan, aplikasi, dan basis data. Data buku dan anggota diolah menjadi informasi katalog, stok, transaksi peminjaman, pengembalian, booking, serta denda.

## 2.2 Perpustakaan dan Peminjaman

Perpustakaan mengelola koleksi bahan pustaka dan menyediakan layanan kepada anggotanya. Pengelolaan koleksi memerlukan data bibliografis seperti judul, ISBN, penulis, penerbit, tahun terbit, kategori, lokasi rak, dan jumlah eksemplar. Layanan sirkulasi meliputi peminjaman dan pengembalian.

Pada E-Perpus, transaksi peminjaman menghubungkan data anggota, petugas, buku, tanggal pinjam, tanggal batas kembali, serta detail buku yang dipinjam. Ketika pengembalian diproses, petugas mencatat kondisi setiap buku. Sistem menggunakan tanggal dan tarif yang dikonfigurasi untuk menghitung denda keterlambatan atau kondisi buku tertentu, lalu mencatat status pembayaran denda.

## 2.3 Aplikasi Web

Aplikasi web adalah perangkat lunak yang diakses menggunakan peramban melalui jaringan. Antarmuka aplikasi disajikan kepada pengguna, sementara pemrosesan dan penyimpanan data dilakukan oleh aplikasi pada sisi server dan basis data. E-Perpus menggunakan pola ini agar admin, petugas, dan anggota dapat mengakses fungsi sesuai perannya tanpa memasang aplikasi native pada setiap perangkat.

## 2.4 Laravel, PHP, MySQL, Blade dan Vite

**PHP** adalah bahasa pemrograman sisi server yang digunakan untuk menjalankan logika aplikasi web. **Laravel** merupakan kerangka kerja PHP yang menyediakan pola pengelolaan route, controller, validasi, autentikasi, akses basis data melalui Eloquent ORM, dan migrasi skema.

**MySQL** merupakan sistem manajemen basis data relasional yang dapat digunakan untuk menyimpan data aplikasi. Konfigurasi proyek menyediakan koneksi MySQL; konfigurasi bawaan proyek menggunakan SQLite untuk lingkungan lokal tertentu dan pengujian. Basis data yang dipakai saat implementasi akhir perlu disesuaikan dengan lingkungan sekolah.

**Blade** adalah mesin template Laravel yang digunakan untuk menyusun tampilan halaman dinamis. **Vite** digunakan untuk mengelola dan membangun aset antarmuka seperti CSS dan JavaScript. Pada proyek ini, aset CSS menggunakan Tailwind CSS melalui integrasi Vite.

## 2.5 Pengujian Black-Box

Pengujian black-box memeriksa fungsi perangkat lunak berdasarkan masukan dan keluaran tanpa menilai struktur kode internal. Penguji menyiapkan skenario, memberikan masukan, menjalankan fungsi, lalu membandingkan hasil aktual dengan hasil yang diharapkan.

Pada E-Perpus, pengujian direncanakan mencakup validasi login dan hak akses, pengelolaan data, pencarian katalog, booking, peminjaman, perubahan stok, pengembalian, perhitungan denda, pelunasan, dan pencatatan riwayat. Hasil setiap skenario dicatat sebagai berhasil atau perlu diperbaiki.

---

# BAB III ANALISIS DAN PERANCANGAN

## 3.1 Gambaran Umum

E-Perpus adalah aplikasi perpustakaan berbasis web dengan halaman publik, portal anggota, dan area pengelolaan untuk admin serta petugas. Pengguna masuk menggunakan akun. Hak akses membatasi area anggota dari area operasional; fitur administrasi tertentu, seperti pengelolaan akun dan pengaturan, hanya tersedia bagi admin.

Modul sistem meliputi:

1. Autentikasi dan pengelolaan akun.
2. Dashboard admin dan petugas.
3. Pengelolaan data buku dan data pendukungnya.
4. Pengelolaan anggota.
5. Katalog dan portal booking anggota.
6. Transaksi peminjaman dan pengembalian.
7. Pengelolaan denda dan pembayaran.
8. Pengaturan perpustakaan dan tarif.
9. Riwayat transaksi serta aktivitas.

Dalam proses booking, anggota dapat memiliki paling banyak tiga booking aktif. Setelah disetujui petugas, buku ditahan selama 24 jam. Peminjaman langsung dibatasi paling banyak dua judul buku berbeda dalam satu transaksi; pengambilan booking yang disetujui diproses melalui alur transaksi petugas. Ketentuan tersebut mengikuti implementasi proyek dan dapat diubah apabila kebijakan perpustakaan berbeda.

## 3.2 Pengguna Sistem

| Pengguna | Hak akses dan tanggung jawab |
|---|---|
| Admin | Mengakses dashboard dan modul operasional; mengelola akun pengguna dan pengaturan perpustakaan; serta mengelola data dan transaksi. |
| Petugas | Mengelola data perpustakaan yang tersedia pada area operasional, mengelola anggota, memproses booking, mencatat peminjaman/pengembalian, dan mengelola pembayaran denda. |
| Anggota | Mengakses portal anggota, melihat katalog, mengajukan atau membatalkan booking, dan melihat status booking. Anggota harus dihubungkan dengan data anggota oleh petugas sebelum menggunakan fungsi keanggotaan. |

## 3.3 Kebutuhan Fungsional

| Kode | Kebutuhan |
|---|---|
| F-01 | Sistem menyediakan proses login dan logout. |
| F-02 | Sistem membatasi akses menu berdasarkan peran admin, petugas, dan anggota. |
| F-03 | Admin dapat mengelola akun admin dan petugas serta pengaturan perpustakaan. |
| F-04 | Admin dan petugas dapat mengelola data buku, kategori, penulis, penerbit, rak, dan anggota sesuai hak akses aplikasi. |
| F-05 | Sistem menampilkan katalog dan mendukung pencarian buku berdasarkan judul, kode buku, atau penulis. |
| F-06 | Anggota yang telah terhubung dapat membuat dan membatalkan booking sesuai status dan batas booking aktif. |
| F-07 | Admin atau petugas dapat menyetujui atau menolak booking. Sistem menahan stok untuk booking yang disetujui, menetapkan masa berlaku 24 jam, dan menggabungkan semua booking disetujui milik anggota yang sama ke dalam satu keranjang peminjaman. |
| F-08 | Petugas dapat mencatat peminjaman anggota aktif sampai batas jumlah buku yang dikonfigurasi, memeriksa ketersediaan buku, dan menetapkan tanggal kembali berdasarkan lama pinjam yang dikonfigurasi. |
| F-09 | Sistem mengurangi stok tersedia saat peminjaman atau booking disetujui, sesuai alur transaksi. |
| F-10 | Petugas dapat mencatat pengembalian dan kondisi setiap buku. |
| F-11 | Sistem menghitung denda keterlambatan, kerusakan, atau kehilangan berdasarkan tarif yang dikonfigurasi; stok disesuaikan dengan kondisi buku. |
| F-12 | Petugas dapat mencatat pelunasan denda. |
| F-13 | Sistem mencatat data transaksi dan riwayat aktivitas yang relevan. |
| F-14 | Sistem menyediakan pencarian dan penapisan transaksi sesuai modul yang tersedia. |

## 3.4 Kebutuhan Nonfungsional

1. **Kemudahan penggunaan:** antarmuka menggunakan label dan formulir yang jelas serta konsisten.
2. **Kompatibilitas:** aplikasi dapat digunakan melalui peramban modern pada komputer atau perangkat bergerak dengan koneksi jaringan.
3. **Keamanan akses:** halaman yang memerlukan autentikasi hanya dapat diakses oleh pengguna yang telah masuk dan berhak.
4. **Validasi data:** masukan diperiksa sebelum disimpan, termasuk keberadaan data terkait dan nilai yang diperbolehkan.
5. **Konsistensi transaksi:** perubahan status booking, peminjaman, stok, pengembalian, dan denda dilakukan secara terkontrol agar data terkait tetap selaras.
6. **Pemeliharaan:** struktur Laravel dan migrasi basis data membantu pengembangan serta pemeliharaan aplikasi.
7. **Pencadangan:** basis data dan berkas unggahan perlu dicadangkan secara berkala oleh pengelola lingkungan implementasi.

## 3.5 Alur Proses

### A. Pengelolaan data awal

Admin atau petugas yang berwenang masuk ke sistem, kemudian melengkapi data kategori, penulis, penerbit, rak, dan buku. Data ini menjadi dasar katalog dan transaksi.

### B. Pendaftaran dan pengaitan anggota

Anggota membuat akun melalui fitur registrasi. Petugas mengelola data anggota perpustakaan dan menghubungkan akun dengan data anggota yang sesuai. Sebelum pengaitan dilakukan, akun anggota belum dapat menggunakan fungsi portal yang membutuhkan data anggota.

### C. Booking buku

Anggota masuk ke portal, mencari buku yang tersedia, lalu mengajukan booking. Sistem memeriksa status anggota, peminjaman aktif, jumlah booking aktif, booking ganda, dan ketersediaan buku. Petugas kemudian menyetujui atau menolak permohonan. Booking yang disetujui menahan stok selama 24 jam. Jika booking dibatalkan atau kedaluwarsa, status dan stok diperbarui menurut alur sistem.

### D. Peminjaman

Petugas memilih anggota aktif dan buku yang tersedia, atau memproses buku dari booking yang disetujui. Sistem memeriksa masukan, mencatat transaksi serta detail buku, menetapkan tanggal batas kembali berdasarkan pengaturan lama pinjam, dan mengurangi stok tersedia. Sistem kemudian menampilkan bukti transaksi yang dapat dicetak melalui peramban.

### E. Pengembalian dan denda

Petugas memilih transaksi yang masih aktif, mencatat tanggal pengembalian dan kondisi setiap buku. Sistem menghitung keterlambatan serta denda berdasarkan tarif aktif. Buku dengan kondisi baik dikembalikan ke stok tersedia; buku hilang mengurangi jumlah stok; kondisi rusak dicatat dan menghasilkan denda sesuai pengaturan. Denda yang belum dibayar dicatat sebagai kewajiban anggota, sedangkan pelunasan dicatat sebagai kas masuk dan riwayat.

## 3.6 Rancangan Data

Rancangan basis data relasional menggunakan tabel utama berikut:

| Tabel | Data yang disimpan |
|---|---|
| users | Akun pengguna, identitas login, kata sandi, peran, dan foto profil. |
| members | Kode, identitas, kontak, tanggal daftar, dan status anggota. |
| categories | Kode, nama, dan deskripsi kategori. |
| authors | Kode, nama, dan biografi penulis. |
| publishers | Kode, nama, dan informasi penerbit. |
| shelves | Kode, nama, lokasi, tipe, kapasitas, dan deskripsi rak/etalase. |
| books | Kode, judul, ISBN, relasi data pendukung, tahun terbit, jumlah, stok tersedia, cover, dan deskripsi. |
| bookings | Permohonan booking, anggota, buku, status, petugas pemroses, waktu persetujuan, dan masa berlaku. |
| loans | Kode transaksi, anggota, petugas, tanggal pinjam, batas kembali, tanggal dikembalikan, status, dan catatan. |
| loan_details | Buku dan jumlah dalam transaksi peminjaman serta kondisi saat dipinjam dan dikembalikan. |
| returns | Informasi transaksi pengembalian, keterlambatan, jumlah denda, petugas, dan catatan. |
| fines | Anggota, transaksi terkait, nominal, keterangan, status pembayaran, dan tanggal bayar. |
| settings | Pasangan kunci dan nilai untuk identitas perpustakaan serta pengaturan pinjam dan denda. |
| histories | Catatan transaksi dan aktivitas yang relevan, termasuk kas masuk dan perubahan tarif. |

Hubungan data utama:

- Satu kategori, penulis, penerbit, atau rak dapat berhubungan dengan banyak buku.
- Satu anggota dapat memiliki banyak booking, peminjaman, dan denda.
- Satu peminjaman memiliki satu atau lebih detail peminjaman; setiap detail menunjuk ke satu buku.
- Booking menghubungkan satu anggota dengan satu buku dan diproses oleh pengguna petugas/admin.
- Pengembalian terkait dengan peminjaman, sedangkan denda dapat terkait dengan anggota dan peminjaman.
- Pengguna admin/petugas tercatat pada transaksi yang diproses.

## 3.7 Arsitektur Sistem

E-Perpus menggunakan arsitektur aplikasi web dengan alur umum sebagai berikut:

**Peramban pengguna → Route Laravel → Middleware autentikasi/peran → Controller → Model/Eloquent → Basis data**

Controller memproses permintaan, menjalankan validasi dan aturan bisnis, lalu mengakses data melalui model. Blade menyusun halaman yang dikirim kepada peramban. Aset CSS dan JavaScript dikelola melalui Vite. Basis data menyimpan data transaksi dan master. Proyek dapat menggunakan koneksi MySQL untuk penerapan dan SQLite untuk kebutuhan lokal atau pengujian sesuai konfigurasi lingkungan.

---

# BAB IV METODE PELAKSANAAN DAN PENGUJIAN

## 4.1 Metode Pengembangan

Metode yang diusulkan adalah **Prototype**, karena kebutuhan tampilan dan alur kerja perpustakaan dapat diklarifikasi melalui contoh aplikasi yang dapat dicoba. Tahapannya:

1. **Pengumpulan kebutuhan:** mengidentifikasi kebutuhan admin, petugas, dan anggota melalui pengamatan atau wawancara yang disetujui pihak sekolah.
2. **Perancangan awal:** menyusun alur proses, rancangan basis data, hak akses, dan sketsa halaman.
3. **Pembuatan prototype:** membangun fungsi inti aplikasi secara bertahap, dimulai dari autentikasi, data master, katalog, dan transaksi.
4. **Evaluasi pengguna:** meminta masukan pembimbing atau calon pengguna terhadap alur dan tampilan.
5. **Penyempurnaan:** memperbaiki prototype berdasarkan masukan dan hasil pengujian.
6. **Pengujian dan dokumentasi:** menjalankan skenario black-box, memperbaiki kesalahan yang ditemukan, dan menyusun dokumentasi penggunaan.

Tahapan tersebut merupakan rencana pelaksanaan. Urutan rinci dan durasi dapat disesuaikan dengan kalender UKK serta arahan pembimbing.

## 4.2 Perangkat

### Perangkat keras

| Perangkat | Spesifikasi minimum/rencana |
|---|---|
| Laptop/komputer pengembangan | Prosesor setara Intel Core i3, RAM 8 GB, penyimpanan yang cukup untuk aplikasi dan basis data |
| Perangkat pengujian | Laptop atau telepon pintar dengan peramban modern |
| Jaringan | Internet atau jaringan lokal untuk instalasi dan akses aplikasi |

### Perangkat lunak

| Perangkat lunak | Kegunaan |
|---|---|
| Windows | Sistem operasi lingkungan pengembangan |
| PHP 8.2 atau lebih baru yang kompatibel | Menjalankan aplikasi Laravel |
| Laravel 12 | Kerangka kerja aplikasi web |
| Composer | Mengelola dependensi PHP |
| Node.js dan npm | Mengelola dependensi dan membangun aset melalui Vite |
| MySQL | Basis data rencana penerapan; SQLite dapat digunakan untuk pengembangan/pengujian |
| Visual Studio Code | Editor kode |
| Peramban modern | Mengakses dan menguji aplikasi |
| Git | Mengelola perubahan kode sumber |

## 4.3 Rencana Pengujian

Pengujian dilakukan dengan metode black-box. Data uji dan akun yang digunakan harus berupa data contoh atau data yang telah mendapat izin. Hasil pengujian dicatat pada lembar pengujian.

| No. | Modul | Skenario uji | Hasil yang diharapkan |
|---:|---|---|---|
| 1 | Autentikasi | Login dengan akun valid dan tidak valid | Akun valid masuk sesuai peran; akun tidak valid ditolak dengan pesan yang sesuai. |
| 2 | Hak akses | Anggota mencoba membuka halaman petugas/admin | Sistem membatasi akses ke halaman yang tidak berhak diakses. |
| 3 | Data buku | Menambah dan memperbarui buku dengan data valid | Data tersimpan dan relasi kategori, penulis, penerbit, dan rak tampil benar. |
| 4 | Katalog | Mencari buku berdasarkan judul, kode, atau penulis | Sistem menampilkan hasil yang sesuai dengan kata pencarian. |
| 5 | Booking | Anggota mengajukan booking buku tersedia/tidak tersedia | Permohonan valid tercatat; permohonan yang melanggar ketentuan ditolak. |
| 6 | Persetujuan booking | Petugas menyetujui atau menolak booking | Status booking berubah; persetujuan menahan stok dan menetapkan masa berlaku. |
| 7 | Peminjaman | Petugas meminjamkan buku tersedia dan tidak tersedia | Transaksi valid tercatat; stok tersedia berkurang; transaksi tidak valid ditolak. |
| 8 | Pengembalian | Petugas mengembalikan buku tepat waktu atau terlambat | Status transaksi berubah; keterlambatan dan denda dihitung sesuai tarif. |
| 9 | Kondisi buku | Petugas mencatat buku baik, rusak, atau hilang | Kondisi dan denda tercatat; stok berubah sesuai kondisi buku. |
| 10 | Pelunasan denda | Petugas melunasi denda belum dibayar | Status berubah menjadi lunas dan kas masuk/riwayat dicatat satu kali. |
| 11 | Pengaturan | Admin mengubah lama pinjam dan tarif denda | Nilai tersimpan dan digunakan pada transaksi berikutnya. |

Kriteria keberhasilan pengujian adalah setiap fungsi pada skenario menghasilkan keluaran sesuai hasil yang diharapkan, tidak menyebabkan perubahan data yang bertentangan, dan menampilkan validasi yang dapat dipahami. Temuan kegagalan dicatat, diperbaiki, lalu diuji ulang.

## 4.4 Jadwal

Rencana berikut merupakan perkiraan delapan minggu dan perlu diselaraskan dengan kalender sekolah.

| Kegiatan | M1 | M2 | M3 | M4 | M5 | M6 | M7 | M8 |
|---|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|
| Identifikasi kebutuhan | X | X | | | | | | |
| Analisis dan rancangan | | X | X | | | | | |
| Pembuatan prototype dan data master | | | X | X | | | | |
| Pembuatan transaksi dan portal anggota | | | | X | X | | | |
| Evaluasi dan penyempurnaan | | | | | X | X | | |
| Pengujian black-box | | | | | | X | X | |
| Dokumentasi dan persiapan UKK | | | | | | | X | X |

## 4.5 Perkiraan Biaya

Perkiraan ini bersifat sementara, mengasumsikan laptop dan perangkat lunak pengembangan telah tersedia, serta menggunakan perangkat lunak bebas dan sumber terbuka. Nominal perlu dikonfirmasi dengan harga lokal dan kebijakan sekolah.

| No. | Kebutuhan | Perkiraan biaya |
|---:|---|---:|
| 1 | Internet selama pengerjaan | Rp150.000 |
| 2 | Listrik dan operasional perangkat | Rp100.000 |
| 3 | Pencetakan dan penjilidan proposal/laporan | Rp150.000 |
| | **Total perkiraan** | **Rp400.000** |

Biaya di atas tidak mencakup pembelian laptop, server, atau domain/hosting berbayar. Jika sekolah meminta aplikasi dipublikasikan secara daring, biaya infrastruktur perlu dihitung kembali.

---

# BAB V PENUTUP

## 5.1 Kesimpulan

E-Perpus direncanakan sebagai sistem informasi perpustakaan berbasis web yang mengintegrasikan pengelolaan data koleksi, anggota, booking, peminjaman, pengembalian, denda, pengaturan, dan riwayat. Sistem menyediakan fungsi berbeda untuk admin, petugas, dan anggota, sehingga proses katalog dan transaksi dapat dikelola melalui aplikasi yang sama.

Melalui pelaksanaan proyek ini, diharapkan kebutuhan pengelolaan perpustakaan dapat diterjemahkan menjadi fungsi yang terukur, dirancang secara terstruktur, dan diuji menggunakan skenario black-box. Proposal ini menjadi dasar pelaksanaan UKK dan masih dapat disesuaikan berdasarkan hasil konfirmasi kebutuhan serta arahan pembimbing.

## 5.2 Saran

1. Sebelum penerapan, pihak perpustakaan perlu memeriksa kesesuaian alur booking, batas peminjaman, masa pinjam, kondisi buku, dan tarif denda dengan kebijakan yang berlaku.
2. Admin perlu menjaga kerahasiaan akun, mengatur hak akses dengan tepat, serta melakukan pencadangan basis data dan berkas unggahan.
3. Pengujian sebaiknya dilakukan bersama calon pengguna dengan data contoh sebelum sistem digunakan untuk transaksi sebenarnya.
4. Pengembangan berikutnya dapat mempertimbangkan laporan yang dapat diekspor, pencetakan kartu anggota, notifikasi, dan penguatan pencadangan sesuai kebutuhan sekolah.

---

# DAFTAR PUSTAKA

1. Laravel. *Laravel 12.x Documentation*. https://laravel.com/docs/12.x
2. MySQL. *MySQL 8.4 Reference Manual*. https://dev.mysql.com/doc/refman/8.4/en/
3. PHP Documentation Group. *PHP Manual*. https://www.php.net/manual/en/
4. Pressman, Roger S. dan Bruce R. Maxim. *Software Engineering: A Practitioner's Approach*. Edisi ke-9. McGraw-Hill Education, 2020.

---

## CATATAN PENYESUAIAN SEBELUM DIKUMPULKAN

- Ganti semua teks dalam tanda kurung siku, termasuk identitas peserta, sekolah, pembimbing, kota, dan tanggal.
- Konfirmasikan latar belakang proses perpustakaan melalui pengamatan/wawancara; sesuaikan pernyataan masalah dengan fakta sekolah.
- Sesuaikan jadwal, biaya, perangkat, dan kebijakan denda dengan kondisi sebenarnya.
- Verifikasi nama fitur dan hak akses berdasarkan versi aplikasi yang akan didemonstrasikan.
- Setelah dipindahkan ke Word, terapkan gaya Heading pada judul bab/subbab, buat daftar isi otomatis, tambahkan nomor halaman, dan perbarui daftar isi sebelum dicetak.
