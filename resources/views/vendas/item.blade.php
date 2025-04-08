@php
    // Verifica se está em modo de edição (variáveis vindas do controller)
    $produtoSelecionado = $item->produto_id ?? null;
    $quantidade = $item->quantidade ?? 1;
@endphp

<div class="item-venda border rounded-3 p-4 mb-3 shadow-sm">
    <div class="row g-3 align-items-end">
        <div class="col-md-6">
            <label class="form-label fw-semibold">Produto</label>
            <select name="produtos[]" class="form-select produto-select" onchange="calcularTotal()" required>
                <option value="">Selecione um produto</option>
                @foreach ($produtos as $produto)
                    <option value="{{ $produto->id }}"
                        data-preco="{{ $produto->preco }}"
                        {{ $produtoSelecionado == $produto->id ? 'selected' : '' }}>
                        {{ $produto->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label fw-semibold">Quantidade</label>
            <input type="number" name="quantidades[]" class="form-control quantidade-input"
                value="{{ $quantidade }}" min="1" oninput="calcularTotal()" required>
        </div>

        <div class="col-md-2 text-end">
            <button type="button" class="btn btn-outline-danger w-100" onclick="removerItem(this)">
                <i class="bi bi-trash3 me-1"></i> Remover
            </button>
        </div>
    </div>
</div>
