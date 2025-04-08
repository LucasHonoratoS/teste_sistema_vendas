<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Resumo da Venda</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 5px; text-align: left; }
    </style>
</head>
<body>
    <h2>Resumo da Venda #{{ $venda->id }}</h2>
    <p><strong>Cliente:</strong> {{ $venda->cliente->nome ?? 'Não informado' }}</p>
    <p><strong>Vendedor:</strong> {{ $venda->usuario->name }}</p>
    <p><strong>Forma de Pagamento:</strong> {{ ucfirst($venda->forma_pagamento) }}</p>
    <hr>

    <h4>Itens:</h4>
    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Qtd</th>
                <th>Preço Unitário</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($venda->itens as $item)
                <tr>
                    <td>{{ $item->produto->nome }}</td>
                    <td>{{ $item->quantidade }}</td>
                    <td>R$ {{ number_format($item->produto->preco, 2, ',', '.') }}</td>
                    <td>R$ {{ number_format($item->quantidade * $item->produto->preco, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($venda->forma_pagamento == "credito")
        <h4 style="margin-top: 20px;">Parcelas:</h4>
        <table>
            <thead>
                <tr>
                    <th>Vencimento</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($venda->parcelas as $parcela)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($parcela->vencimento)->format('d/m/Y') }}</td>
                        <td>R$ {{ number_format($parcela->valor, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h3>Total: R$ {{ number_format($venda->total, 2, ',', '.') }}</h3>
</body>
</html>
