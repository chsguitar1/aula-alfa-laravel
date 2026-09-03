<footer class="border-t border-slate-800 bg-slate-950">
    <div class="mx-auto flex max-w-5xl flex-col items-center justify-between gap-2 px-6 py-6 text-sm text-slate-500 sm:flex-row">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}</p>
        <a href="{{ url('/products') }}" class="transition hover:text-slate-300">Ver produtos</a>
    </div>
</footer>