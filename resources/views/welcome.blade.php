<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <!-- Menggunakan Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="text-center">
        <!-- Heading utama -->
        <h1 class="text-4xl font-bold text-gray-800">Welcome to Laravel</h1>
        <p class="mt-4 text-gray-600">Build something amazing!</p>

        <!-- Tombol-tombol untuk navigasi -->
        <div class="mt-6">
            <!-- Tombol menuju ke Admin Login -->
            <a href="{{ route('admin.login') }}"
                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                Admin Login
            </a>
            <!-- Tombol menuju ke API Login -->
            <a href="/api/login"
                class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 ml-4">
                API Login
            </a>

            <!-- Tombol Logout (hanya ditampilkan jika sudah login) -->
            @auth
                <form action="{{ route('admin.logout') }}" method="POST" class="mt-4 inline">
                    @csrf
                    @method('POST')
                    <button type="submit"
                        class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                        Logout
                    </button>
                </form>
            @endauth
        </div>
    </div>
</body>

</html>
