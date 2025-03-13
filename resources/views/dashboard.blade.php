<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-96 text-center">
        <h2 class="text-2xl font-bold text-gray-700">Dashboard</h2>
        <p class="mt-4 text-gray-600">Selamat datang, <span class="font-semibold">{{ auth()->user()->name }}</span>!</p>
        <p class="mt-2 text-blue-500">Role: <span class="font-semibold uppercase">{{ auth()->user()->role }}</span></p>

        <a href="{{ route('logout') }}" 
           class="block mt-6 bg-red-500 text-white py-2 rounded hover:bg-red-600">Logout</a>
    </div>

</body>
</html>
