<?php

namespace App\Http\Controllers;

use App\Models\Venda;
use App\Models\Cliente;
use App\Models\Produto;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $inicioDoMes = Carbon::now()->startOfMonth();

        $totalVendas = Venda::count();
        $totalClientes = Cliente::count();
        $totalProdutos = Produto::count();

        $dadosVendas = Venda::selectRaw('DATE(created_at) as data, COUNT(*) as total')
            ->where('created_at', '>=', $inicioDoMes)
            ->groupBy('data')
            ->orderBy('data')
            ->get();

        $labels = $dadosVendas->pluck('data')->map(fn($d) => Carbon::parse($d)->format('d/m'))->toArray();
        $valores = $dadosVendas->pluck('total')->toArray();

        return view('dashboard', compact('totalVendas', 'totalClientes', 'totalProdutos', 'labels', 'valores'));
    }
}

