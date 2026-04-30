<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Veiculo;
use App\Models\Categoria;
use App\Models\EstadoVeiculo;
use App\Models\Localizacao;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VeiculoController extends Controller
{
    public function index()
    {
        $veiculos = Veiculo::with(['categoria', 'estadoVeiculo', 'localizacao'])
            ->orderBy('id_veiculo', 'desc')
            ->paginate(10);

        return view('veiculos.index', compact('veiculos'));
    }

    public function create()
    {
        $categorias = Categoria::all();
        $estadosVeiculo = EstadoVeiculo::all();
        $localizacoes = Localizacao::all();

        return view('veiculos.create', compact('categorias', 'estadosVeiculo', 'localizacoes'));
    }

    public function store(Request $request)
    {
        $dadosValidados = $request->validate(
            $this->validationRules(),
            $this->validationMessages()
        );

        Veiculo::create($dadosValidados);

        return redirect('/veiculos')->with('success', 'Veiculo criado com sucesso');
    }

    public function edit($id_veiculo)
    {
        $veiculo = Veiculo::findOrFail($id_veiculo);
        $categorias = Categoria::all();
        $estadosVeiculo = EstadoVeiculo::all();
        $localizacoes = Localizacao::all();

        return view('veiculos.edit', compact('veiculo', 'categorias', 'estadosVeiculo', 'localizacoes'));
    }

    public function update(Request $request, $id_veiculo)
    {
        $dadosValidados = $request->validate(
            $this->validationRules($id_veiculo),
            $this->validationMessages()
        );

        $veiculo = Veiculo::findOrFail($id_veiculo);
        $veiculo->update($dadosValidados);

        return redirect('/veiculos')->with('success', 'Veiculo atualizado com sucesso');
    }

    public function destroy($id_veiculo)
    {
        $veiculo = Veiculo::findOrFail($id_veiculo);

        $temReservas = Reserva::where('id_veiculo', $id_veiculo)->exists();

        if ($temReservas) {
            return redirect('/veiculos')->withErrors('Nao e possivel eliminar um veiculo com reservas associadas.');
        }

        $veiculo->delete();

        return redirect('/veiculos')->with('success', 'Veiculo eliminado com sucesso');
    }

    private function validationRules(?int $idVeiculo = null): array
    {
        $matriculaRule = $idVeiculo
            ? Rule::unique('veiculo', 'matricula')->ignore($idVeiculo, 'id_veiculo')
            : 'unique:veiculo,matricula';

        return [
            'matricula' => ['required', 'string', 'max:20', $matriculaRule],
            'marca' => 'required',
            'modelo' => 'required',
            'preco_diario' => 'required|numeric|gt:0',
            'id_categoria' => 'required|exists:categoria,id_categoria',
            'id_estado_veiculo' => 'required|exists:estado_veiculo,id_estado_veiculo',
            'id_localizacao' => 'required|exists:localizacao,id_localizacao',
        ];
    }

    private function validationMessages(): array
    {
        return [
            'matricula.required' => 'A matricula e obrigatoria.',
            'matricula.max' => 'A matricula nao pode ter mais de 20 caracteres.',
            'matricula.unique' => 'Ja existe um veiculo com esta matricula.',
        ];
    }
}