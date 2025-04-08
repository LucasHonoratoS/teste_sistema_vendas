@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Nova Venda</h2>
        <a href="{{ route('vendas.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Voltar
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0">
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('vendas.store') }}" method="POST" id="form-venda" onsubmit="return validarAntesDeEnviar()">
                @csrf

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="cliente_id" class="form-label fw-semibold">Cliente</label>
                        <select name="cliente_id" id="cliente_id" class="form-select" required>
                            <option value="">Selecione um cliente</option>
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="p-3 rounded mb-4">
                    <h5 class="fw-semibold border-bottom pb-2 mb-3">Itens da Venda</h5>
                    <div id="itens-venda">
                        @include('vendas.item')
                    </div>
                    <button type="button" class="btn btn-outline-primary mt-3" onclick="adicionarItem()">
                        <i class="bi bi-plus-circle"></i> Adicionar Produto
                    </button>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="total" class="form-label fw-semibold">Valor Total (R$)</label>
                        <input type="text" name="total" id="total" class="form-control bg-white" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="forma_pagamento" class="form-label fw-semibold">Forma de Pagamento</label>
                        <select name="forma_pagamento" id="forma_pagamento" class="form-select" required onchange="verificarPagamento()">
                            <option value="">Selecione</option>
                            <option value="debito">Débito</option>
                            <option value="credito">Crédito</option>
                        </select>
                    </div>
                </div>

                <div id="parcelamento" class="p-3 rounded mb-4" style="display: none;">
                    <h5 class="fw-semibold border-bottom pb-2 mb-3">Parcelamento</h5>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="quantidade_parcelas" class="form-label fw-semibold">Quantidade de Parcelas</label>
                            <input type="number" id="quantidade_parcelas" class="form-control" min="1" max="12" onchange="gerarParcelas()">
                        </div>
                    </div>

                    <div id="parcelas-venda"></div>

                    <div class="alert alert-danger d-none mt-3" id="alerta-parcelas">
                        A soma das parcelas não confere com o valor total da venda.
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i> Salvar
                    </button>
                    <a href="{{ route('vendas.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function calcularTotal() {
        const itens = document.querySelectorAll('.item-venda');
        let total = 0;

        itens.forEach(item => {
            const produto = item.querySelector('.produto-select');
            const quantidade = item.querySelector('.quantidade-input');
            const preco = parseFloat(produto.selectedOptions[0]?.dataset.preco) || 0;
            const qtd = parseFloat(quantidade.value) || 0;
            total += preco * qtd;
        });

        document.getElementById('total').value = total.toFixed(2).replace('.', ',');
        validarParcelas();
    }

    function adicionarItem() {
        const item = document.querySelector('.item-venda').cloneNode(true);
        item.querySelector('.produto-select').selectedIndex = 0;
        item.querySelector('.quantidade-input').value = 1;
        document.getElementById('itens-venda').appendChild(item);
        calcularTotal();
    }

    function removerItem(btn) {
        const container = document.getElementById('itens-venda');
        if (container.children.length > 1) {
            btn.closest('.item-venda').remove();
            calcularTotal();
        }
    }

    function verificarPagamento() {
        const forma = document.getElementById('forma_pagamento').value;
        const parcelamento = document.getElementById('parcelamento');

        if (forma === 'credito') {
            parcelamento.style.display = 'block';
        } else {
            parcelamento.style.display = 'none';
            document.getElementById('parcelas-venda').innerHTML = '';
        }
    }

    function gerarParcelas() {
        const qtde = parseInt(document.getElementById('quantidade_parcelas').value) || 0;
        const total = parseFloat(document.getElementById('total').value.replace(',', '.')) || 0;

        if (qtde <= 0 || total <= 0) return;

        const container = document.getElementById('parcelas-venda');
        container.innerHTML = '';

        // Valor base com duas casas decimais (arredondado para baixo)
        const valorBase = Math.floor((total / qtde) * 100) / 100;

        let somaParcelas = 0;
        const valores = [];

        for (let i = 0; i < qtde; i++) {
            valores.push(valorBase);
            somaParcelas += valorBase;
        }

        // Corrigir a última parcela com a diferença
        let diferenca = (total - somaParcelas).toFixed(2);
        valores[qtde - 1] += parseFloat(diferenca);

        const hoje = new Date();

        valores.forEach((valor, i) => {
            const vencimento = new Date(hoje.getFullYear(), hoje.getMonth() + i, hoje.getDate());
            const dataVencimento = vencimento.toISOString().split('T')[0];

            const html = `
                <div class="row mb-2 parcela-item">
                    <div class="col-md-6">
                        <label>Valor da Parcela</label>
                        <input type="number" name="parcelas[]" class="form-control parcela-valor" step="0.01"
                            value="${valor.toFixed(2)}" data-valor-original="${valor.toFixed(2)}" oninput="validarParcelas(true)">
                    </div>
                    <div class="col-md-6">
                        <label>Data de Vencimento</label>
                        <input type="date" name="datas_parcelas[]" class="form-control" value="${dataVencimento}" required>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        });

        validarParcelas(false);
    }


    function validarParcelas(mostrarErro = false) {
        const totalVenda = parseFloat(document.getElementById('total').value.replace(',', '.')) || 0;
        const parcelas = document.querySelectorAll('.parcela-valor');
        let soma = 0;
        let alterado = false;

        parcelas.forEach(input => {
            const valorAtual = parseFloat(input.value) || 0;
            const valorOriginal = parseFloat(input.dataset.valorOriginal) || 0;
            soma += valorAtual;

            if (Math.abs(valorAtual - valorOriginal) > 0.01) {
                alterado = true;
            }
        });

        const alerta = document.getElementById('alerta-parcelas');

        if (parcelas.length && Math.abs(soma - totalVenda) > 0.01 && alterado && mostrarErro) {
            alerta.classList.remove('d-none');
        } else {
            alerta.classList.add('d-none');
        }
    }

    function validarAntesDeEnviar() {
    const alerta = document.getElementById('alerta-parcelas');
    if (!alerta.classList.contains('d-none')) {
        alert("Corrija os valores das parcelas antes de enviar.");
        return false;
    }
    return true;
}
</script>
@endsection
