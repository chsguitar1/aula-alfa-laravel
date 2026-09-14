<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pordutos</title>
</head>

<body>

    <h1>Lista de Produtos</h1>

    @forelse ($products as $product)

        <h2>{{ $product->nome }}</h2>

        <p>
            <strong>Preço:</strong>
            R$ {{ number_format($product->preco, 2, ',', '.') }}
        </p>

        <p>
            <strong>Unidade de medida:</strong>
            {{ $product->unidade_medida }}
        </p>

        <h3>Itens</h3>

        @forelse ($product->itens as $item)

            <ul>
                <li>
                    Quantidade: {{ $item->quantidade }}
                </li>

                <li>
                    Cor: {{ $item->cor }}
                </li>

                <li>
                    Valor: R$ {{ number_format($item->valor, 2, ',', '.') }}
                </li>
            </ul>

        @empty

            <p>Este produto não possui itens.</p>

        @endforelse

        <hr>

    @empty

        <p>Nenhum produto cadastrado.</p>

    @endforelse
</body>

</html>