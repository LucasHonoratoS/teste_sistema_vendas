@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Dashboard</h1>

    {{-- Boas-vindas --}}
    <div class="alert alert-success" role="alert">
        Olá, <strong>{{ Auth::user()->name }}</strong>! Você está logado.
    </div>

    {{-- Cards de estatísticas --}}
    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Vendas</div>
                <div class="card-body">
                    <h5 class="card-title">123</h5>
                    <p class="card-text">Total de vendas realizadas este mês.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Clientes</div>
                <div class="card-body">
                    <h5 class="card-title">45</h5>
                    <p class="card-text">Novos clientes cadastrados.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Produtos</div>
                <div class="card-body">
                    <h5 class="card-title">67</h5>
                    <p class="card-text">Produtos em estoque.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Espaço para gráficos, relatórios, etc. --}}
    <div class="card mt-4">
        <div class="card-header">Relatório do mês</div>
        <div class="card-body">
            <p>Aqui você pode adicionar gráficos ou relatórios.</p>
        </div>
    </div>
</div>
@endsection
