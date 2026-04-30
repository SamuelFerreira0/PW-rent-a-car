<x-app-layout>
<div class="min-h-screen py-8 px-4" style="background: #f8f9fb;">
    <div class="max-w-2xl mx-auto">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-7">
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Reservas</h1>
                <p class="text-sm text-slate-400 mt-0.5">
                    {{ $reservas->count() }} reservas ·
                    {{ $reservas->where('id_estado_reserva', 1)->count() }} ativas
                </p>
            </div>

            @if(auth()->user()->funcionario)
            <a href="/reservas/create"
               class="inline-flex items-center gap-1.5 text-white text-[13px] font-semibold px-4 py-2.5 rounded-xl transition-colors"
               style="background:#1e40af;">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Nova reserva
            </a>
            @endif
        </div>

        {{-- STATS --}}
        <div class="grid grid-cols-3 gap-3 mb-6">
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <p class="text-[10px] uppercase tracking-widest text-slate-400 font-semibold mb-1">Total este mês</p>
                <p class="text-xl font-bold" style="color:#1e40af;">
                    € {{ number_format($reservas->sum('custo_total'), 2, ',', '.') }}
                </p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <p class="text-[10px] uppercase tracking-widest text-slate-400 font-semibold mb-1">Ativas</p>
                <p class="text-xl font-bold text-green-600">
                    {{ $reservas->where('id_estado_reserva', 1)->count() }}
                </p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <p class="text-[10px] uppercase tracking-widest text-slate-400 font-semibold mb-1">Concluídas</p>
                <p class="text-xl font-bold text-slate-800">
                    {{ $reservas->where('id_estado_reserva', 2)->count() }}
                </p>
            </div>
        </div>

        {{-- LISTA --}}
        <div class="flex flex-col gap-2.5">
            @forelse($reservas as $reserva)
            @php $isAtiva = $reserva->id_estado_reserva == 1; @endphp

            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">

                {{-- CORPO --}}
                <div class="flex justify-between items-start gap-4 p-5">

                    <div class="flex items-start gap-3 flex-1 min-w-0">
                        {{-- ÍCONE --}}
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#eff6ff;">
                            <svg class="w-5 h-5" fill="none" stroke="#1e40af" stroke-width="1.8" viewBox="0 0 24 24">
                                <path d="M5 17H3a2 2 0 01-2-2v-4l3-6h12l3 6v4a2 2 0 01-2 2h-2M5 17a2 2 0 104 0 2 2 0 00-4 0zm10 0a2 2 0 104 0 2 2 0 00-4 0z"/>
                            </svg>
                        </div>

                        <div class="flex-1">
                            <p class="text-[15px] font-semibold text-slate-900 tracking-tight">
                                {{ $reserva->veiculo->marca }} {{ $reserva->veiculo->modelo }}
                            </p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $reserva->cliente->nome }}</p>

                            <div class="flex gap-4 mt-3">
                                <div>
                                    <p class="text-[10px] uppercase tracking-widest text-slate-300 font-semibold">Início</p>
                                    <p class="text-[13px] text-slate-600 font-medium mt-0.5">
                                        {{ \Carbon\Carbon::parse($reserva->data_reserva)->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-[10px] uppercase tracking-widest text-slate-300 font-semibold">Fim</p>
                                    <p class="text-[13px] text-slate-600 font-medium mt-0.5">
                                        {{ \Carbon\Carbon::parse($reserva->data_prevista)->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TOTAL + BADGE --}}
                    <div class="text-right flex-shrink-0">
                        <p class="text-[10px] uppercase tracking-widest text-slate-300 font-semibold">Total</p>
                        <p class="text-xl font-bold text-slate-900 tracking-tight mt-0.5">
                            € {{ number_format($reserva->custo_total, 2, ',', '.') }}
                        </p>
                        @if($isAtiva)
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold px-2.5 py-1 rounded-full mt-2 bg-green-50 text-green-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>Ativa
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold px-2.5 py-1 rounded-full mt-2 bg-indigo-50 text-indigo-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span>Concluída
                            </span>
                        @endif
                    </div>
                </div>

                {{-- RODAPÉ COM AÇÕES --}}
                @if(auth()->user()->funcionario)
                <div class="flex items-center gap-1 px-4 py-2.5 border-t border-slate-100" style="background:#fafbfc;">
                    <a href="/reservas/{{ $reserva->id_reserva }}/edit"
                       class="text-xs font-medium text-slate-500 hover:text-slate-900 hover:bg-slate-100 px-3 py-1.5 rounded-lg transition-colors">
                        Editar
                    </a>

                    <form method="POST" action="/reservas/{{ $reserva->id_reserva }}">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="text-xs font-medium text-red-500 hover:text-red-700 hover:bg-red-50 px-3 py-1.5 rounded-lg transition-colors"
                                onclick="return confirm('Eliminar esta reserva?')">
                            Eliminar
                        </button>
                    </form>

                    @if($isAtiva)
                    <form method="POST" action="/reservas/{{ $reserva->id_reserva }}/concluir" class="ml-auto">
                        @csrf @method('PUT')
                        <button type="submit"
                                class="text-xs font-semibold text-white px-4 py-1.5 rounded-lg transition-colors"
                                style="background:#1e40af;">
                            Concluir
                        </button>
                    </form>
                    @endif
                </div>
                @endif

            </div>
            @empty
            <div class="text-center py-20">
                <p class="text-sm text-slate-400">Ainda não existem reservas.</p>
            </div>
            @endforelse
        </div>

    </div>
</div>
</x-app-layout>