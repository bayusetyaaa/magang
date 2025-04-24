<!DOCTYPE html>
<html>
<head>
    <title>Kunjungan {{ $event->nama_acara }}</title>
</head>
<body>
    <h1>Data Kunjungan: {{ $event->nama_acara }}</h1>
    <table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>No</th>
                <th>ID Tamu</th>
                <th>Nama Tamu</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Asal Instansi</th>
                <th>Jabatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kunjungan as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->id_tamu }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->jam_masuk)->format('H:i') }}</td>
                    <td>{{ $item->asal_instansi }}</td>
                    <td>{{ $item->jabatan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
