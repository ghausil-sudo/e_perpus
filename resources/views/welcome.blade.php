@php
    $libraryName = $settings['nama_perpustakaan'] ?? 'E-Perpus';
    $loanPeriod = (int) ($settings['lama_pinjam'] ?? 7);
    $fineRates = [
        ['label' => 'Keterlambatan', 'amount' => (float) ($settings['denda_per_hari'] ?? 0), 'unit' => 'per buku / hari'],
        ['label' => 'Kerusakan ringan', 'amount' => (float) ($settings['denda_rusak_ringan'] ?? 0), 'unit' => 'per buku'],
        ['label' => 'Kerusakan berat', 'amount' => (float) ($settings['denda_rusak_berat'] ?? 0), 'unit' => 'per buku'],
        ['label' => 'Buku hilang', 'amount' => (float) ($settings['denda_hilang'] ?? 0), 'unit' => 'per buku'],
    ];
    $logo = $settings['logo'] ?? null;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Panduan penggunaan dan peraturan {{ $libraryName }}.">
    <title>{{ $libraryName }} - Panduan dan Peraturan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: light;
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --ink: #1f2937;
            --muted: #64748b;
            --border: #e8edf5;
            --surface: #fff;
            --background: #f5f7fc;
            --radius: 18px;
            --shadow: 0 2px 5px rgba(16, 24, 40, .04), 0 14px 34px rgba(16, 24, 40, .07);
        }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { margin: 0; color: var(--ink); background: var(--background); font-family: Inter, Arial, sans-serif; -webkit-font-smoothing: antialiased; }
        a { color: inherit; }
        .container { width: min(1120px, calc(100% - 40px)); margin: 0 auto; }
        .topbar { padding: 22px 0; }
        .nav { display: flex; align-items: center; justify-content: space-between; gap: 20px; }
        .brand { display: inline-flex; align-items: center; gap: 12px; text-decoration: none; }
        .brand-mark { display: grid; width: 44px; height: 44px; place-items: center; overflow: hidden; border: 1px solid var(--border); border-radius: 14px; background: #fff; color: var(--primary); font-size: 19px; font-weight: 800; box-shadow: 0 3px 10px rgba(37, 99, 235, .1); }
        .brand-mark img { width: 100%; height: 100%; object-fit: contain; padding: 5px; }
        .brand-name { font-size: 16px; font-weight: 750; letter-spacing: -.03em; }
        .brand-caption { margin-top: 3px; color: var(--muted); font-size: 11px; }
        .nav-actions { display: flex; align-items: center; gap: 10px; }
        .button { display: inline-flex; min-height: 42px; align-items: center; justify-content: center; gap: 8px; padding: 0 16px; border: 1px solid var(--border); border-radius: 11px; background: #fff; color: #344054; font-size: 13px; font-weight: 650; text-decoration: none; transition: transform .16s ease, background .16s ease, box-shadow .16s ease; }
        .button:hover { transform: translateY(-1px); background: #f9fbff; box-shadow: 0 5px 14px rgba(16, 24, 40, .07); }
        .button-primary { border-color: var(--primary); background: var(--primary); color: #fff; box-shadow: 0 4px 12px rgba(37, 99, 235, .19); }
        .button-primary:hover { border-color: var(--primary-dark); background: var(--primary-dark); }
        .button-login { min-height: 46px; padding: 0 20px; border-color: #1d4ed8; background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: #fff; font-size: 14px; box-shadow: 0 7px 18px rgba(37, 99, 235, .28); }
        .button-login:hover { border-color: #1e40af; background: linear-gradient(135deg, #2563eb, #1e40af); box-shadow: 0 9px 22px rgba(37, 99, 235, .34); }
        .hero { position: relative; overflow: hidden; margin: 18px 0 26px; padding: clamp(30px, 6vw, 66px); border: 1px solid #e6edfb; border-radius: 26px; background: linear-gradient(120deg, #fff 22%, #f4f8ff 100%); box-shadow: var(--shadow); }
        .hero::after { position: absolute; top: -150px; right: -110px; width: 390px; height: 390px; border-radius: 50%; background: radial-gradient(circle, rgba(191, 219, 254, .55), rgba(219, 234, 254, 0) 70%); content: ""; pointer-events: none; }
        .hero-content { position: relative; z-index: 1; max-width: 690px; }
        .eyebrow { display: inline-flex; align-items: center; gap: 8px; padding: 7px 11px; border: 1px solid #dbeafe; border-radius: 99px; background: #eff6ff; color: #1d4ed8; font-size: 11px; font-weight: 700; letter-spacing: .09em; text-transform: uppercase; }
        .eyebrow-dot { width: 7px; height: 7px; border-radius: 50%; background: #3b82f6; }
        h1 { max-width: 650px; margin: 20px 0 14px; font-size: clamp(34px, 5.7vw, 58px); line-height: 1.08; letter-spacing: -.055em; }
        .hero p { max-width: 610px; margin: 0; color: var(--muted); font-size: 16px; line-height: 1.75; }
        .hero-actions { display: flex; flex-wrap: wrap; gap: 11px; margin-top: 27px; }
        .section { margin-top: 34px; }
        .section-heading { display: flex; align-items: end; justify-content: space-between; gap: 20px; margin: 0 2px 15px; }
        .section-heading h2 { margin: 0; font-size: 21px; letter-spacing: -.035em; }
        .section-heading p { margin: 6px 0 0; color: var(--muted); font-size: 13px; line-height: 1.6; }
        .steps { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; }
        .card { min-width: 0; padding: 21px; border: 1px solid var(--border); border-radius: var(--radius); background: var(--surface); box-shadow: var(--shadow); }
        .step-number { display: grid; width: 38px; height: 38px; place-items: center; border: 1px solid #dbeafe; border-radius: 12px; background: #eff6ff; color: var(--primary); font-size: 14px; font-weight: 800; }
        .step h3 { margin: 17px 0 8px; font-size: 15px; letter-spacing: -.02em; }
        .step p { margin: 0; color: var(--muted); font-size: 12px; line-height: 1.7; }
        .content-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .rules { display: grid; gap: 14px; margin: 19px 0 0; padding: 0; list-style: none; }
        .rules li { display: flex; align-items: flex-start; gap: 11px; color: #475569; font-size: 13px; line-height: 1.65; }
        .check { display: grid; width: 21px; height: 21px; flex: 0 0 21px; place-items: center; margin-top: 1px; border-radius: 7px; background: #ecfdf3; color: #15803d; font-size: 13px; font-weight: 800; }
        .fine-list { display: grid; gap: 0; margin-top: 16px; }
        .fine-row { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 13px 0; border-bottom: 1px solid #eef2f7; }
        .fine-row:last-child { border-bottom: 0; }
        .fine-label { font-size: 13px; font-weight: 650; }
        .fine-unit { margin-top: 4px; color: var(--muted); font-size: 11px; }
        .fine-amount { flex: 0 0 auto; color: #b42318; font-size: 13px; font-weight: 750; font-variant-numeric: tabular-nums; }
        .note { margin-top: 14px; padding: 12px 13px; border: 1px solid #dbeafe; border-radius: 11px; background: #f5f9ff; color: #475569; font-size: 11px; line-height: 1.65; }
        .footer { display: flex; flex-wrap: wrap; justify-content: space-between; gap: 12px; margin-top: 32px; padding: 21px 2px 28px; border-top: 1px solid #e5eaf2; color: var(--muted); font-size: 11px; }
        .contact a { color: var(--primary); text-decoration: none; }
        :focus-visible { outline: 3px solid rgba(37, 99, 235, .45); outline-offset: 3px; }
        @media (max-width: 900px) { .steps { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (max-width: 640px) {
            .container { width: min(100% - 28px, 540px); }
            .topbar { padding: 15px 0; }
            .brand-caption { display: none; }
            .nav-actions { gap: 7px; }
            .button { min-height: 40px; padding: 0 12px; font-size: 12px; }
            .hero { margin-top: 10px; padding: 30px 23px; border-radius: 20px; }
            .hero p { font-size: 14px; }
            .section { margin-top: 28px; }
            .section-heading { align-items: flex-start; }
            .section-heading h2 { font-size: 19px; }
            .content-grid { grid-template-columns: 1fr; }
            .card { padding: 19px; }
        }
        @media (max-width: 420px) {
            .brand-name { font-size: 14px; }
            .brand-mark { width: 39px; height: 39px; }
            .steps { grid-template-columns: 1fr; }
            .nav-actions .button-secondary { display: none; }
            h1 { font-size: 36px; }
        }
        @media (prefers-reduced-motion: reduce) { html { scroll-behavior: auto; } *, *::before, *::after { transition-duration: .01ms !important; } }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="container nav">
            <a href="{{ url('/') }}" class="brand" aria-label="{{ $libraryName }} - Beranda">
                <span class="brand-mark">
                    @if($logo)
                        <img src="{{ asset('storage/' . $logo) }}" alt="">
                    @else
                        E
                    @endif
                </span>
                <span>
                    <span class="brand-name">{{ $libraryName }}</span>
                    <span class="brand-caption" style="display:block">Sistem Manajemen Perpustakaan Digital</span>
                </span>
            </a>
            <nav class="nav-actions" aria-label="Navigasi utama">
                <a class="button button-secondary" href="#panduan">Panduan</a>
                <a class="button button-secondary" href="#peraturan">Peraturan</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <section class="hero">
            <div class="hero-content">
                <span class="eyebrow"><span class="eyebrow-dot"></span> Selamat datang di perpustakaan</span>
                <h1>Temukan bacaan.<br>Jelajahi pengetahuan.</h1>
                <p>Ikuti panduan peminjaman dan pengembalian buku, serta pahami peraturan perpustakaan agar pengalaman membaca tetap nyaman untuk semua.</p>
                <div class="hero-actions">
                    <a class="button button-primary" href="#panduan">Lihat Panduan <span aria-hidden="true">↓</span></a>
                    <a class="button button-secondary" href="#peraturan">Baca Peraturan</a>
                    <a class="button button-login" href="{{ route('login') }}">Login</a>
                    <a class="button button-secondary" href="{{ route('register') }}">Daftar Akun Anggota</a>
                </div>
            </div>
        </section>

        <section id="panduan" class="section">
            <div class="section-heading">
                <div>
                    <h2>Panduan Penggunaan</h2>
                    <p>Langkah sederhana untuk meminjam dan mengembalikan buku.</p>
                </div>
            </div>
            <div class="steps">
                <article class="card step">
                    <span class="step-number">01</span>
                    <h3>Pilih buku</h3>
                    <p>Temukan buku yang ingin dibaca melalui katalog perpustakaan atau tanyakan ketersediaannya kepada petugas.</p>
                </article>
                <article class="card step">
                    <span class="step-number">02</span>
                    <h3>Ajukan peminjaman</h3>
                    <p>Sampaikan identitas anggota dan buku pilihan kepada petugas. Beberapa buku dapat dicatat dalam satu transaksi.</p>
                </article>
                <article class="card step">
                    <span class="step-number">03</span>
                    <h3>Simpan bukti pinjam</h3>
                    <p>Periksa struk peminjaman dan catat tanggal batas pengembalian untuk setiap buku yang dipinjam.</p>
                </article>
                <article class="card step">
                    <span class="step-number">04</span>
                    <h3>Kembalikan ke petugas</h3>
                    <p>Kembalikan buku sebelum batas waktu. Petugas akan memeriksa kondisi buku dan mencatat pengembaliannya.</p>
                </article>
            </div>
        </section>

        <section id="peraturan" class="section">
            <div class="section-heading">
                <div>
                    <h2>Peraturan Perpustakaan</h2>
                    <p>Ketentuan dibuat untuk menjaga koleksi dan kenyamanan bersama.</p>
                </div>
            </div>
            <div class="content-grid">
                <article class="card">
                    <h3 style="margin:0;font-size:16px">Ketentuan Peminjaman</h3>
                    <ul class="rules">
                        <li><span class="check" aria-hidden="true">✓</span><span>Peminjaman dilakukan melalui petugas dengan menggunakan data anggota yang terdaftar.</span></li>
                        <li><span class="check" aria-hidden="true">✓</span><span>Satu anggota hanya dapat memiliki satu transaksi peminjaman aktif. Satu transaksi dapat berisi beberapa buku.</span></li>
                        <li><span class="check" aria-hidden="true">✓</span><span>Lama peminjaman adalah {{ $loanPeriod }} hari. Batas tanggal pada struk menjadi acuan pengembalian.</span></li>
                        <li><span class="check" aria-hidden="true">✓</span><span>Buku harus dikembalikan kepada petugas agar kondisi dan pengembaliannya dapat dicatat.</span></li>
                        <li><span class="check" aria-hidden="true">✓</span><span>Jaga buku tetap bersih, tidak dicoret, dilipat, atau dipindahtangankan kepada orang lain.</span></li>
                    </ul>
                </article>
                <article class="card">
                    <h3 style="margin:0;font-size:16px">Denda Keterlambatan dan Kondisi Buku</h3>
                    <div class="fine-list">
                        @foreach($fineRates as $rate)
                            <div class="fine-row">
                                <div>
                                    <div class="fine-label">{{ $rate['label'] }}</div>
                                    <div class="fine-unit">{{ $rate['unit'] }}</div>
                                </div>
                                <div class="fine-amount">Rp {{ number_format($rate['amount'], 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                    </div>
                    <div class="note">Denda dihitung berdasarkan tarif yang berlaku dan kondisi akhir buku saat dikembalikan. Buku rusak tidak ditambahkan ke stok tersedia, sedangkan buku hilang mengurangi jumlah stok.</div>
                </article>
            </div>
        </section>
    </main>

    <footer class="container footer">
        <span>© {{ date('Y') }} {{ $libraryName }}. Gunakan koleksi dengan bijak.</span>
        <span class="contact">
            @if(!empty($settings['telepon']))
                Kontak: <a href="tel:{{ $settings['telepon'] }}">{{ $settings['telepon'] }}</a>
            @elseif(!empty($settings['email']))
                Kontak: <a href="mailto:{{ $settings['email'] }}">{{ $settings['email'] }}</a>
            @else
                Sistem Manajemen Perpustakaan Digital
            @endif
        </span>
    </footer>
</body>
</html>
