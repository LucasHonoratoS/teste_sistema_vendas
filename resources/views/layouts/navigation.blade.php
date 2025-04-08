@php
    $user = Auth::user();
@endphp

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm mb-4">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-primary" href="{{ route('dashboard') }}">
            <i class="bi bi-kanban"></i> {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-semibold text-primary' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2 me-1"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('vendas.*') ? 'active fw-semibold text-primary' : '' }}" href="{{ route('vendas.index') }}">
                    <i class="bi bi-cart3 me-1"></i> Vendas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('clientes.*') ? 'active fw-semibold text-primary' : '' }}" href="{{ route('clientes.index') }}">
                    <i class="bi bi-people me-1"></i> Clientes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('produtos.*') ? 'active fw-semibold text-primary' : '' }}" href="{{ route('produtos.index') }}">
                    <i class="bi bi-box-seam me-1"></i> Produtos
                </a>
            </li>
        </ul>
    </div>
</nav>
