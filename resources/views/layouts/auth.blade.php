<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>@yield('title', 'Вход') — infobaza</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 text-gray-900 font-sans antialiased">
    <main class="min-h-screen flex items-center justify-center p-4">
        <section class="w-full max-w-md rounded-2xl border border-gray-200 bg-white shadow-sm p-6 sm:p-8">
            <h1 class="text-2xl font-bold tracking-tight mb-2">@yield('title', 'Вход')</h1>
            <p class="text-sm text-gray-500 mb-6">@yield('subtitle')</p>

            @if(session('success'))
                <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            {{ $slot ?? '' }}
            @yield('content')
        </section>
    </main>
</body>
</html>
