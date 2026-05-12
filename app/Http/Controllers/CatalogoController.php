<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;

class CatalogoController extends Controller
{
    public function index()
    {
        $veiculos = Veiculo::with(['categoria', 'estadoVeiculo', 'localizacao'])
            ->orderBy('marca')
            ->orderBy('modelo')
            ->get();

        return view('catalogo.index', compact('veiculos'));
    }
}
