<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
</head>
<body>

    <h1>{{ $title }}</h1>

    @forelse ($products as $product)
        <div>
            <h2>{{ $product->name }}</h2>
            <p>Preço: {{ $product->price }}</p>
            <p>Unidade de medida: {{ $product->unit }}</p>

            <h3>Itens</h3>
            @forelse ($product->productItens as $item)
                <ul>
                    <li>Quantidade: {{ $item->quantidade }}</li>
                    <li>Cor: {{ $item->cor }}</li>
                    <li>Valor: {{ $item->valor }}</li>
                </ul>
            @empty
                <p>Nenhum item cadastrado para este produto.</p>
            @endforelse

            <hr>
        </div>
    @empty
        <p>Nenhum produto cadastrado.</p>
    @endforelse

    {{ $products->links() }}

</body>
</html>
