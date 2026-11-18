<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #1f2937; margin: 24px; }
        h1 { font-size: 20px; margin-bottom: 4px; }
        p.meta { font-size: 12px; color: #6b7280; margin-top: 0; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th, td { border: 1px solid #d1d5db; padding: 8px 10px; text-align: left; }
        th { background-color: #f3f4f6; }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <p class="meta">Engine: Laravel Snappy PDF &middot; Cache: Redis</p>

    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Judul Tugas</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item['code'] }}</td>
                    <td>{{ $item['title'] }}</td>
                    <td>{{ $item['status'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
