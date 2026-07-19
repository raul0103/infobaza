<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#2563eb">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="infobaza">
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <link rel="icon" href="{{ asset('icon.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('icon.svg') }}">
    <title>@yield('title', 'Главная') — infobaza</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
                    boxShadow: {
                        soft: '0 1px 3px 0 rgb(0 0 0 / 0.06), 0 1px 2px -1px rgb(0 0 0 / 0.06)',
                        card: '0 1px 3px 0 rgb(0 0 0 / 0.08), 0 4px 12px -2px rgb(0 0 0 / 0.05)',
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer components {
            ul, ol { @apply list-none pl-0; }
            .nav-link {
                @apply flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600
                hover:bg-gray-100 hover:text-gray-900 transition-colors;
            }
            .nav-link.active {
                @apply bg-blue-50 text-blue-700 hover:bg-blue-50 hover:text-blue-700;
            }
            .nav-link.active .nav-icon { @apply text-blue-600; }
            .nav-icon { @apply w-5 h-5 shrink-0 text-gray-400; }
            .nav-link:hover .nav-icon { @apply text-gray-600; }
            .nav-section-label {
                @apply px-3 pt-5 pb-1.5 text-[10px] font-semibold uppercase tracking-widest text-gray-400;
            }
            .nav-section-label:first-child { @apply pt-1; }
            .btn {
                @apply inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium
                transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50
                min-h-[44px] sm:min-h-0;
            }
            .btn-primary { @apply btn bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500 shadow-sm; }
            .btn-secondary { @apply btn bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 focus:ring-gray-400; }
            .btn-danger { @apply btn bg-white text-red-600 border border-red-200 hover:bg-red-50 focus:ring-red-400; }
            .btn-ghost { @apply btn text-gray-600 hover:bg-gray-100 focus:ring-gray-400; }
            .btn-success { @apply btn bg-emerald-600 text-white hover:bg-emerald-700 focus:ring-emerald-500 shadow-sm; }
            .card { @apply bg-white rounded-xl border border-gray-200 shadow-soft p-4 sm:p-6; }
            .card-hover { @apply card hover:border-blue-200 hover:shadow-card transition-all duration-200; }
            .card-form { @apply bg-white rounded-xl sm:rounded-2xl border border-gray-200 shadow-card p-4 sm:p-8; }
            .label { @apply block text-sm font-medium text-gray-700 mb-1.5; }
            .hint { @apply mt-1.5 text-xs text-gray-500; }
            .input, .select, .textarea {
                @apply w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-base sm:text-sm text-gray-900
                placeholder:text-gray-400 shadow-sm min-h-[44px] sm:min-h-0
                focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20
                disabled:bg-gray-50 disabled:text-gray-500;
            }
            .textarea { @apply resize-y min-h-[120px] py-3; }
            .select { @apply pr-10; }
            .form-group { @apply space-y-0; }
            .page-title { @apply text-xl sm:text-2xl font-bold tracking-tight text-gray-900; }
            .page-subtitle { @apply mt-1 text-sm text-gray-500; }
            .section-title { @apply text-base font-semibold text-gray-900; }
            .link { @apply text-sm font-medium text-blue-600 hover:text-blue-800 py-1; }
            .badge { @apply inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium; }
            .badge-blue { @apply badge bg-blue-50 text-blue-700; }
            .badge-gray { @apply badge bg-gray-100 text-gray-600; }
            .list-item { @apply py-3.5 border-b border-gray-100 last:border-0; }
            .empty-state { @apply text-sm text-gray-500 py-4; }
            .stat-card { @apply card text-center hover:shadow-card transition-shadow p-4 sm:p-6; }
            .stat-value { @apply text-2xl sm:text-3xl font-bold text-blue-600 tabular-nums; }
            .stat-label { @apply text-xs sm:text-sm text-gray-500 mt-1; }
            .table-scroll { @apply overflow-x-auto -mx-4 sm:mx-0 px-4 sm:px-0; }
            .list-row { @apply flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between sm:gap-4; }
            .page-actions { @apply flex flex-col w-full sm:w-auto sm:flex-row sm:flex-wrap gap-2; }
            .page-actions .btn { @apply w-full sm:w-auto justify-center; }
            .safe-top { padding-top: max(0px, env(safe-area-inset-top)); }
            .safe-bottom { padding-bottom: max(0px, env(safe-area-inset-bottom)); }
            .collapsible-section > summary::-webkit-details-marker { display: none; }
            .collapsible-section > summary { list-style: none; }
            .collapsible-section .collapse-chevron { @apply transition-transform; }
            .collapsible-section[open] .collapse-chevron { transform: rotate(90deg); }
        }
    </style>
    @stack('head')
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen font-sans antialiased">
<div id="sidebar-backdrop" class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm hidden lg:hidden" aria-hidden="true"></div>

<div class="flex min-h-screen min-h-[100dvh]">
    <aside id="sidebar"
        class="fixed inset-y-0 left-0 z-50 flex w-[min(100vw-3rem,18rem)] flex-col bg-white border-r border-gray-200 shadow-xl
        -translate-x-full transition-transform duration-300 ease-out
        lg:static lg:z-auto lg:w-64 lg:translate-x-0 lg:shadow-soft shrink-0">
        <div class="flex items-center justify-between p-4 border-b border-gray-100 lg:p-5">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 min-w-0" onclick="closeSidebar()">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-600 text-white text-sm font-bold">M</span>
                <span class="text-lg font-bold text-gray-900 truncate">infobaza</span>
            </a>
            <button type="button" id="sidebar-close" class="lg:hidden p-2 -mr-1 rounded-lg text-gray-500 hover:bg-gray-100" aria-label="Закрыть меню">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <p class="text-xs text-gray-400 px-5 pb-2 lg:pl-5 hidden sm:block lg:hidden">Личная база знаний</p>
        <nav class="flex-1 px-3 py-2 overflow-y-auto overscroll-contain">
            @include('partials.sidebar-nav')
        </nav>
    </aside>

    <div class="flex flex-1 flex-col min-w-0 w-full lg:pt-0 pt-14">
        <header class="fixed top-0 left-0 right-0 z-30 flex h-14 items-center gap-3 border-b border-gray-200 bg-white/95 backdrop-blur px-4 lg:hidden safe-top">
            <button type="button" id="sidebar-open" class="p-2 -ml-2 rounded-lg text-gray-600 hover:bg-gray-100" aria-label="Открыть меню">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <a href="{{ route('dashboard') }}" class="font-semibold text-gray-900 truncate">infobaza</a>
        </header>

        @if(session('success'))
            <div class="mx-4 mt-4 lg:mx-8 lg:mt-6 rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 flex items-start gap-2 text-sm text-emerald-800">
                <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span class="min-w-0">{{ session('success') }}</span>
            </div>
        @endif

        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden overflow-y-auto">
            <div class="max-w-6xl mx-auto w-full">
                @yield('content')
            </div>
        </main>
    </div>
</div>

<script>
function openModal(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}
function closeModal(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeSidebar();
        document.querySelectorAll('[role="dialog"]:not(.hidden)').forEach((dialog) => closeModal(dialog.id));
        document.querySelectorAll('details.action-menu[open]').forEach((menu) => menu.removeAttribute('open'));
    }
});

// Capture-фаза: срабатывает даже там, где клики гасятся через stopPropagation
document.addEventListener('click', (e) => {
    document.querySelectorAll('details.action-menu[open]').forEach((menu) => {
        if (!menu.contains(e.target)) {
            menu.removeAttribute('open');
        }
    });
}, true);

const sidebar = document.getElementById('sidebar');
const backdrop = document.getElementById('sidebar-backdrop');

function openSidebar() {
    if (!sidebar || window.matchMedia('(min-width: 1024px)').matches) return;
    sidebar.classList.remove('-translate-x-full');
    backdrop?.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeSidebar() {
    if (!sidebar) return;
    sidebar.classList.add('-translate-x-full');
    backdrop?.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

document.getElementById('sidebar-open')?.addEventListener('click', openSidebar);
document.getElementById('sidebar-close')?.addEventListener('click', closeSidebar);
backdrop?.addEventListener('click', closeSidebar);

sidebar?.querySelectorAll('a.nav-link').forEach((link) => {
    link.addEventListener('click', () => closeSidebar());
});

window.addEventListener('resize', () => {
    if (window.matchMedia('(min-width: 1024px)').matches) closeSidebar();
});
</script>
@stack('scripts')
</body>
</html>
