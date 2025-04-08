@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Clientes</h2>
        <a href="{{ route('clientes.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg me-1"></i> Novo Cliente
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
        <form id="filtro-clientes" class="mb-3">
            <label for="termo" class="form-label fw-semibold">Buscar Cliente</label>
            <div class="input-group">
                <input type="text" name="termo" id="termo" class="form-control" placeholder="Filtrar por nome ou CPF">
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

            <div id="tabela-clientes">
                @include('clientes.tabela', ['clientes' => $clientes])
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        configurarFiltroAjax(
            '#filtro-clientes',
            '#resultado-clientes',
            '{{ route("clientes.index") }}'
        );
    });
</script>
@endsection
