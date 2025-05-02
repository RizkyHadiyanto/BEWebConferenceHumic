<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Letter of Acceptance</title>
    <style>
        body { font-family: sans-serif; }
        h1 { text-align: center; }
        .section { margin: 20px 0; }
    </style>
</head>
<body>
    <h1>Letter of Acceptance</h1>
    <div class="section">
        <strong>Paper ID:</strong> {{ $loa->paper_id }}
    </div>
    <div class="section">
        <strong>Authors:</strong> {{ implode(', ', $loa->author_names ?? []) }}<br>
        <strong>Title:</strong> {{ $loa->paper_title }}<br>
        <strong>Status:</strong> {{ $loa->status }}
    </div>
    <div class="section">
        <strong>Tempat & Tanggal:</strong> {{ $loa->tempat_tanggal }}<br>
        <strong>Signature:</strong> {{ $loa->signature->picture }}<br>
        <strong>Nama Penandatangan:</strong> {{ $loa->signature->nama_penandatangan }}<br>
        <strong>Jabatan:</strong> {{ $loa->signature->jabatan_penandatangan }}<br>
    </div>
</body>
</html>
