@if ($vendas->count())
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Produtos</th>
                <th>Data</th>
                <th>Total</th>
                <th>Forma de Pagamento</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($vendas as $venda)
                <tr>
                    <td>{{ $venda->id }}</td>
                    <td>{{ $venda->cliente->nome ?? '' }}</td>
                    <td>
                        <ul class="list-unstyled mb-0">
                            @foreach ($venda->itens as $item)
                                <li>{{ $item->produto->nome }} (x{{ $item->quantidade }})</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>{{ $venda->created_at->format('d/m/Y') }}</td>
                    <td>R$ {{ number_format($venda->total, 2, ',', '.') }}</td>
                    <td>{{ $venda->forma_pagamento }}</td>
                    <td>
                        <a href="{{ route('vendas.edit', $venda) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('vendas.destroy', $venda) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Excluir</button>
                        </form>
                        <a href="{{ route('vendas.pdf', $venda->id) }}" class="btn btn-outline-secondary" target="_blank">
                            Baixar PDF
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="alert alert-info">Nenhuma venda encontrada.</div>
@endif
