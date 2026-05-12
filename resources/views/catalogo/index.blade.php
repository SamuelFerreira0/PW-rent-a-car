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

<div class="max-w-5xl mx-auto px-4 py-8">

    {{-- HEADER --}}
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Catálogo de Veículos</h1>
            <p class="text-sm text-slate-400 mt-1">
                {{ $veiculos->count() }} {{ $veiculos->count() === 1 ? 'veículo disponível' : 'veículos disponíveis' }}
            </p>
        </div>
        @guest
        <div class="hidden sm:flex items-center gap-2 text-xs text-slate-400">
            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            <span>É necessário <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">iniciar sessão</a> para reservar</span>
        </div>
        @endguest
    </div>

    {{-- GRID DE VEÍCULOS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($veiculos as $veiculo)

        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden transition-all duration-150 hover:-translate-y-0.5"
             style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 1px 2px rgba(0,0,0,0.04);">

            {{-- BANNER --}}
            <div class="h-28 flex items-end px-4 pb-3 relative overflow-hidden"
                 style="background:linear-gradient(135deg,#1e3a8a 0%,#2563eb 60%,#3b82f6 100%);">
                <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-16 h-16 text-white/10"
                     fill="none" stroke="currentColor" stroke-width="0.8" viewBox="0 0 24 24">
                    <path d="M5 17H3a2 2 0 01-2-2v-4l3-6h12l3 6v4a2 2 0 01-2 2h-2M5 17a2 2 0 104 0 2 2 0 00-4 0zm10 0a2 2 0 104 0 2 2 0 00-4 0z"/>
                </svg>
                <div class="absolute top-3 right-3">
                    <span class="text-[11px] font-semibold text-white/80 bg-white/10 px-2 py-0.5 rounded-full">
                        {{ $veiculo->matricula }}
                    </span>
                </div>
                <div>
                    <p class="text-white font-bold text-[15px] tracking-tight leading-none">
                        {{ $veiculo->marca }} {{ $veiculo->modelo }}
                    </p>
                    <p class="text-blue-200 text-[11px] mt-0.5">
                        {{ $veiculo->categoria->nome_categoria ?? $veiculo->categoria->nome ?? '—' }}
                    </p>
                </div>
            </div>

            {{-- CORPO --}}
            <div class="px-4 pt-4 pb-3 flex flex-col gap-3">

                {{-- DETALHES --}}
                <div class="grid grid-cols-2 gap-x-4 gap-y-2.5">
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-slate-300 font-semibold">Estado</p>
                        <p class="text-[13px] text-slate-700 font-medium mt-0.5">
                            {{ $veiculo->estadoVeiculo->nome ?? '—' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-slate-300 font-semibold">Localização</p>
                        <p class="text-[13px] text-slate-700 font-medium mt-0.5">
                            {{ $veiculo->localizacao->nome ?? '—' }}
                        </p>
                    </div>
                </div>

                {{-- PREÇO --}}
                <div class="flex items-center justify-between pt-2.5 border-t border-slate-100">
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-slate-300 font-semibold">Preço / dia</p>
                        <p class="text-xl font-bold text-slate-900 tracking-tight mt-0.5">
                            € {{ number_format($veiculo->preco_diario, 2, ',', '.') }}
                        </p>
                    </div>

                    {{-- CTA --}}
                    @guest
                        <a href="{{ route('login') }}"
                           class="text-xs font-semibold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 border border-blue-100 px-3 py-2 rounded-xl transition-all duration-150">
                            Reservar →
                        </a>
                    @else
                        @if(auth()->user()->isFuncionarioOrAdmin())
                            <a href="{{ route('veiculos.edit', $veiculo->id_veiculo) }}"
                               class="text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 border border-slate-200 px-3 py-2 rounded-xl transition-all duration-150">
                                Editar
                            </a>
                        @else
                            <a href="{{ route('reservas.create', ['veiculo' => $veiculo->id_veiculo]) }}"
                               class="text-xs font-semibold text-white px-3 py-2 rounded-xl transition-all duration-150 hover:-translate-y-px"
                               style="background:linear-gradient(135deg,#2563eb 0%,#1e40af 100%); box-shadow:0 2px 6px rgba(30,64,175,0.2);">
                                Reservar →
                            </a>
                        @endif
                    @endguest
                </div>

            </div>
        </div>

        @empty
        <div class="col-span-3 text-center py-20">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:#eff6ff;">
                <svg class="w-6 h-6" fill="none" stroke="#2563eb" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M5 17H3a2 2 0 01-2-2v-4l3-6h12l3 6v4a2 2 0 01-2 2h-2M5 17a2 2 0 104 0 2 2 0 00-4 0zm10 0a2 2 0 104 0 2 2 0 00-4 0z"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-slate-400">Nenhum veículo disponível de momento</p>
        </div>
        @endforelse
    </div>

</div>

</body>
</html>
