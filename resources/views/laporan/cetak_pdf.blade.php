<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kegiatan - {{ $bulanFormat }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header img {
            width: 80px;
            float: left;
        }
        .header-content {
            margin-left: 90px;
            text-align: center;
        }
        .title {
            font-size: 14pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        .subtitle {
            font-size: 12pt;
            margin: 5px 0 0 0;
        }
        .address {
            font-size: 9pt;
            margin-top: 5px;
        }
        .report-title {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 20px;
            text-decoration: underline;
        }
        .filter-info {
            margin-bottom: 15px;
            font-size: 10pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10pt;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .signature-area {
            width: 100%;
            margin-top: 40px;
        }
        .signature-box {
            float: right;
            width: 300px;
            text-align: center;
        }
        .signature-name {
            margin-top: 80px;
            font-weight: bold;
            text-decoration: underline;
        }
        .status-aktif { color: #000; }
        .status-selesai { color: #000; font-weight: bold; }
        .status-batal { color: #666; font-style: italic; }
        
        .page-break {
            page-break-after: always;
        }
        .footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: 30px;
            font-size: 8pt;
            text-align: right;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-content">
            <h1 class="title">SISTEM INFORMASI PEMANTAUAN AGENDA (SIMADA)</h1>
            <p class="subtitle">KECAMATAN / INSTANSI</p>
            <p class="address">Jl. Contoh Alamat No. 123, Kota, Provinsi. Telp: (021) 1234567<br>Email: contact@instansi.go.id | Website: www.instansi.go.id</p>
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="report-title">
        LAPORAN REKAPITULASI KEGIATAN
    </div>

    <div class="filter-info">
        <table style="width: 50%; border: none; margin-bottom: 0;">
            <tr>
                <td style="border: none; padding: 2px; width: 120px; font-weight: bold;">Periode</td>
                <td style="border: none; padding: 2px;">: {{ $bulanFormat }}</td>
            </tr>
            <tr>
                <td style="border: none; padding: 2px; font-weight: bold;">Status Filter</td>
                <td style="border: none; padding: 2px;">: {{ ucfirst($status) }}</td>
            </tr>
            <tr>
                <td style="border: none; padding: 2px; font-weight: bold;">Total Kegiatan</td>
                <td style="border: none; padding: 2px;">: {{ $kegiatan->count() }} Kegiatan</td>
            </tr>
            <tr>
                <td style="border: none; padding: 2px; font-weight: bold;">Tanggal Cetak</td>
                <td style="border: none; padding: 2px;">: {{ date('d F Y H:i:s') }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal & Waktu</th>
                <th width="30%">Judul Kegiatan</th>
                <th width="20%">Tempat</th>
                <th width="10%">Kategori</th>
                <th width="10%">Peserta</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kegiatan as $index => $keg)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        {{ $keg->tanggal->format('d/m/Y') }}<br>
                        <small>{{ \Carbon\Carbon::parse($keg->jam_mulai)->format('H:i') }} - {{ $keg->jam_selesai ? \Carbon\Carbon::parse($keg->jam_selesai)->format('H:i') : 'Selesai' }}</small>
                    </td>
                    <td>{{ $keg->judul }}</td>
                    <td>{{ $keg->tempat }}</td>
                    <td class="text-center">{{ $keg->kategori->nama ?? 'Umum' }}</td>
                    <td class="text-center">{{ $keg->peserta_count }} Org</td>
                    <td class="text-center status-{{ $keg->status }}">
                        {{ strtoupper($keg->status) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px;">
                        Tidak ada data kegiatan yang sesuai dengan filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-area">
        <div class="signature-box">
            <p>Kota, {{ date('d F Y') }}</p>
            <p style="margin-bottom: 70px;">Mengetahui,<br>Kepala Instansi / Camat</p>
            <p class="signature-name">(........................................................)</p>
            <p style="margin-top: 5px;">NIP. ........................................</p>
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="footer">
        Dicetak dari Sistem Informasi Pemantauan Agenda (SIMADA) - {{ date('Y') }}
    </div>

</body>
</html>
