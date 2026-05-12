<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden transition-all duration-150 hover:-translate-y-px"
     style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 1px 2px rgba(0,0,0,0.04);">

    {{-- CORPO --}}
    <div class="flex justify-between items-start gap-4 p-5">

        <div class="flex items-start gap-3 flex-1 min-w-0">
            {{-- ÍCONE --}}
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#eff6ff;">
                <svg class="w-5 h-5" fill="none" stroke="#2563eb" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M5 17H3a2 2 0 01-2-2v-4l3-6h12l3 6v4a2 2 0 01-2 2h-2M5 17a2 2 0 104 0 2 2 0 00-4 0zm10 0a2 2 0 104 0 2 2 0 00-4 0z"/>
                </svg>
            </div>

            <div class="flex-1 min-w-0">
                <p class="text-[15px] font-semibold text-slate-900 tracking-tight">
                    {{ $reserva->veiculo->marca }} {{ $reserva->veiculo->modelo }}
                </p>
                {{-- Nome do cliente: só relevante para funcionários/admins --}}
                @if(auth()->user()->isFuncionarioOrAdmin())
                    <p class="text-xs text-slate-400 mt-0.5">{{ $reserva->cliente->nome }}</p>
                @endif

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
            <x-reserva-status :reserva="$reserva" class="px-2.5 py-1 mt-2" />
        </div>
    </div>

    {{-- RODAPÉ COM AÇÕES --}}
    @if(auth()->user()->isFuncionarioOrAdmin() && !$reserva->isCancelada())
    <div class="flex items-center gap-1 px-4 py-2.5 border-t border-slate-100" style="background:#fafbfc;">
        <a href="{{ route('reservas.edit', $reserva->id_reserva) }}"
           class="text-xs font-medium text-slate-500 hover:text-slate-900 hover:bg-slate-100 px-3 py-1.5 rounded-lg transition-colors">
            Editar
        </a>
        <a href="{{ route('reservas.show', $reserva->id_reserva) }}"
           class="text-xs font-medium text-slate-500 hover:text-slate-900 hover:bg-slate-100 px-3 py-1.5 rounded-lg transition-colors">
            Ver detalhe
        </a>

        @if($reserva->isAtiva())
        <form method="POST" action="{{ route('reservas.cancelar', $reserva->id_reserva) }}" class="ml-auto">
            @csrf @method('PUT')
            <button type="submit"
                    class="text-xs font-medium text-red-600 hover:text-red-800 hover:bg-red-50 px-3 py-1.5 rounded-lg transition-colors"
                    onclick="return confirm('Cancelar esta reserva?')">
                Cancelar
            </button>
        </form>
        <form method="POST" action="{{ route('reservas.concluir', $reserva->id_reserva) }}">
            @csrf @method('PUT')
            <button type="submit"
                    class="text-xs font-semibold text-white px-4 py-1.5 rounded-lg transition-all duration-150 hover:-translate-y-px"
                    style="background:linear-gradient(135deg,#2563eb 0%,#1e40af 100%);">
                Concluir
            </button>
        </form>
        @endif
    </div>
    @elseif($reserva->isAtiva())
    <div class="flex items-center gap-1 px-4 py-2.5 border-t border-slate-100" style="background:#fafbfc;">
        <a href="{{ route('reservas.show', $reserva->id_reserva) }}"
           class="text-xs font-medium text-slate-500 hover:text-slate-900 hover:bg-slate-100 px-3 py-1.5 rounded-lg transition-colors">
            Ver detalhe
        </a>
        <form method="POST" action="{{ route('reservas.cancelar', $reserva->id_reserva) }}" class="ml-auto">
            @csrf @method('PUT')
            <button type="submit"
                    class="text-xs font-medium text-red-600 hover:text-red-800 hover:bg-red-50 px-3 py-1.5 rounded-lg transition-colors"
                    onclick="return confirm('Cancelar esta reserva?')">
                Cancelar
            </button>
        </form>
    </div>
    @else
    {{-- Reserva concluída ou cancelada: cliente pode ver detalhe --}}
    <div class="flex items-center gap-1 px-4 py-2.5 border-t border-slate-100" style="background:#fafbfc;">
        <a href="{{ route('reservas.show', $reserva->id_reserva) }}"
           class="text-xs font-medium text-slate-500 hover:text-slate-900 hover:bg-slate-100 px-3 py-1.5 rounded-lg transition-colors">
            Ver detalhe
        </a>
    </div>
    @endif

</div>
