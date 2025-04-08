<div class="item-venda border p-3 mb-3 rounded">
    <div class="row">
        <div class="col-md-6 mb-2">
            <label class="form-label">Produto</label>
            <select name="produtos[]" class="form-select produto-select" onchange="calcularTotal()">
                <option value="">Selecione</option>
                @foreach ($produtos as $produto)
                    <option value="{{ $produto->id }}" data-preco="{{ $produto->preco }}">{{ $produto->nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 mb-2">
            <label class="form-label">Quantidade</label>
            <input type="number" name="quantidades[]" class="form-control quantidade-input" value="1" min="1" oninput="calcularTotal()">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-danger w-100" onclick="removerItem(this)">
                <i class="bi bi-trash"></i> Remover
            </button>
        </div>
    </div>
</div>
