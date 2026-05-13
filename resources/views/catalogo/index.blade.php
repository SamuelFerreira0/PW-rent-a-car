<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catálogo · {{ config('app.name', 'Rent a Car') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased min-h-screen" style="background:#f8f9fb; margin:0;">

{{-- NAVEGAÇÃO: partilhada entre guest e auth --}}
@auth
    @include('layouts.navigation')
@else
    <nav class="bg-white border-b border-slate-200 shadow-[0_1px_3px_rgba(0,0,0,0.06)]">
        <div class="max-w-5xl mx-auto px-4 h-14 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 transition-transform duration-150 group-hover:-translate-y-px"
                         style="background:linear-gradient(135deg,#2563eb,#1e40af); box-shadow:0 2px 6px rgba(30,64,175,0.35);">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M5 17H3a2 2 0 01-2-2v-4l3-6h12l3 6v4a2 2 0 01-2 2h-2M5 17a2 2 0 104 0 2 2 0 00-4 0zm10 0a2 2 0 104 0 2 2 0 00-4 0z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-bold text-slate-900 tracking-tight">Rent a Car</span>
                </a>
                <div class="flex items-center gap-0.5">
                    <a href="{{ route('home') }}"
                       class="text-xs font-semibold px-3 py-1.5 rounded-lg transition-all duration-150 text-slate-500 hover:text-slate-900 hover:bg-slate-100">
                        Início
                    </a>
                    <a href="{{ route('catalogo.index') }}"
                       class="text-xs font-semibold px-3 py-1.5 rounded-lg transition-all duration-150 bg-blue-50 text-blue-700">
                        Catálogo
                    </a>
                </div>
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

<div class="max-w-5xl mx-auto px-4 py-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-5">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Catálogo de Veículos</h1>
            <p class="text-sm text-slate-400 mt-0.5">
                {{ $veiculos->count() }} {{ $veiculos->count() === 1 ? 'veículo disponível' : 'veículos disponíveis' }}
            </p>
        </div>
        @guest
        <p class="hidden sm:block text-xs text-slate-400">
            <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">Inicie sessão</a> para reservar
        </p>
        @endguest
    </div>

    {{-- GRID DE VEÍCULOS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
        @forelse($veiculos as $veiculo)

        <div class="bg-white rounded-xl border border-slate-200 transition-colors duration-150 hover:border-blue-200 hover:bg-slate-50/50">

            <div class="px-4 pt-3 pb-2.5">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2 min-w-0">
                        <p class="font-semibold text-sm text-slate-900 truncate">{{ $veiculo->marca }} {{ $veiculo->modelo }}</p>
                        <span class="text-[10px] text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded font-mono flex-shrink-0">{{ $veiculo->matricula }}</span>
                    </div>
                    <p class="text-sm font-bold text-slate-800 flex-shrink-0 tabular-nums">
                        € {{ number_format($veiculo->preco_diario, 2, ',', '.') }}<span class="text-xs font-normal text-slate-400">/dia</span>
                    </p>
                </div>
                <p class="text-xs text-slate-400 mt-0.5">
                    {{ $veiculo->categoria->nome_categoria ?? $veiculo->categoria->nome ?? '—' }}
                    <span class="mx-1">·</span>
                    {{ $veiculo->estadoVeiculo->nome ?? '—' }}
                    <span class="mx-1">·</span>
                    {{ $veiculo->localizacao->nome ?? '—' }}
                </p>
            </div>

            <div class="flex items-center justify-end px-3 py-1.5 border-t border-slate-100 bg-slate-50/50">
                @guest
                    <a href="{{ route('login') }}"
                       class="text-xs font-semibold text-blue-600 hover:text-blue-700 border border-blue-200 hover:border-blue-300 bg-white hover:bg-blue-50 px-3 py-1 rounded-lg transition-colors">
                        Reservar →
                    </a>
                @else
                    @if(auth()->user()->isFuncionarioOrAdmin())
                        <a href="{{ route('veiculos.edit', $veiculo->id_veiculo) }}"
                           class="text-xs font-medium text-slate-500 hover:text-slate-900 hover:bg-white border border-transparent hover:border-slate-200 px-3 py-1 rounded-lg transition-colors">
                            Editar
                        </a>
                    @else
                        <a href="{{ route('reservas.create', ['veiculo' => $veiculo->id_veiculo]) }}"
                           class="text-xs font-semibold text-blue-600 hover:text-blue-700 border border-blue-200 hover:border-blue-300 bg-white hover:bg-blue-50 px-3 py-1 rounded-lg transition-colors">
                            Reservar →
                        </a>
                    @endif
                @endguest
            </div>

        </div>

        @empty
        <div class="col-span-full text-center py-16">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mx-auto mb-3 bg-slate-100">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M5 17H3a2 2 0 01-2-2v-4l3-6h12l3 6v4a2 2 0 01-2 2h-2M5 17a2 2 0 104 0 2 2 0 00-4 0zm10 0a2 2 0 104 0 2 2 0 00-4 0z"/>
                </svg>
            </div>
            <p class="text-sm font-medium text-slate-400">Nenhum veículo disponível de momento</p>
        </div>
        @endforelse
    </div>

</div>

</body>
</html>
