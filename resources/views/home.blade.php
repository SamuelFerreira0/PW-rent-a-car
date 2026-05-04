<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Rent a Car') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen" style="background:#f8f9fb; margin:0;">

{{-- NAVBAR --}}
<nav class="bg-white border-b border-slate-200">
    <div class="max-w-2xl mx-auto px-4 h-14 flex items-center justify-between">

        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" style="background:#1e40af;">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M5 17H3a2 2 0 01-2-2v-4l3-6h12l3 6v4a2 2 0 01-2 2h-2M5 17a2 2 0 104 0 2 2 0 00-4 0zm10 0a2 2 0 104 0 2 2 0 00-4 0z"/>
                </svg>
            </div>
            <span class="text-sm font-bold text-slate-900 tracking-tight">Rent a Car</span>
        </div>

        <div class="flex items-center gap-2">
            @guest
                <a href="{{ route('login') }}"
                   class="text-xs font-semibold text-slate-500 hover:text-slate-900 border border-slate-200 hover:border-slate-300 bg-white px-4 py-2 rounded-xl transition-colors">
                    Login
                </a>
                <a href="{{ route('register') }}"
                   class="text-xs font-semibold text-white px-4 py-2 rounded-xl transition-colors"
                   style="background:#1e40af;">
                    Registar
                </a>
            @endguest

            @auth
                <a href="{{ url('/reservas') }}"
                   class="text-xs font-semibold text-slate-500 hover:text-slate-900 hover:bg-slate-100 px-3 py-2 rounded-lg transition-colors">
                    Reservas
                </a>

                @if(auth()->user()->funcionario)
                    <a href="{{ url('/veiculos') }}"
                       class="text-xs font-semibold text-slate-500 hover:text-slate-900 hover:bg-slate-100 px-3 py-2 rounded-lg transition-colors">
                        Veículos
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="text-xs font-semibold text-red-500 hover:text-red-700 hover:bg-red-50 px-3 py-2 rounded-lg transition-colors">
                        Logout
                    </button>
                </form>
            @endauth
        </div>

    </div>
</nav>

{{-- CONTEÚDO --}}
<div class="max-w-2xl mx-auto px-4 py-8">

    {{-- ALERTA SUCCESS --}}
    @if(session('success'))
    <div class="mb-6 px-4 py-3 rounded-xl bg-green-50 border border-green-100 text-sm text-green-700 font-medium">
        {{ session('success') }}
    </div>
    @endif

    @guest
    {{-- ===== VISTA GUEST ===== --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden mb-4">
        <div class="p-8 text-center">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:#eff6ff;">
                <svg class="w-6 h-6" fill="none" stroke="#1e40af" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M5 17H3a2 2 0 01-2-2v-4l3-6h12l3 6v4a2 2 0 01-2 2h-2M5 17a2 2 0 104 0 2 2 0 00-4 0zm10 0a2 2 0 104 0 2 2 0 00-4 0z"/>
                </svg>
            </div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight mb-1">Bem-vindo ao Rent a Car</h1>
            <p class="text-sm text-slate-400 mb-6">Faz login para aceder ao sistema de reservas.</p>
            <div class="flex items-center justify-center gap-3">
                <a href="{{ route('login') }}"
                   class="text-[13px] font-semibold text-white px-6 py-2.5 rounded-xl"
                   style="background:#1e40af;">
                    Entrar
                </a>
                <a href="{{ route('register') }}"
                   class="text-[13px] font-semibold text-slate-500 border border-slate-200 bg-white px-6 py-2.5 rounded-xl hover:border-slate-300 transition-colors">
                    Criar conta
                </a>
            </div>
        </div>
    </div>
    @endguest

    @auth
    {{-- ===== VISTA AUTENTICADO ===== --}}

    {{-- BOAS-VINDAS --}}
    <div class="flex justify-between items-center mb-7">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">
                Olá, {{ auth()->user()->name }}
            </h1>
            <p class="text-sm text-slate-400 mt-0.5">
                {{ auth()->user()->funcionario ? 'Painel de gestão' : 'O teu painel de reservas' }}
            </p>
        </div>

        @if(!auth()->user()->funcionario)
            <a href="/reservas/create"
               class="inline-flex items-center gap-1.5 text-white text-[13px] font-semibold px-4 py-2.5 rounded-xl"
               style="background:#1e40af;">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Nova reserva
            </a>
        @endif
    </div>

    {{-- AÇÕES RÁPIDAS --}}
    <div class="grid gap-3 mb-6" style="grid-template-columns: repeat({{ auth()->user()->funcionario ? '2' : '1' }}, 1fr);">

        <a href="/reservas"
           class="bg-white rounded-2xl border border-slate-200 p-5 hover:border-slate-300 transition-colors flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#eff6ff;">
                <svg class="w-5 h-5" fill="none" stroke="#1e40af" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <p class="text-[13px] font-semibold text-slate-900">
                    {{ auth()->user()->funcionario ? 'Gerir reservas' : 'As minhas reservas' }}
                </p>
                <p class="text-xs text-slate-400 mt-0.5">
                    {{ auth()->user()->funcionario ? 'Ver e gerir todas as reservas' : 'Ver e acompanhar as tuas reservas' }}
                </p>
            </div>
            <svg class="w-4 h-4 text-slate-300 ml-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 18l6-6-6-6"/>
            </svg>
        </a>

        @if(auth()->user()->funcionario)
        <a href="/veiculos"
           class="bg-white rounded-2xl border border-slate-200 p-5 hover:border-slate-300 transition-colors flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#eff6ff;">
                <svg class="w-5 h-5" fill="none" stroke="#1e40af" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M5 17H3a2 2 0 01-2-2v-4l3-6h12l3 6v4a2 2 0 01-2 2h-2M5 17a2 2 0 104 0 2 2 0 00-4 0zm10 0a2 2 0 104 0 2 2 0 00-4 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-[13px] font-semibold text-slate-900">Gerir veículos</p>
                <p class="text-xs text-slate-400 mt-0.5">Adicionar, editar e remover veículos</p>
            </div>
            <svg class="w-4 h-4 text-slate-300 ml-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 18l6-6-6-6"/>
            </svg>
        </a>
        @endif

    </div>

    {{-- STATS DASHBOARD --}}
    @if(auth()->user()->funcionario)

    <div class="grid gap-3 mb-6" style="grid-template-columns: repeat(4, 1fr);">

        <div class="bg-white rounded-2xl border border-slate-200 p-4">
            <p class="text-xs text-slate-400">Total Reservas</p>
            <p class="text-xl font-bold text-slate-900">{{ $total ?? 0 }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-4">
            <p class="text-xs text-slate-400">Ativas</p>
            <p class="text-xl font-bold text-green-600">{{ $ativas ?? 0 }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-4">
            <p class="text-xs text-slate-400">Concluídas</p>
            <p class="text-xl font-bold text-slate-900">{{ $concluidas ?? 0 }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-4">
            <p class="text-xs text-slate-400">Receita</p>
            <p class="text-xl font-bold text-blue-600">
                € {{ number_format($receita ?? 0, 2, ',', '.') }}
            </p>
        </div>

    </div>

    @else

    <div class="grid gap-3 mb-6">

        <div class="bg-white rounded-2xl border border-slate-200 p-4">
            <p class="text-xs text-slate-400">Total de Reservas</p>
            <p class="text-xl font-bold text-slate-900">{{ $total ?? 0 }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-4">
            <p class="text-xs text-slate-400 mb-1">Próxima Reserva</p>

            @if(isset($proxima) && $proxima)
                <p class="font-semibold text-slate-900">
                    {{ \Carbon\Carbon::parse($proxima->data_reserva)->format('d/m/Y H:i') }}
                </p>
                <p class="text-xs text-slate-400">
                    até {{ \Carbon\Carbon::parse($proxima->data_prevista)->format('d/m/Y H:i') }}
                </p>
            @else
                <p class="text-slate-400 text-sm">
                    Não tens reservas futuras
                </p>
            @endif
        </div>

    </div>

    @endif

    {{-- FEATURES --}}
    <div class="grid gap-3" style="grid-template-columns: repeat(3, 1fr);">

        <div class="bg-white rounded-2xl border border-slate-200 p-4">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center mb-3" style="background:#eff6ff;">
                <svg class="w-4 h-4" fill="none" stroke="#1e40af" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <p class="text-[13px] font-semibold text-slate-800">Criação rápida</p>
            <p class="text-xs text-slate-400 mt-0.5">Registo de reservas em segundos</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-4">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center mb-3" style="background:#eff6ff;">
                <svg class="w-4 h-4" fill="none" stroke="#1e40af" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
            </div>
            <p class="text-[13px] font-semibold text-slate-800">Validação de datas</p>
            <p class="text-xs text-slate-400 mt-0.5">Controlo automático de conflitos</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-4">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center mb-3" style="background:#eff6ff;">
                <svg class="w-4 h-4" fill="none" stroke="#1e40af" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-[13px] font-semibold text-slate-800">Gestão de estado</p>
            <p class="text-xs text-slate-400 mt-0.5">Acompanhamento em tempo real</p>
        </div>

    </div>

    @endauth

    {{-- FEATURES PARA GUEST --}}
    @guest
    <div class="grid gap-3" style="grid-template-columns: repeat(3, 1fr);">
        <div class="bg-white rounded-2xl border border-slate-200 p-4">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center mb-3" style="background:#eff6ff;">
                <svg class="w-4 h-4" fill="none" stroke="#1e40af" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <p class="text-[13px] font-semibold text-slate-800">Criação rápida</p>
            <p class="text-xs text-slate-400 mt-0.5">Registo de reservas em segundos</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-4">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center mb-3" style="background:#eff6ff;">
                <svg class="w-4 h-4" fill="none" stroke="#1e40af" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
            </div>
            <p class="text-[13px] font-semibold text-slate-800">Validação de datas</p>
            <p class="text-xs text-slate-400 mt-0.5">Controlo automático de conflitos</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-4">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center mb-3" style="background:#eff6ff;">
                <svg class="w-4 h-4" fill="none" stroke="#1e40af" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-[13px] font-semibold text-slate-800">Gestão de estado</p>
            <p class="text-xs text-slate-400 mt-0.5">Acompanhamento em tempo real</p>
        </div>
    </div>
    @endguest

</div>

</body>
</html>