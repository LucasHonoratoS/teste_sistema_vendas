@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Dashboard</h1>
    <div class="alert alert-secondary" role="alert">
        Olá, <strong>{{ Auth::user()->name }}</strong>! Você está logado.
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Vendas</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalVendas }}</h5>
                    <p class="card-text">Total de vendas.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Clientes</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalClientes }}</h5>
                    <p class="card-text">Novos clientes cadastrados este mês.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Produtos</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalProdutos }}</h5>
                    <p class="card-text">Produtos atualmente no estoque.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">Relatório de vendas do mês</div>
        <div class="card-body">
            <canvas id="graficoVendasMes" height="100"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('graficoVendasMes').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Vendas por dia',
                data: @json($valores),
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.2)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 1
                }
            }
        }
    });
</script>
@endsection
