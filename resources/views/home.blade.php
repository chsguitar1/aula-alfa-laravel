<x-layout>
    <main>
        <section class="border-b border-slate-800 bg-slate-900/40">
            <div class="mx-auto flex max-w-5xl flex-col items-start gap-6 px-6 py-20">
                <span
                    class="rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-semibold tracking-wide text-emerald-400 uppercase">
                    Catálogo
                </span>

                <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl">
                    Gerencie seus produtos<br class="hidden sm:block"> em um só lugar
                </h1>

                <p class="max-w-xl text-lg text-slate-400">
                    Acompanhe preços, unidades de medida e itens de composição de cada produto cadastrado.
                </p>

                <a href="{{ url('/products') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-emerald-500 px-5 py-3 text-sm font-semibold text-slate-950 shadow-sm transition hover:bg-emerald-400">
                    Ver produtos
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>
        </section>

        <section class="mx-auto max-w-5xl px-6 py-14">
            <div class="grid gap-6 sm:grid-cols-2">
                <div class="rounded-xl border border-slate-800 bg-slate-900 p-6 shadow-sm">
                    <p class="text-sm font-medium text-slate-400">Produtos cadastrados</p>
                    <p class="mt-2 text-3xl font-bold text-white">{{ $productsCount }}</p>
                </div>

                <div class="rounded-xl border border-slate-800 bg-slate-900 p-6 shadow-sm">
                    <p class="text-sm font-medium text-slate-400">Itens cadastrados</p>
                    <p class="mt-2 text-3xl font-bold text-white">{{ $itemsCount }}</p>
                </div>
            </div>
        </section>
    </main>
</x-layout>
