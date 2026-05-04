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
        if (auth()->user()->funcionario) {
            $reservas = Reserva::with('cliente', 'veiculo')
                ->orderBy('id_reserva', 'desc')
                ->paginate(10);
        } else {
            $reservas = Reserva::where('id_cliente', auth()->user()->cliente->id_cliente)
                ->with('veiculo')
                ->orderBy('id_reserva', 'desc')
                ->paginate(10);
        }
    
        return view('reservas.index', compact('reservas'));
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
    
        $clientes = auth()->user()->funcionario
            ? Cliente::all()
            : null;
    
        return view('reservas.create', compact('clientes', 'veiculos'));
    }

    public function store(Request $request)
    {
        $request->validate($this->validationRules(), $this->validationMessages());

        $dataInicio = Carbon::parse($request->data_reserva);
        $dataFim = Carbon::parse($request->data_prevista);

        if ($dataInicio->lt(now())) {
            return back()->withInput()->withErrors('Data de início não pode ser no passado.');
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
                'id_cliente' => auth()->user()->funcionario
                ? $request->id_cliente
                    : auth()->user()->cliente->id_cliente,
                'id_veiculo' => $request->id_veiculo,
                'data_reserva' => $dataInicio,
                'data_prevista' => $dataFim,
                'id_estado_reserva' => Reserva::ESTADO_ATIVA,
                'preco_diario_usado' => $precoDiarioUsado,
                'custo_total' => $custoTotal,
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors($e->getMessage());
        }

        return redirect('/reservas')->with('success', 'Reserva criada com sucesso');
    }

    public function edit($id)
    {
        if (!auth()->user()->funcionario) {
            abort(403);
        }

        $reserva = Reserva::findOrFail($id);

        if ($reserva->isConcluida()) {
            return back()->withErrors('Não é possível editar uma reserva concluída.');
        }

        $clientes = Cliente::all();
        $veiculos = Veiculo::all();

        return view('reservas.edit', compact('reserva', 'clientes', 'veiculos'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->funcionario) {
            abort(403);
        }
        
        $reserva = Reserva::findOrFail($id);

        if ($reserva->isConcluida()) {
            return back()->withInput()->withErrors('Não é possível alterar uma reserva concluída.');
        }

        $request->validate($this->validationRules(), $this->validationMessages());

        $dataInicio = Carbon::parse($request->data_reserva);
        $dataFim = Carbon::parse($request->data_prevista);

        if ($dataInicio->lt(now())) {
            return back()->withInput()->withErrors('Data de início não pode ser no passado.');
        }

        if (Reserva::temConflito(
            $request->id_veiculo,
            $dataInicio,
            $dataFim,
            $reserva->id_reserva
        )) {
            return back()->withInput()->withErrors('Veículo já está reservado nesse período');
        }

        $veiculo = Veiculo::findOrFail($request->id_veiculo);

        $precoDiarioUsado = (float) $reserva->preco_diario_usado;

        if ((int) $reserva->id_veiculo !== (int) $request->id_veiculo || $precoDiarioUsado <= 0) {
            $precoDiarioUsado = (float) $veiculo->preco_diario;
        }

        if ($precoDiarioUsado <= 0) {
            return back()->withInput()->withErrors('Preço do veículo inválido.');
        }

        try {
            $custoTotal = Reserva::calcularCusto($dataInicio, $dataFim, $precoDiarioUsado);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors('Erro ao calcular custo da reserva.');
        }

        $reserva->update([
            'id_cliente' => auth()->user()->funcionario
                ? $request->id_cliente
                : auth()->user()->cliente->id_cliente,
            'id_veiculo' => $request->id_veiculo,
            'data_reserva' => $dataInicio,
            'data_prevista' => $dataFim,
            'preco_diario_usado' => $precoDiarioUsado,
            'custo_total' => $custoTotal,
        ]);

        return redirect('/reservas')->with('success', 'Reserva atualizada');
    }

    public function destroy($id)
    {
        if (!auth()->user()->funcionario) {
            abort(403);
        }

        $reserva = Reserva::findOrFail($id);

        if ($reserva->isConcluida()) {
            return back()->withErrors('Não é possível eliminar uma reserva concluída.');
        }

        $reserva->delete();

        return redirect('/reservas')->with('success', 'Reserva eliminada');
    }

    public function concluir($id)
    {
        if (!auth()->user()->funcionario) {
            abort(403);
        }

        $reserva = Reserva::findOrFail($id);

        if ($reserva->isConcluida()) {
            return redirect('/reservas')->withErrors('A reserva já está concluída');
        }

        $reserva->id_estado_reserva = Reserva::ESTADO_CONCLUIDA;
        $reserva->save();

        return redirect('/reservas')->with('success', 'Reserva concluída');
    }

    private function validationRules(): array
    {
        $rules = [
            'id_veiculo' => 'required|exists:veiculo,id_veiculo',
            'data_reserva' => 'required|date',
            'data_prevista' => 'required|date|after:data_reserva',
        ];
    
        if (auth()->user()->funcionario) {
            $rules['id_cliente'] = 'required|exists:cliente,id_cliente';
        }
    
        return $rules;
    }

    private function validationMessages(): array
    {
        return [
            'id_cliente.required' => 'Tem de selecionar um cliente.',
            'id_veiculo.required' => 'Tem de selecionar um veículo.',
            'data_reserva.required' => 'A data de início é obrigatória.',
            'data_prevista.required' => 'A data de fim é obrigatória.',
            'data_prevista.after' => 'A data de fim tem de ser depois da data de início.',
        ];
    }

    public function checkDisponibilidade(Request $request)
    {
        $request->validate([
            'id_veiculo' => 'required',
            'data_reserva' => 'required|date',
            'data_prevista' => 'required|date',
        ]);

        $conflito = Reserva::temConflito(
            $request->id_veiculo,
            $request->data_reserva,
            $request->data_prevista
        );

        return response()->json([
            'disponivel' => !$conflito
        ]);
    }

    public function preview(Request $request)
    {
        try {
            $dataInicio = $request->data_reserva;
            $dataFim = $request->data_prevista;
    
            $veiculo = Veiculo::findOrFail($request->id_veiculo);
    
            $custo = Reserva::calcularCusto(
                $dataInicio,
                $dataFim,
                $veiculo->preco_diario
            );
    
            return response()->json([
                'success' => true,
                'custo' => $custo
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function show($id)
    {
        $reserva = Reserva::with('veiculo', 'cliente')->findOrFail($id);

        if (!auth()->user()->funcionario &&
            $reserva->id_cliente !== auth()->user()->cliente->id_cliente) {
            abort(403, 'Acesso não autorizado.');
        }

        return view('reservas.show', compact('reserva'));
    }
}