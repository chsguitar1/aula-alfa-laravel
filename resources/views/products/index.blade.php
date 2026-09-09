<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
</head>
<body>

    <h1>Produtos</h1>

    @foreach ($products as $product)
        <div>
            <h2>{{ $product->nome }}</h2>

            <p>
                Preço: R$ {{ number_format($product->preco, 2, ',', '.') }}
            </p>

            <p>
                Unidade de medida: {{ $product->unidade_medida }}
            </p>

            <h3>Itens de composição</h3>

            @if ($product->itens->count())
                <ul>
                    @foreach ($product->itens as $item)
                        <li>
                            Quantidade: {{ $item->quantidade }}
                            | Cor: {{ $item->cor }}
                            | Valor: R$ {{ number_format($item->valor, 2, ',', '.') }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p>Nenhum item cadastrado.</p>
            @endif
        </div>

        <hr>
    @endforeach

</body>
</html>
