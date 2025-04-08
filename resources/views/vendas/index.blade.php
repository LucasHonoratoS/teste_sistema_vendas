@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Vendas</h2>
        <a href="{{ route('vendas.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg me-1"></i> Nova Venda
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form id="filtro-vendas" class="mb-3">
                <label for="termo" class="form-label fw-semibold">Buscar Venda</label>
                <div class="input-group">
                    <input type="text" name="termo" id="termo" class="form-control" placeholder="Filtrar por cliente ou produto">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i> Filtrar
                    </button>
                    <button type="reset" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i> Limpar
                    </button>
                </div>
            </form>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div id="resultado-vendas">
                @include('vendas.tabela', ['vendas' => $vendas])
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        configurarFiltroAjax(
            '#filtro-vendas',
            '#resultado-vendas',
            '{{ route("vendas.index") }}'
        );
    });
</script>
@endsection
