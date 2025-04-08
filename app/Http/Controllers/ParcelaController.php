<?php

namespace App\Http\Controllers;

use App\Models\Parcela;
use Illuminate\Http\Request;

class ParcelaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parcelas = Parcela::with('venda', 'venda.cliente')->latest()->paginate(10);
        return view('parcelas.index', compact('parcelas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Parcela $parcela)
    {
        return view('parcelas.edit', compact('parcela'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Parcela $parcela)
    {
        $request->validate([
            'data_vencimento' => 'required|date',
            'valor' => 'required|numeric',
        ]);

        $parcela->update([
            'data_vencimento' => $request->data_vencimento,
            'valor' => $request->valor,
        ]);

        return redirect()->route('parcelas.index')->with('success', 'Parcela atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Parcela $parcela)
    {
        $parcela->delete();

        return redirect()->route('parcelas.index')->with('success', 'Parcela excluída com sucesso!');
    }
}
