<x-app-layout>
<div class="min-h-screen py-8 px-4" style="background:#f8f9fb;">
<div class="max-w-xl mx-auto">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-7">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Detalhe da Reserva</h1>
            <p class="text-sm text-slate-400 mt-0.5">Reserva #{{ $reserva->id_reserva }}</p>
        </div>
        <a href="{{ route('reservas.index') }}"
           class="text-xs font-semibold text-slate-500 hover:text-slate-900 border border-slate-200 hover:border-slate-300 bg-white px-4 py-2 rounded-xl transition-all duration-150">
            ← Voltar
        </a>
    </div>

    {{-- CARD PRINCIPAL --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden"
         style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 1px 2px rgba(0,0,0,0.04);">

        {{-- VEHICLE BANNER --}}
        <div class="h-24 flex items-end px-5 pb-4 relative overflow-hidden"
             style="background:linear-gradient(135deg,#1e3a8a 0%,#2563eb 60%,#3b82f6 100%);">
            <svg class="absolute right-5 top-1/2 -translate-y-1/2 w-16 h-16 text-white/10" fill="none" stroke="currentColor" stroke-width="0.8" viewBox="0 0 24 24">
                <path d="M5 17H3a2 2 0 01-2-2v-4l3-6h12l3 6v4a2 2 0 01-2 2h-2M5 17a2 2 0 104 0 2 2 0 00-4 0zm10 0a2 2 0 104 0 2 2 0 00-4 0z"/>
            </svg>
            <div>
                <p class="text-white font-bold text-lg tracking-tight leading-none">
                    {{ $reserva->veiculo->marca }} {{ $reserva->veiculo->modelo }}
                </p>
                <p class="text-blue-200 text-xs mt-1">{{ $reserva->cliente->nome }}</p>
            </div>
        </div>

        {{-- BODY --}}
        <div class="p-5">

            {{-- BADGE --}}
            <div class="mb-5">
                <x-reserva-status :reserva="$reserva" class="px-3 py-1.5" />
            </div>

            {{-- DETALHES --}}
            <div class="grid grid-cols-2 gap-4 mb-5">
                <div>
                    <p class="text-[10px] uppercase tracking-widest text-slate-300 font-semibold">Início</p>
                    <p class="text-[13px] text-slate-700 font-semibold mt-0.5">
                        {{ \Carbon\Carbon::parse($reserva->data_reserva)->format('d/m/Y') }}
                    </p>
                    <p class="text-xs text-slate-400">
                        {{ \Carbon\Carbon::parse($reserva->data_reserva)->format('H:i') }}
                    </p>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-widest text-slate-300 font-semibold">Fim</p>
                    <p class="text-[13px] text-slate-700 font-semibold mt-0.5">
                        {{ \Carbon\Carbon::parse($reserva->data_prevista)->format('d/m/Y') }}
                    </p>
                    <p class="text-xs text-slate-400">
                        {{ \Carbon\Carbon::parse($reserva->data_prevista)->format('H:i') }}
                    </p>
                </div>
            </div>

            {{-- CUSTO --}}
            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                <p class="text-[10px] uppercase tracking-widest text-slate-400 font-semibold">Valor total</p>
                <p class="text-2xl font-bold text-slate-900 tracking-tight">
                    € {{ number_format($reserva->custo_total, 2, ',', '.') }}
                </p>
            </div>

        </div>

        {{-- AÇÕES --}}
        @if($reserva->isAtiva())
        <div class="flex items-center justify-end px-5 py-3 border-t border-slate-100" style="background:#fafbfc;">
            <form method="POST" action="{{ route('reservas.cancelar', $reserva->id_reserva) }}">
                @csrf @method('PUT')
                <button type="submit"
                        class="inline-flex items-center text-xs font-medium text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 border border-red-100 px-4 py-2 rounded-lg transition-all duration-150"
                        onclick="return confirm('Cancelar esta reserva?')">
                    Cancelar reserva
                </button>
            </form>
        </div>
        @endif

    </div>

</div>
</div>
</x-app-layout>
