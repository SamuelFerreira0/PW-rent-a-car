<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Rent a Car') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased min-h-screen" style="background:#f8f9fb; margin:0;">

@auth
    @include('layouts.navigation')
@else
    {{-- Nav simplificado para guests --}}
    <nav class="bg-white border-b border-slate-200 shadow-[0_1px_3px_rgba(0,0,0,0.06)]">
        <div class="max-w-4xl mx-auto px-4 h-14 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0"
                         style="background:linear-gradient(135deg,#2563eb,#1e40af); box-shadow:0 2px 6px rgba(30,64,175,0.35);">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M5 17H3a2 2 0 01-2-2v-4l3-6h12l3 6v4a2 2 0 01-2 2h-2M5 17a2 2 0 104 0 2 2 0 00-4 0zm10 0a2 2 0 104 0 2 2 0 00-4 0z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-bold text-slate-900 tracking-tight">Rent a Car</span>
                </a>
                <a href="{{ route('catalogo.index') }}"
                   class="text-xs font-semibold px-3 py-1.5 rounded-lg transition-all duration-150 text-slate-500 hover:text-slate-900 hover:bg-slate-100">
                    Catálogo
                </a>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('login') }}"
                   class="text-xs font-semibold text-slate-600 hover:text-slate-900 border border-slate-200 hover:border-slate-300 bg-white px-4 py-2 rounded-xl transition-all duration-150">
                    Login
                </a>
                <a href="{{ route('register') }}"
                   class="text-xs font-semibold text-white px-4 py-2 rounded-xl transition-all duration-150 hover:-translate-y-px"
                   style="background:linear-gradient(135deg,#2563eb 0%,#1e40af 100%); box-shadow:0 2px 8px rgba(30,64,175,0.25);">
                    Registar
                </a>
            </div>
        </div>
    </nav>
@endauth

@guest
{{-- ===== HERO GUEST ===== --}}
<div style="background:linear-gradient(135deg,#0f172a 0%,#1e3a8a 60%,#1d4ed8 100%);">
    <div class="max-w-2xl mx-auto px-4 py-20 text-center">

        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full mb-6 text-xs font-semibold text-blue-200"
             style="background:rgba(255,255,255,0.1);">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>
            Sistema de gestão de frotas
        </div>

        <h1 class="text-4xl font-bold text-white tracking-tight leading-tight mb-4">
            Bem-vindo ao<br>Rent a Car
        </h1>
        <p class="text-blue-200 text-base mb-8 max-w-md mx-auto leading-relaxed">
            Reservas, veículos e utilizadores num só lugar. Controlo total da tua frota.
        </p>

        <div class="flex items-center justify-center gap-3">
            <a href="{{ route('catalogo.index') }}"
               class="inline-flex items-center text-sm font-semibold text-blue-900 bg-white px-6 py-3 rounded-xl transition-all duration-150 hover:-translate-y-px"
               style="box-shadow:0 4px 12px rgba(0,0,0,0.15);">
                Ver veículos
            </a>
            <a href="{{ route('login') }}"
               class="inline-flex items-center text-sm font-semibold text-white px-6 py-3 rounded-xl transition-all duration-150 hover:-translate-y-px"
               style="background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.2);">
                Entrar
            </a>
        </div>

    </div>
</div>

<div class="max-w-4xl mx-auto px-4 py-12">
    <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-4 text-center">O que inclui</p>
    <div class="grid gap-3 grid-cols-1 sm:grid-cols-3">
        <div class="bg-white rounded-2xl border border-slate-200 p-5"
             style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 1px 2px rgba(0,0,0,0.04);">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center mb-3" style="background:#eff6ff;">
                <svg class="w-4 h-4" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <p class="text-[13px] font-semibold text-slate-800">Criação rápida</p>
            <p class="text-xs text-slate-400 mt-0.5">Registo de reservas em segundos</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-5"
             style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 1px 2px rgba(0,0,0,0.04);">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center mb-3" style="background:#eff6ff;">
                <svg class="w-4 h-4" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
            </div>
            <p class="text-[13px] font-semibold text-slate-800">Validação de datas</p>
            <p class="text-xs text-slate-400 mt-0.5">Controlo automático de conflitos</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-5"
             style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 1px 2px rgba(0,0,0,0.04);">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center mb-3" style="background:#eff6ff;">
                <svg class="w-4 h-4" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-[13px] font-semibold text-slate-800">Gestão de estado</p>
            <p class="text-xs text-slate-400 mt-0.5">Acompanhamento em tempo real</p>
        </div>
    </div>
</div>
@endguest

@auth
{{-- ===== DASHBOARD AUTENTICADO ===== --}}
<div class="max-w-4xl mx-auto px-4 py-8">

    @if(session('success'))
    <div class="mb-6 px-4 py-3 rounded-xl bg-green-50 border border-green-100 text-sm text-green-700 font-medium">
        {{ session('success') }}
    </div>
    @endif

    {{-- BOAS-VINDAS --}}
    <div class="flex justify-between items-start mb-7">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">
                Olá, {{ auth()->user()->name }}
            </h1>
            <p class="text-sm text-slate-400 mt-0.5">
                @if(auth()->user()->isFuncionarioOrAdmin())
                    Painel de gestão · {{ $total ?? 0 }} reservas no sistema
                @else
                    O teu painel de reservas
                @endif
            </p>
        </div>

        @if(!auth()->user()->isFuncionarioOrAdmin())
            <a href="{{ route('reservas.create') }}"
               class="inline-flex items-center gap-1.5 text-white text-[13px] font-semibold px-4 py-2.5 rounded-xl transition-all duration-150 hover:-translate-y-px flex-shrink-0"
               style="background:linear-gradient(135deg,#2563eb 0%,#1e40af 100%); box-shadow:0 2px 8px rgba(30,64,175,0.25);">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Nova reserva
            </a>
        @endif
    </div>

    {{-- AÇÕES RÁPIDAS --}}
    @php
        $gridCols = auth()->user()->isAdmin()
            ? 'grid-cols-1 sm:grid-cols-3'
            : (auth()->user()->isFuncionarioOrAdmin() ? 'grid-cols-1 sm:grid-cols-2' : 'grid-cols-1');
    @endphp
    <div class="grid gap-3 mb-6 {{ $gridCols }}">

        <a href="{{ route('reservas.index') }}"
           class="bg-white rounded-2xl border border-slate-200 p-5 transition-all duration-150 hover:-translate-y-px flex items-center gap-4"
           style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 1px 2px rgba(0,0,0,0.04);">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#eff6ff;">
                <svg class="w-5 h-5" fill="none" stroke="#2563eb" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <p class="text-[13px] font-semibold text-slate-900">
                    {{ auth()->user()->isFuncionarioOrAdmin() ? 'Gerir reservas' : 'As minhas reservas' }}
                </p>
                <p class="text-xs text-slate-400 mt-0.5">
                    {{ auth()->user()->isFuncionarioOrAdmin() ? 'Ver e gerir todas as reservas' : 'Ver e acompanhar as tuas reservas' }}
                </p>
            </div>
            <svg class="w-4 h-4 text-slate-300 ml-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 18l6-6-6-6"/>
            </svg>
        </a>

        @if(auth()->user()->isFuncionarioOrAdmin())
        <a href="{{ route('veiculos.index') }}"
           class="bg-white rounded-2xl border border-slate-200 p-5 transition-all duration-150 hover:-translate-y-px flex items-center gap-4"
           style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 1px 2px rgba(0,0,0,0.04);">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#eff6ff;">
                <svg class="w-5 h-5" fill="none" stroke="#2563eb" stroke-width="1.8" viewBox="0 0 24 24">
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

        @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.index') }}"
           class="bg-white rounded-2xl border border-slate-200 p-5 transition-all duration-150 hover:-translate-y-px flex items-center gap-4"
           style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 1px 2px rgba(0,0,0,0.04);">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#eff6ff;">
                <svg class="w-5 h-5" fill="none" stroke="#2563eb" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zM23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                </svg>
            </div>
            <div>
                <p class="text-[13px] font-semibold text-slate-900">Gerir utilizadores</p>
                <p class="text-xs text-slate-400 mt-0.5">Promover, revogar e eliminar contas</p>
            </div>
            <svg class="w-4 h-4 text-slate-300 ml-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 18l6-6-6-6"/>
            </svg>
        </a>
        @endif

    </div>

    {{-- STATS --}}
    @if(auth()->user()->isFuncionarioOrAdmin())

    <div class="grid gap-3 mb-3 grid-cols-1 sm:grid-cols-3">
        <div class="bg-white rounded-2xl border border-slate-200 p-4"
             style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 1px 2px rgba(0,0,0,0.04);">
            <p class="text-[10px] uppercase tracking-widest text-slate-400 font-semibold mb-1">Ativas</p>
            <p class="text-xl font-bold text-emerald-600">{{ $ativas ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-4"
             style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 1px 2px rgba(0,0,0,0.04);">
            <p class="text-[10px] uppercase tracking-widest text-slate-400 font-semibold mb-1">Concluídas</p>
            <p class="text-xl font-bold text-slate-800">{{ $concluidas ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-4"
             style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 1px 2px rgba(0,0,0,0.04);">
            <p class="text-[10px] uppercase tracking-widest text-slate-400 font-semibold mb-1">Canceladas</p>
            <p class="text-xl font-bold text-red-500">{{ $canceladas ?? 0 }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-4 mb-6 flex items-center justify-between"
         style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 1px 2px rgba(0,0,0,0.04);">
        <p class="text-[10px] uppercase tracking-widest text-slate-400 font-semibold">Receita total</p>
        <p class="text-xl font-bold" style="color:#1e40af;">
            € {{ number_format($receita ?? 0, 2, ',', '.') }}
        </p>
    </div>

    @else

    <div class="grid gap-3 mb-6">
        <div class="bg-white rounded-2xl border border-slate-200 p-4"
             style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 1px 2px rgba(0,0,0,0.04);">
            <p class="text-[10px] uppercase tracking-widest text-slate-400 font-semibold mb-1">Total de Reservas</p>
            <p class="text-xl font-bold text-slate-900">{{ $total ?? 0 }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-4"
             style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 1px 2px rgba(0,0,0,0.04);">
            <p class="text-[10px] uppercase tracking-widest text-slate-400 font-semibold mb-1">Próxima Reserva</p>
            @if(isset($proxima) && $proxima)
                <p class="font-semibold text-slate-900">
                    {{ \Carbon\Carbon::parse($proxima->data_reserva)->format('d/m/Y H:i') }}
                </p>
                <p class="text-xs text-slate-400 mt-0.5">
                    até {{ \Carbon\Carbon::parse($proxima->data_prevista)->format('d/m/Y H:i') }}
                </p>
            @else
                <p class="text-slate-400 text-sm">Não tens reservas futuras</p>
            @endif
        </div>
    </div>

    @endif

</div>
@endauth

</body>
</html>
