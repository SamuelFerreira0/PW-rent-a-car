<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsFuncionario
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->funcionario) {
            abort(403, 'Acesso não autorizado.');
        }

        return $next($request);
    }
}
