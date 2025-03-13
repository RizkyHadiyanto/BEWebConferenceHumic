<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold text-center text-gray-700">Login</h2>

        @if (session('error'))
            <div class="text-red-500 text-sm mt-2">{{ session('error') }}</div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="mt-4">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-600">Email</label>
                <input type="email" name="email" required class="w-full p-2 border border-gray-300 rounded">
            </div>
            <div class="mb-4">
                <label class="block text-gray-600">Password</label>
                <input type="password" name="password" required class="w-full p-2 border border-gray-300 rounded">
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">
                Login
            </button>
        </form>
    </div>

</body>
</html>
