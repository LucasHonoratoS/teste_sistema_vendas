@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Produtos</h2>
        <a href="{{ route('produtos.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg me-1"></i> Novo Produto
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form id="filtro-produtos" class="mb-3">
                <label for="termo" class="form-label fw-semibold">Buscar Produto</label>
                <div class="input-group">
                    <input type="text" name="termo" id="termo" class="form-control" placeholder="Filtrar por nome ou preço">
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

            <div id="resultado-produtos">
                @include('produtos.tabela', ['produtos' => $produtos])
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        configurarFiltroAjax(
            '#filtro-produtos',
            '#resultado-produtos',
            '{{ route("produtos.index") }}'
        );
    });
</script>
@endsection
