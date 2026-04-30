<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FuncionarioMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->funcionario) {
            return redirect()
                ->route('home')
                ->with('error', 'Nao tem permissao para aceder a pagina de reservas.');
        }

        return $next($request);
    }
}