@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Editar Venda</h2>
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

    <form action="{{ route('vendas.update', $venda) }}" method="POST" id="form-venda" onsubmit="return validarAntesDeEnviar()">
        @csrf
        @method('PUT')

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom fw-semibold">
                Dados do Cliente
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label for="cliente_id" class="form-label">Cliente</label>
                        <select name="cliente_id" id="cliente_id" class="form-select">
                            <option value="">Selecione um cliente</option>
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ $venda->cliente_id == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom fw-semibold d-flex justify-content-between align-items-center">
                Itens da Venda
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="adicionarItem()">
                    <i class="bi bi-plus-circle me-1"></i> Adicionar Produto
                </button>
            </div>
            <div class="card-body" id="itens-venda">
                @foreach ($venda->itens as $index => $item)
                    @include('vendas.item', ['index' => $index, 'item' => $item])
                @endforeach
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom fw-semibold">Pagamento</div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="total" class="form-label">Valor Total (R$)</label>
                        <input type="text" name="total" id="total" class="form-control bg-white" readonly
                            value="{{ number_format($venda->total, 2, ',', '.') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="forma_pagamento" class="form-label">Forma de Pagamento</label>
                        <select name="forma_pagamento" id="forma_pagamento" class="form-select" required onchange="verificarPagamento()">
                            <option value="">Selecione</option>
                            <option value="debito" {{ $venda->forma_pagamento == 'debito' ? 'selected' : '' }}>Débito</option>
                            <option value="credito" {{ $venda->forma_pagamento == 'credito' ? 'selected' : '' }}>Crédito</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div id="parcelamento" class="card shadow-sm border-0 mb-4" style="{{ $venda->forma_pagamento === 'credito' ? '' : 'display: none;' }}">
            <div class="card-header bg-white border-bottom fw-semibold">Parcelamento</div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="quantidade_parcelas" class="form-label">Quantidade de Parcelas</label>
                        <input type="number" id="quantidade_parcelas" class="form-control" min="1" max="12"
                            value="{{ count($venda->parcelas) }}" onchange="gerarParcelas()">
                    </div>
                </div>

                <div id="parcelas-venda">
                    @foreach ($venda->parcelas as $index => $parcela)
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label class="form-label">Valor da Parcela</label>
                                <input type="number" name="parcelas[]" class="form-control"
                                    value="{{ number_format($parcela->valor, 2, '.', '') }}" step="0.01">
                                <input type="hidden" name="parcelas_originais[]" value="{{ number_format($parcela->valor, 2, ',', '.') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Data de Vencimento</label>
                                <input type="date" name="datas_parcelas[]" class="form-control"
                                    value="{{ \Carbon\Carbon::parse($parcela->vencimento)->format('Y-m-d') }}">
                                <input type="hidden" name="datas_parcelas_originais[]" value="{{ \Carbon\Carbon::parse($parcela->vencimento)->format('Y-m-d') }}">
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="alert alert-danger d-none mt-3" id="alerta-parcelas">
                    A soma das parcelas não confere com o valor total da venda.
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end gap-2 mt-4">
            <button type="submit" class="btn btn-warning">
                <i class="bi bi-save me-1"></i> Atualizar
            </button>
            <a href="{{ route('vendas.index') }}" class="btn btn-secondary">
                <i class="bi bi-x-circle me-1"></i> Cancelar
            </a>
        </div>
    </form>
</div>

@include('vendas.js')
@endsection