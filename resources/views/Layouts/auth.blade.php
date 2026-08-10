<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login') - E-Perpus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .input { width: 100%; padding: 0.625rem 0.875rem; font-size: 0.875rem; border: 1px solid #d1d5db; border-radius: 0.375rem; transition: border-color 0.15s ease; }
        .input:focus { outline: none; border-color: #111827; }
        .label { display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.375rem; }
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 0.625rem 1.25rem; font-size: 0.875rem; font-weight: 500; border-radius: 0.375rem; transition: all 0.15s ease; cursor: pointer; border: none; text-decoration: none; }
        .btn-primary { background-color: #111827; color: #ffffff; }
        .btn-primary:hover { background-color: #374151; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col justify-center items-center px-4">
        <div class="w-full max-w-sm">
            @yield('content')
        </div>
    </div>
</body>
</html>
