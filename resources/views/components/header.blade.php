<header class="sticky top-0 z-10 border-b border-slate-800 bg-slate-950/80 backdrop-blur">
    <div class="mx-auto flex max-w-5xl items-center justify-between px-6 py-4">
        <a href="{{ url('/') }}" class="flex items-center gap-2 text-lg font-semibold tracking-tight text-white">
            <span
                class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-500 text-sm font-bold text-slate-950">
                {{ Str::substr(config('app.name'), 0, 1) }}
            </span>
            {{ config('app.name') }}
        </a>

        <nav class="flex items-center gap-6 text-sm font-medium text-slate-400">
            <a href="{{ url('/products') }}"
                class="transition hover:text-white {{ request()->is('products*') ? 'text-white' : '' }}">
                Produtos
            </a>
        </nav>
    </div>
</header>