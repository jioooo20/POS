<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            margin: 30px;
            font-size: 12pt;
            color: #333;
        }

        h1 {
            font-size: 16pt;
            margin-top: 20px;
            margin-bottom: 10px;
            text-align: center;
            border-bottom: 2px solid #444;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        td,
        th {
            padding: 8px 10px;
            border: 1px solid #ccc;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header-table img {
            height: 70px;
        }

        .header-text {
            text-align: center;
        }

        .header-text span {
            display: block;
        }

        .font-bold {
            font-weight: bold;
        }

        .small-text {
            font-size: 10pt;
        }
    </style>
</head>

<body>
    <table class="header-table">
        <tr>
            <td width="15%" class="text-center">
                <img src="{{ public_path('polinema-bw.png') }}" alt="Logo">
            </td>
            <td width="85%" class="header-text">
                <span class="font-bold">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</span>
                <span class="font-bold" style="font-size: 14pt;">POLITEKNIK NEGERI MALANG</span>
                <span class="small-text">Jl. Soekarno-Hatta No. 9 Malang 65141</span>
                <span class="small-text">Telepon (0341) 404424 Pes. 101-105, 0341-404420, Fax. (0341) 404420</span>
                <span class="small-text">Laman: www.polinema.ac.id</span>
            </td>
        </tr>
    </table>

    <h1>LAPORAN DATA PENJUALAN</h1>

    <table>
        <thead>
            <tr>
                <th>Kasir</th>
                <th>Pembeli</th>
                <th>Kode Penjualan</th>
                <th>Barang</th>
                <th>Harga</th>
                <th>Jml</th>
                <th>Sub Total</th>
                <th>Tanggal Penjualan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekap as $item)
                <tr>
                    <td>{{ $item['nama'] }}</td>
                    <td>{{ $item['pembeli'] }}</td>
                    <td>{{ $item['penjualan_kode'] }}</td>
                    <td>{{ $item['barang'] }}</td>
                    <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                    <td style="text-align: center">{{ $item['jumlah'] }}</td>
                    <td><strong>Rp {{ number_format($item['total'], 0, ',', '.') }}</strong></td>
                    <td>{{ $item['tgl_penjualan'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
