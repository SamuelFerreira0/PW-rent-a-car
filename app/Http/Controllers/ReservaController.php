<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Cliente;
use App\Models\Veiculo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReservaController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isFuncionarioOrAdmin()) {
            $reservas = Reserva::with('cliente', 'veiculo')
                ->orderByRaw('CASE WHEN id_estado_reserva = ' . Reserva::ESTADO_ATIVA . ' THEN 0 ELSE 1 END')
                ->orderBy('id_reserva', 'desc')
                ->paginate(10);

            $statsAtivas     = Reserva::where('id_estado_reserva', Reserva::ESTADO_ATIVA)->count();
            $statsConcluidas = Reserva::where('id_estado_reserva', Reserva::ESTADO_CONCLUIDA)->count();
            $statsCanceladas = Reserva::where('id_estado_reserva', Reserva::ESTADO_CANCELADA)->count();
            $totalCusto      = Reserva::sum('custo_total');
        } else {
            $clienteId = $user->clienteId();
            abort_if(!$clienteId, 403, 'Perfil de cliente não encontrado.');

            $reservas = Reserva::where('id_cliente', $clienteId)
                ->with('veiculo')
                ->orderByRaw('CASE WHEN id_estado_reserva = ' . Reserva::ESTADO_ATIVA . ' THEN 0 ELSE 1 END')
                ->orderBy('id_reserva', 'desc')
                ->paginate(10);

            $statsAtivas     = Reserva::where('id_cliente', $clienteId)->where('id_estado_reserva', Reserva::ESTADO_ATIVA)->count();
            $statsConcluidas = Reserva::where('id_cliente', $clienteId)->where('id_estado_reserva', Reserva::ESTADO_CONCLUIDA)->count();
            $statsCanceladas = Reserva::where('id_cliente', $clienteId)->where('id_estado_reserva', Reserva::ESTADO_CANCELADA)->count();
            $totalCusto      = Reserva::where('id_cliente', $clienteId)->sum('custo_total');
        }

        $collection = $reservas->getCollection();
        $ativas    = $collection->filter(fn($r) => $r->isAtiva())->values();
        $historico = $collection->filter(fn($r) => !$r->isAtiva())->values();

        return view('reservas.index', compact(
            'reservas', 'ativas', 'historico',
            'statsAtivas', 'statsConcluidas', 'statsCanceladas', 'totalCusto'
        ));
    }

    public function create()
    {
        $agora = now();

        $veiculos = Veiculo::whereDoesntHave('reservas', function ($query) use ($agora) {
            $query->where('id_estado_reserva', Reserva::ESTADO_ATIVA)
                ->where(function ($q) use ($agora) {
                    $q->where('data_reserva', '<=', $agora)
                      ->where('data_prevista', '>=', $agora);
                });
        })->get();

        $clientes = auth()->user()->isFuncionarioOrAdmin()
            ? Cliente::all()
            : null;

        $veiculoPreSelecionado = request()->query('veiculo');

        return view('reservas.create', compact('clientes', 'veiculos', 'veiculoPreSelecionado'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate($this->validationRules(), $this->validationMessages());

        $dataInicio = Carbon::parse($request->data_reserva);
        $dataFim    = Carbon::parse($request->data_prevista);

        if ($dataInicio->lt(now())) {
            return back()->withInput()->withErrors('Data de início não pode ser no passado.');
        }

        if (!$user->isFuncionarioOrAdmin()) {
            abort_if(!$user->clienteId(), 403, 'Perfil de cliente não encontrado.');
        }

        DB::beginTransaction();

        try {
            if (Reserva::temConflito($request->id_veiculo, $dataInicio, $dataFim)) {
                throw new \Exception('Veículo já está reservado nesse período');
            }

            $veiculo = Veiculo::findOrFail($request->id_veiculo);
            $precoDiarioUsado = (float) $veiculo->preco_diario;

            if ($precoDiarioUsado <= 0) {
                throw new \Exception('Preço do veículo inválido.');
            }

            $custoTotal = Reserva::calcularCusto($dataInicio, $dataFim, $precoDiarioUsado);

            Reserva::create([
                'id_cliente'        => $user->isFuncionarioOrAdmin() ? $request->id_cliente : $user->clienteId(),
                'id_veiculo'        => $request->id_veiculo,
                'data_reserva'      => $dataInicio,
                'data_prevista'     => $dataFim,
                'id_estado_reserva' => Reserva::ESTADO_ATIVA,
                'preco_diario_usado' => $precoDiarioUsado,
                'custo_total'       => $custoTotal,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()->route('reservas.index')->with('success', 'Reserva criada com sucesso');
    }

    public function edit($id)
    {
        abort_unless(auth()->user()->isFuncionarioOrAdmin(), 403);

        $reserva = Reserva::findOrFail($id);

        if ($reserva->isConcluida() || $reserva->isCancelada()) {
            return back()->withErrors('Não é possível editar esta reserva.');
        }

        $clientes = Cliente::all();
        $veiculos = Veiculo::all();

        return view('reservas.edit', compact('reserva', 'clientes', 'veiculos'));
    }

    public function update(Request $request, $id)
    {
        abort_unless(auth()->user()->isFuncionarioOrAdmin(), 403);

        $reserva = Reserva::findOrFail($id);

        if ($reserva->isConcluida() || $reserva->isCancelada()) {
            return back()->withInput()->withErrors('Não é possível alterar esta reserva.');
        }

        $request->validate($this->validationRules(), $this->validationMessages());

        $dataInicio = Carbon::parse($request->data_reserva);
        $dataFim    = Carbon::parse($request->data_prevista);

        if ($dataInicio->lt(now())) {
            return back()->withInput()->withErrors('Data de início não pode ser no passado.');
        }

        $veiculo = Veiculo::findOrFail($request->id_veiculo);
        $precoDiarioUsado = (float) $reserva->preco_diario_usado;

        if ((int) $reserva->id_veiculo !== (int) $request->id_veiculo || $precoDiarioUsado <= 0) {
            $precoDiarioUsado = (float) $veiculo->preco_diario;
        }

        if ($precoDiarioUsado <= 0) {
            return back()->withInput()->withErrors('Preço do veículo inválido.');
        }

        DB::beginTransaction();

        try {
            if (Reserva::temConflito($request->id_veiculo, $dataInicio, $dataFim, $reserva->id_reserva)) {
                throw new \Exception('Veículo já está reservado nesse período');
            }

            $custoTotal = Reserva::calcularCusto($dataInicio, $dataFim, $precoDiarioUsado);

            $reserva->update([
                'id_cliente'        => $request->id_cliente,
                'id_veiculo'        => $request->id_veiculo,
                'data_reserva'      => $dataInicio,
                'data_prevista'     => $dataFim,
                'preco_diario_usado' => $precoDiarioUsado,
                'custo_total'       => $custoTotal,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()->route('reservas.index')->with('success', 'Reserva atualizada');
    }

    public function concluir($id)
    {
        abort_unless(auth()->user()->isFuncionarioOrAdmin(), 403);

        $reserva = Reserva::findOrFail($id);

        if ($reserva->isConcluida() || $reserva->isCancelada()) {
            return redirect()->route('reservas.index')->withErrors('Não é possível concluir esta reserva.');
        }

        $reserva->id_estado_reserva = Reserva::ESTADO_CONCLUIDA;
        $reserva->save();

        return redirect()->route('reservas.index')->with('success', 'Reserva concluída');
    }

    public function cancelar($id)
    {
        $user    = auth()->user();
        $reserva = Reserva::findOrFail($id);

        if (!$user->isFuncionarioOrAdmin()) {
            $clienteId = $user->clienteId();
            abort_if(!$clienteId || $reserva->id_cliente !== $clienteId, 403);
        }

        if (!$reserva->isAtiva()) {
            return back()->withErrors('Só é possível cancelar reservas ativas.');
        }

        $reserva->id_estado_reserva = Reserva::ESTADO_CANCELADA;
        $reserva->save();

        return redirect()->route('reservas.index')->with('success', 'Reserva cancelada.');
    }

    public function show($id)
    {
        $user    = auth()->user();
        $reserva = Reserva::with('veiculo', 'cliente')->findOrFail($id);

        if (!$user->isFuncionarioOrAdmin()) {
            $clienteId = $user->clienteId();
            abort_if(!$clienteId || $reserva->id_cliente !== $clienteId, 403, 'Acesso não autorizado.');
        }

        return view('reservas.show', compact('reserva'));
    }

    public function checkDisponibilidade(Request $request)
    {
        $request->validate([
            'id_veiculo'   => 'required|exists:veiculo,id_veiculo',
            'data_reserva' => 'required|date',
            'data_prevista' => 'required|date|after:data_reserva',
        ]);

        $conflito = Reserva::temConflito(
            $request->id_veiculo,
            $request->data_reserva,
            $request->data_prevista
        );

        return response()->json(['disponivel' => !$conflito]);
    }

    public function preview(Request $request)
    {
        $request->validate([
            'id_veiculo'   => 'required|exists:veiculo,id_veiculo',
            'data_reserva' => 'required|date',
            'data_prevista' => 'required|date|after:data_reserva',
        ]);

        try {
            $veiculo = Veiculo::findOrFail($request->id_veiculo);
            $custo   = Reserva::calcularCusto(
                $request->data_reserva,
                $request->data_prevista,
                $veiculo->preco_diario
            );

            return response()->json(['success' => true, 'custo' => $custo]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    private function validationRules(): array
    {
        $rules = [
            'id_veiculo'   => 'required|exists:veiculo,id_veiculo',
            'data_reserva' => 'required|date',
            'data_prevista' => 'required|date|after:data_reserva',
        ];

        if (auth()->user()->isFuncionarioOrAdmin()) {
            $rules['id_cliente'] = 'required|exists:cliente,id_cliente';
        }

        return $rules;
    }

    private function validationMessages(): array
    {
        return [
            'id_cliente.required'   => 'Tem de selecionar um cliente.',
            'id_veiculo.required'   => 'Tem de selecionar um veículo.',
            'data_reserva.required' => 'A data de início é obrigatória.',
            'data_prevista.required' => 'A data de fim é obrigatória.',
            'data_prevista.after'   => 'A data de fim tem de ser depois da data de início.',
        ];
    }
}
