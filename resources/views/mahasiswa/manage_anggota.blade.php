<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Anggota</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> 
</head>
<body>
    <div class="container mx-auto mt-5">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-bold">Manage Anggota</h1>
            <p>Hi, bph@stimata.ac.id</p>
        </div>

        <div class="flex justify-between mb-4">
            <div>
                <button class="bg-blue-500 text-white px-4 py-2 rounded">Tambah Anggota</button>
                <button class="bg-green-500 text-white px-4 py-2 rounded ml-2">Export Excel</button>
            </div>
            <h2 class="text-lg font-semibold">Data</h2>
        </div>

        <table class="min-w-full border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border px-4 py-2">No</th>
                    <th class="border px-4 py-2">NIM</th>
                    <th class="border px-4 py-2">Email</th>
                    <th class="border px-4 py-2">Date</th>
                    <th class="border px-4 py-2">Status</th>
                    <th class="border px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
            
                @foreach ($anggota as $index => $item)
                    <tr>
                        <td class="border px-4 py-2">{{ $index + 1 }}</td>
                        <td class="border px-4 py-2">{{ $item->nim }}</td>
                        <td class="border px-4 py-2">{{ $item->email }}</td>
                        <td class="border px-4 py-2">{{ $item->created_at->format('d-m-Y') }}</td> 
                        <td class="border px-4 py-2">{{ $item->status }}</td>
                        <td class="border px-4 py-2">
                            <button class="bg-yellow-500 text-white px-3 py-1 rounded">Edit</button>
                            <button class="bg-red-500 text-white px-3 py-1 rounded">Hapus</button> 
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    
    <script src="{{ asset('js/app.js') }}"></script> 
</body>
</html>