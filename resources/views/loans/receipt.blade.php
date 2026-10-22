<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Peminjaman {{ $loan->kode_pinjam }} - E-Perpus</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 32px 16px;
            background: #f4f5fa;
            color: #1f2937;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }
        .receipt {
            width: 100%;
            max-width: 720px;
            margin: 0 auto;
            padding: 36px;
            border: 1px solid #eef0f6;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 1px 2px rgba(16, 24, 40, .04), 0 8px 24px rgba(16, 24, 40, .06);
        }
        .receipt-header { padding-bottom: 22px; border-bottom: 1px solid #e5e7eb; text-align: center; }
        .receipt-header h1 { margin: 0; font-size: 22px; letter-spacing: .08em; }
        .receipt-header p { margin: 7px 0 0; color: #6b7280; }
        .receipt-code { margin-top: 20px; text-align: center; }
        .receipt-code span { display: block; margin-bottom: 5px; color: #6b7280; font-size: 11px; letter-spacing: .08em; text-transform: uppercase; }
        .receipt-code strong { color: #2563eb; font-size: 18px; }
        .details { display: grid; grid-template-columns: 1fr 1fr; gap: 16px 24px; margin: 24px 0; }
        .detail-label { display: block; margin-bottom: 5px; color: #6b7280; font-size: 11px; letter-spacing: .04em; text-transform: uppercase; }
        .detail-value { font-weight: 600; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 11px 8px; border-bottom: 1px solid #e5e7eb; text-align: left; }
        th { background: #f8fafc; color: #6b7280; font-size: 11px; letter-spacing: .05em; text-transform: uppercase; }
        .text-center { text-align: center; }
        .note { margin-top: 20px; padding: 12px 14px; border-radius: 8px; background: #f8fafc; color: #4b5563; line-height: 1.5; }
        .receipt-footer { margin-top: 30px; color: #6b7280; font-size: 12px; text-align: center; line-height: 1.6; }
        .actions { display: flex; justify-content: center; gap: 10px; margin: 20px auto 0; }
        .button { display: inline-flex; min-height: 40px; align-items: center; justify-content: center; padding: 0 16px; border: 1px solid #d1d5db; border-radius: 9px; background: #fff; color: #374151; font-size: 14px; font-weight: 600; text-decoration: none; cursor: pointer; }
        .button-primary { border-color: #2563eb; background: #2563eb; color: #fff; }
        @media (max-width: 540px) {
            body { padding: 16px 10px; }
            .receipt { padding: 24px 18px; }
            .details { grid-template-columns: 1fr 1fr; gap: 14px; }
            th, td { padding: 9px 5px; font-size: 12px; }
        }
        @media print {
            @page { margin: 14mm; }
            body { padding: 0; background: #fff; }
            .receipt { max-width: none; padding: 0; border: 0; border-radius: 0; box-shadow: none; }
            .actions { display: none !important; }
            tr, .details, .note { break-inside: avoid; }
        }
    </style>
</head>
<body>
    <main class="receipt">
        <header class="receipt-header">
            <h1>E-PERPUS</h1>
            <p>Struk Peminjaman Buku</p>
        </header>

        <div class="receipt-code">
            <span>Kode Peminjaman</span>
            <strong>{{ $loan->kode_pinjam }}</strong>
        </div>

        <section class="details" aria-label="Informasi peminjaman">
            <div>
                <span class="detail-label">Anggota</span>
                <span class="detail-value">{{ $loan->member->nama ?? 'N/A' }}</span>
            </div>
            <div>
                <span class="detail-label">Kode Anggota</span>
                <span class="detail-value">{{ $loan->member->kode_member ?? 'N/A' }}</span>
            </div>
            <div>
                <span class="detail-label">Tanggal Pinjam</span>
                <span class="detail-value">{{ $loan->tanggal_pinjam->format('d M Y') }}</span>
            </div>
            <div>
                <span class="detail-label">Batas Kembali</span>
                <span class="detail-value">{{ $loan->tanggal_kembali->format('d M Y') }}</span>
            </div>
            <div>
                <span class="detail-label">Petugas</span>
                <span class="detail-value">{{ $loan->user->name ?? 'N/A' }}</span>
            </div>
            <div>
                <span class="detail-label">Status</span>
                <span class="detail-value">{{ ucfirst($loan->status) }}</span>
            </div>
        </section>

        <table>
            <thead>
                <tr>
                    <th>Buku</th>
                    <th class="text-center">Jumlah</th>
                    <th>Kondisi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($loan->loanDetails as $detail)
                    <tr>
                        <td>{{ $detail->book->judul ?? 'Buku tidak tersedia' }}<br><small>{{ $detail->book->kode_buku ?? '' }}</small></td>
                        <td class="text-center">{{ $detail->jumlah }}</td>
                        <td>{{ ucfirst($detail->kondisi) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($loan->catatan)
            <div class="note"><strong>Catatan:</strong> {{ $loan->catatan }}</div>
        @endif

        <footer class="receipt-footer">
            Mohon kembalikan buku sebelum atau pada batas tanggal kembali.<br>
            Terima kasih telah menggunakan layanan E-Perpus.
        </footer>
    </main>

    <div class="actions">
        <a href="{{ route('loans.index') }}" class="button">Kembali ke Peminjaman</a>
        <button type="button" class="button button-primary" onclick="window.print()">Cetak Struk</button>
    </div>

    <script>
        window.addEventListener('load', function () {
            window.setTimeout(function () {
                window.print();
            }, 300);
        });
    </script>
</body>
</html>
