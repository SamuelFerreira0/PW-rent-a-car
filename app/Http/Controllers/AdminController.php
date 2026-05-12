<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Funcionario;
use App\Models\Reserva;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::with([
                'funcionario',
                'cliente' => fn($q) => $q->withCount('reservas'),
            ])
            ->orderByDesc('is_admin')
            ->orderBy('name')
            ->get();

        return view('admin.index', compact('users'));
    }

    public function promoverFuncionario($id)
    {
        $user = User::findOrFail($id);

        if ($user->isAdmin()) {
            return back()->withErrors('Não é possível alterar as permissões de um administrador.');
        }

        if ($user->funcionario) {
            return back()->withErrors('Este utilizador já é funcionário.');
        }

        do {
            $nif = str_pad(rand(1, 999999999), 9, '0', STR_PAD_LEFT);
        } while (Funcionario::where('nif', $nif)->exists());

        $funcionario = new Funcionario();
        $funcionario->nome            = $user->name;
        $funcionario->nif             = $nif;
        $funcionario->data_contratacao = now()->toDateString();
        $funcionario->id_user          = $user->id;
        $funcionario->save();

        return back()->with('success', "{$user->name} promovido a funcionário.");
    }

    public function revogarFuncionario($id)
    {
        $user = User::findOrFail($id);

        if ($user->isAdmin()) {
            return back()->withErrors('Não é possível alterar as permissões de um administrador.');
        }

        if (!$user->funcionario) {
            return back()->withErrors('Este utilizador não é funcionário.');
        }

        $user->funcionario->delete();

        return back()->with('success', "Permissões de funcionário de {$user->name} removidas.");
    }

    public function destroy($id)
    {
        $user = User::with('cliente')->findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->withErrors('Não é possível eliminar a sua própria conta.');
        }

        if ($user->isAdmin()) {
            return back()->withErrors('Não é possível eliminar um administrador.');
        }

        if ($user->cliente && Reserva::where('id_cliente', $user->cliente->id_cliente)->exists()) {
            return back()->withErrors('Não é possível eliminar este utilizador porque tem reservas associadas.');
        }

        $user->funcionario?->delete();
        $user->cliente?->delete();
        $user->delete();

        return back()->with('success', 'Utilizador eliminado.');
    }
}
