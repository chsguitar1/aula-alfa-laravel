<x-layout>
    <main class="mx-auto max-w-5xl px-6 py-10">
        <div class="mb-8 flex items-start justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-white">{{ $product->name }}</h1>
                <p class="mt-1 text-sm text-slate-400">
                    Unidade: <span class="font-medium text-slate-200">{{ $product->unitOfMeasurement }}</span>
                </p>
            </div>
            <span class="shrink-0 rounded-full bg-emerald-500/10 px-3 py-1 text-sm font-medium text-emerald-400">
                R$ {{ number_format($product->price, 2, ',', '.') }}
            </span>
        </div>

        <div class="rounded-xl border border-slate-800 bg-slate-900 p-5 shadow-sm">
            <h3 class="mb-2 text-xs font-semibold tracking-wide text-slate-500 uppercase">Itens</h3>

            <ul class="space-y-2">
                @foreach ($product->productItens as $item)
                    <li class="flex items-center justify-between rounded-lg bg-slate-800/60 px-3 py-2 text-sm">
                        <span class="flex items-center gap-2 text-slate-300">
                            <span class="h-2.5 w-2.5 rounded-full bg-slate-600"></span>
                            {{ $item->quantity }}x {{ $item->color }}
                        </span>
                        <span class="font-medium text-slate-100">R$
                            {{ number_format($item->value, 2, ',', '.') }}</span>
                    </li>
                @endforeach
            </ul>

            @if ($product->productItens->isEmpty())
                <div class="rounded-xl border border-dashed border-slate-700 p-10 text-center text-slate-500">
                    Nenhum item cadastrado para este produto.
                </div>
            @endif
        </div>
    </main>
</x-layout>
