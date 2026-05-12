<?php

namespace App\Http\Controllers;

use App\Models\Reserva;

class HomeController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return view('home');
        }

        $user = auth()->user();

        if ($user->isFuncionarioOrAdmin()) {
            $total      = Reserva::count();
            $ativas     = Reserva::where('id_estado_reserva', Reserva::ESTADO_ATIVA)->count();
            $concluidas = Reserva::where('id_estado_reserva', Reserva::ESTADO_CONCLUIDA)->count();
            $canceladas = Reserva::where('id_estado_reserva', Reserva::ESTADO_CANCELADA)->count();
            $receita    = Reserva::sum('custo_total');

            return view('home', compact('total', 'ativas', 'concluidas', 'canceladas', 'receita'));
        }

        $clienteId = $user->clienteId();

        if (!$clienteId) {
            return view('home', ['total' => 0, 'proxima' => null]);
        }

        $total = Reserva::where('id_cliente', $clienteId)->count();

        $proxima = Reserva::where('id_cliente', $clienteId)
            ->where('id_estado_reserva', Reserva::ESTADO_ATIVA)
            ->where('data_reserva', '>', now())
            ->orderBy('data_reserva')
            ->first();

        return view('home', compact('total', 'proxima'));
    }
}
