<x-app-layout>
<div class="min-h-screen py-8 px-4" style="background:#f8f9fb;">
    <div class="max-w-4xl mx-auto">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-7">
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Reservas</h1>
                <p class="text-sm text-slate-400 mt-0.5">
                    {{ $statsAtivas + $statsConcluidas + $statsCanceladas }} reservas ·
                    {{ $statsAtivas }} ativas
                </p>
            </div>

            <a href="{{ route('reservas.create') }}"
               class="inline-flex items-center gap-1.5 text-white text-[13px] font-semibold px-4 py-2.5 rounded-xl transition-all duration-150 hover:-translate-y-px"
               style="background:linear-gradient(135deg,#2563eb 0%,#1e40af 100%); box-shadow:0 2px 8px rgba(30,64,175,0.25);">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Nova reserva
            </a>
        </div>

        {{-- STATS --}}
        <div class="grid gap-3 mb-6 grid-cols-2 sm:grid-cols-4">
            <div class="bg-white rounded-xl border border-slate-200 px-4 py-3"
                 style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 1px 2px rgba(0,0,0,0.04);">
                <p class="text-[10px] uppercase tracking-wide text-slate-400 font-semibold mb-0.5">Valor total</p>
                <p class="text-lg font-bold" style="color:#1e40af;">
                    € {{ number_format($totalCusto, 2, ',', '.') }}
                </p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 px-4 py-3"
                 style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 1px 2px rgba(0,0,0,0.04);">
                <p class="text-[10px] uppercase tracking-wide text-slate-400 font-semibold mb-0.5">Ativas</p>
                <p class="text-xl font-bold text-emerald-600">{{ $statsAtivas }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 px-4 py-3"
                 style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 1px 2px rgba(0,0,0,0.04);">
                <p class="text-[10px] uppercase tracking-wide text-slate-400 font-semibold mb-0.5">Concluídas</p>
                <p class="text-xl font-bold text-slate-800">{{ $statsConcluidas }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 px-4 py-3"
                 style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 1px 2px rgba(0,0,0,0.04);">
                <p class="text-[10px] uppercase tracking-wide text-slate-400 font-semibold mb-0.5">Canceladas</p>
                <p class="text-xl font-bold text-red-500">{{ $statsCanceladas }}</p>
            </div>
        </div>

        {{-- SECÇÃO: ATIVAS --}}
        @if($ativas->isNotEmpty())
        <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-3 px-1">Ativas</p>
        <div class="flex flex-col gap-2.5 mb-6">
            @foreach($ativas as $reserva)
                @include('reservas._card')
            @endforeach
        </div>
        @endif

        {{-- SECÇÃO: HISTÓRICO --}}
        @if($historico->isNotEmpty())
        <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-3 px-1">Histórico</p>
        <div class="flex flex-col gap-2.5">
            @foreach($historico as $reserva)
                @include('reservas._card')
            @endforeach
        </div>
        @endif

        {{-- EMPTY STATE --}}
        @if($ativas->isEmpty() && $historico->isEmpty())
        <div class="text-center py-20">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:#eff6ff;">
                <svg class="w-6 h-6" fill="none" stroke="#2563eb" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-slate-400">Ainda não existem reservas</p>
        </div>
        @endif

        {{-- PAGINAÇÃO --}}
        @if($reservas->hasPages())
        <div class="mt-6">
            {{ $reservas->links() }}
        </div>
        @endif

    </div>
</div>
</x-app-layout>
