<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Rent a Car') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body style="background: #f8fafc; margin: 0; font-family: Arial, sans-serif; color: #1f2937;">

<div style="max-width: 980px; margin: 36px auto; padding: 0 18px;">

    <!-- HEADER -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; flex-wrap:wrap;">

        <h1 style="font-size:30px; font-weight:700;">Rent a Car</h1>

        <div style="display:flex; gap:10px; flex-wrap:wrap;">

            @guest
                <a href="{{ route('login') }}" style="background:#2563eb;color:#fff;padding:10px 16px;border-radius:10px;">
                    Login
                </a>

                <a href="{{ route('register') }}" style="border:1px solid #ccc;padding:10px 16px;border-radius:10px;">
                    Register
                </a>
            @endguest

            @auth
                <a href="{{ url('/reservas') }}" style="background:#2563eb;color:#fff;padding:10px 16px;border-radius:10px;">
                    Reservas
                </a>

                @if(auth()->user()->funcionario)
                    <a href="{{ url('/veiculos') }}" style="background:#2563eb;color:#fff;padding:10px 16px;border-radius:10px;">
                        Veículos
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" style="background:#ef4444;color:#fff;padding:10px 16px;border-radius:10px;border:none;">
                        Logout
                    </button>
                </form>
            @endauth

        </div>
    </div>

    <!-- ALERTAS -->
    @if (session('success'))
        <div style="background:#dcfce7;padding:10px;border-radius:10px;margin-bottom:10px;">
            {{ session('success') }}
        </div>
    @endif

    <!-- CONTEÚDO PRINCIPAL -->
    <div style="background:white;padding:20px;border-radius:12px;margin-bottom:20px;">

        @guest
            <h2>Bem-vindo</h2>
            <p>Faça login para usar o sistema.</p>

            <a href="{{ route('login') }}" style="background:#2563eb;color:#fff;padding:10px 16px;border-radius:10px;">
                Entrar
            </a>
        @endguest

        @auth

            @if(auth()->user()->funcionario)
                <h2>Bem-vindo, {{ auth()->user()->name }}</h2>
                <p>Gestão completa do sistema.</p>

                <a href="/reservas" style="background:#2563eb;color:#fff;padding:10px 16px;border-radius:10px;">
                    Ir para Reservas
                </a>

            @else
                <h2>Bem-vindo, {{ auth()->user()->name }}</h2>
                <p>Pode gerir as suas reservas.</p>

                <div style="display:flex; gap:10px;">
                    <a href="/reservas" style="background:#2563eb;color:#fff;padding:10px 16px;border-radius:10px;">
                        Minhas Reservas
                    </a>

                    <a href="/reservas/create" style="background:#16a34a;color:#fff;padding:10px 16px;border-radius:10px;">
                        Nova Reserva
                    </a>
                </div>
            @endif

        @endauth

    </div>

    <!-- CARDS -->
    <div style="display:grid; gap:14px; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));">

        <div style="background:white;padding:16px;border-radius:12px;">
            <h3>Criação de reservas</h3>
            <p>Registo rápido.</p>
        </div>

        <div style="background:white;padding:16px;border-radius:12px;">
            <h3>Validação de datas</h3>
            <p>Controlo de conflitos.</p>
        </div>

        <div style="background:white;padding:16px;border-radius:12px;">
            <h3>Gestão de estado</h3>
            <p>Acompanhamento das reservas.</p>
        </div>

        @auth
            @if(auth()->user()->funcionario)
                <div style="background:white;padding:16px;border-radius:12px;">
                    <h3>Gestão de Veículos</h3>
                    <a href="/veiculos" style="background:#2563eb;color:#fff;padding:8px 12px;border-radius:8px;">
                        Gerir
                    </a>
                </div>
            @endif
        @endauth

    </div>

</div>

</body>
</html>