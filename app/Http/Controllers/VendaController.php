<?php

namespace App\Http\Controllers;

use App\Models\Venda;
use App\Models\Cliente;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class VendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Venda::with(['cliente', 'usuario', 'itens.produto']);

        if ($request->has('termo')) {
            $termo = $request->termo;

            $query->whereHas('cliente', function ($q) use ($termo) {
                $q->where('nome', 'like', "%{$termo}%");
            })->orWhereHas('itens.produto', function ($q) use ($termo) {
                $q->where('nome', 'like', "%{$termo}%");
            });
        }

        $vendas = $query->orderByDesc('created_at')->get();

        if ($request->ajax()) {
            return view('vendas.tabela.tabela', compact('vendas'))->render();
        }

        return view('vendas.index', compact('vendas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Cliente::all();
        $produtos = Produto::all();
        return view('vendas.create', compact('clientes', 'produtos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $baseRules = [
            'cliente_id'      => 'nullable|exists:clientes,id',
            'produtos'        => 'required|array|min:1',
            'produtos.*'      => 'required|exists:produtos,id',
            'quantidades'     => 'required|array',
            'quantidades.*'   => 'required|integer|min:1',
            'forma_pagamento' => 'required|string|in:debito,credito',
            'total'           => 'required|string',
        ];
    
        if ($request->forma_pagamento === 'credito') {
            $baseRules = array_merge($baseRules, [
                'parcelas'         => 'required|array|min:1',
                'parcelas.*'       => 'required|numeric|min:0.01',
                'datas_parcelas'   => 'required|array',
                'datas_parcelas.*' => 'required|date',
            ]);
        }
    
        $request->validate($baseRules);
    
        DB::beginTransaction();
    
        try {
            $totalInformado = floatval(str_replace(',', '.', str_replace('.', '', $request->total)));
    
            $totalCalculado = 0;
            foreach ($request->produtos as $index => $produtoId) {
                $produto = Produto::findOrFail($produtoId);
                $quantidade = $request->quantidades[$index];
                $subtotal = $produto->preco * $quantidade;
                $totalCalculado += $subtotal;
            }
    
            if (round($totalInformado, 2) !== round($totalCalculado, 2)) {
                throw new \Exception('O valor total informado não bate com o total calculado dos produtos.');
            }
    
            if ($request->forma_pagamento === 'credito') {
                $totalParcelas = array_sum(array_map(function ($valor) {
                    return floatval(str_replace(',', '.', $valor));
                }, $request->parcelas));
    
                if (round($totalParcelas, 2) !== round($totalInformado, 2)) {
                    throw new \Exception('O valor total das parcelas não bate com o total da venda.');
                }
            }
    
            $venda = Venda::create([
                'user_id'         => Auth::id(),
                'cliente_id'      => $request->cliente_id,
                'forma_pagamento' => $request->forma_pagamento,
                'total'           => $totalInformado
            ]);
    
            foreach ($request->produtos as $index => $produtoId) {
                $produto = Produto::findOrFail($produtoId);
                $quantidade = $request->quantidades[$index];
                $subtotal = $produto->preco * $quantidade;
    
                $venda->itens()->create([
                    'produto_id' => $produtoId,
                    'quantidade' => $quantidade,
                    'preco'      => $produto->preco,
                    'subtotal'   => $subtotal,
                ]);
            }
    
            if ($request->forma_pagamento === 'credito') {
                foreach ($request->datas_parcelas as $index => $data) {
                    $valorParcela = isset($request->parcelas[$index])
                        ? floatval(str_replace(',', '.', $request->parcelas[$index]))
                        : 0;
    
                    $venda->parcelas()->create([
                        'vencimento' => $data,
                        'valor'      => $valorParcela,
                    ]);
                }
            }
    
            DB::commit();
    
            return redirect()->route('vendas.index')->with('success', 'Venda cadastrada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao cadastrar venda: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Venda $venda)
    {
        $venda->load('itens.produto', 'cliente', 'usuario');
        return view('vendas.show', compact('venda'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Venda $venda)
    {
        $clientes = Cliente::all();
        $produtos = Produto::all();

        return view('vendas.edit', compact('venda', 'clientes', 'produtos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Venda $venda)
    {
        $baseRules = [
            'cliente_id'      => 'nullable|exists:clientes,id',
            'produtos'        => 'required|array|min:1',
            'produtos.*'      => 'required|exists:produtos,id',
            'quantidades'     => 'required|array',
            'quantidades.*'   => 'required|integer|min:1',
            'forma_pagamento' => 'required|string|in:debito,credito',
            'total'           => 'required|string',
        ];

        if ($request->forma_pagamento === 'credito') {
            $baseRules = array_merge($baseRules, [
                'parcelas'         => 'required|array|min:1',
                'parcelas.*'       => 'required|numeric|min:0.01',
                'datas_parcelas'   => 'required|array',
                'datas_parcelas.*' => 'required|date',
            ]);
        }

        $request->validate($baseRules);

        DB::beginTransaction();

        try {
            $totalInformado = floatval(str_replace(',', '.', str_replace('.', '', $request->total)));

            $totalCalculado = 0;
            foreach ($request->produtos as $index => $produtoId) {
                $produto = Produto::findOrFail($produtoId);
                $quantidade = $request->quantidades[$index];
                $subtotal = $produto->preco * $quantidade;
                $totalCalculado += $subtotal;
            }

            if (round($totalInformado, 2) !== round($totalCalculado, 2)) {
                throw new \Exception('O valor total informado não bate com o total calculado dos produtos.');
            }

            if ($request->forma_pagamento === 'credito') {
                $totalParcelas = array_sum(array_map(function ($valor) {
                    return floatval(str_replace(',', '.', $valor));
                }, $request->parcelas));

                if (round($totalParcelas, 2) !== round($totalInformado, 2)) {
                    throw new \Exception('O valor total das parcelas não bate com o total da venda.');
                }
            }

            $venda->update([
                'cliente_id'      => $request->cliente_id,
                'forma_pagamento' => $request->forma_pagamento,
                'total'           => $totalInformado,
            ]);

            $venda->itens()->delete();

            foreach ($request->produtos as $index => $produtoId) {
                $produto = Produto::findOrFail($produtoId);
                $quantidade = $request->quantidades[$index];
                $subtotal = $produto->preco * $quantidade;

                $venda->itens()->create([
                    'produto_id' => $produtoId,
                    'quantidade' => $quantidade,
                    'preco'      => $produto->preco,
                    'subtotal'   => $subtotal,
                ]);
            }

            $venda->parcelas()->delete();

            if ($request->forma_pagamento === 'credito') {
                foreach ($request->datas_parcelas as $index => $data) {
                    $valorParcela = isset($request->parcelas[$index])
                        ? floatval(str_replace(',', '.', $request->parcelas[$index]))
                        : 0;

                    $venda->parcelas()->create([
                        'vencimento' => $data,
                        'valor'      => $valorParcela,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('vendas.index')->with('success', 'Venda atualizada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao atualizar venda: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Venda $venda)
    {
        $venda->delete();
        return redirect()->route('vendas.index')->with('success', 'Venda excluída com sucesso.');
    }

    public function gerarPdf(Venda $venda)
    {
        $venda->load(['itens.produto', 'parcelas', 'cliente', 'usuario']);
        $itens = $venda->itens;
        $pdf = Pdf::loadView('vendas.pdf', compact('venda', 'itens'));

        return $pdf->stream('resumo-venda.pdf');
    }
}
