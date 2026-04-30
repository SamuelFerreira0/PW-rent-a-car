<x-app-layout>
<div class="min-h-screen py-8 px-4" style="background:#f8f9fb;">
<div class="max-w-2xl mx-auto">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-7">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Veículos</h1>
            <p class="text-sm text-slate-400 mt-0.5">{{ $veiculos->count() }} veículos registados</p>
        </div>
        <a href="/veiculos/create"
           class="inline-flex items-center gap-1.5 text-white text-[13px] font-semibold px-4 py-2.5 rounded-xl transition-colors"
           style="background:#1e40af;">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Novo veículo
        </a>
    </div>

    {{-- LISTA --}}
    <div class="flex flex-col gap-2.5">
        @forelse($veiculos as $veiculo)

        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">

            <div class="flex justify-between items-start gap-4 p-5">

                {{-- ÍCONE + INFO --}}
                <div class="flex items-start gap-3 flex-1 min-w-0">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#eff6ff;">
                        <svg class="w-5 h-5" fill="none" stroke="#1e40af" stroke-width="1.8" viewBox="0 0 24 24">
                            <path d="M5 17H3a2 2 0 01-2-2v-4l3-6h12l3 6v4a2 2 0 01-2 2h-2M5 17a2 2 0 104 0 2 2 0 00-4 0zm10 0a2 2 0 104 0 2 2 0 00-4 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-[15px] font-semibold text-slate-900 tracking-tight">
                            {{ $veiculo->marca }} {{ $veiculo->modelo }}
                        </p>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $veiculo->matricula }}</p>

                        <div class="flex gap-4 mt-3">
                            <div>
                                <p class="text-[10px] uppercase tracking-widest text-slate-300 font-semibold">Categoria</p>
                                <p class="text-[13px] text-slate-600 font-medium mt-0.5">
                                    {{ $veiculo->categoria->nome_categoria ?? 'Sem categoria' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase tracking-widest text-slate-300 font-semibold">Estado</p>
                                <p class="text-[13px] text-slate-600 font-medium mt-0.5">
                                    {{ $veiculo->estadoVeiculo->nome ?? '—' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase tracking-widest text-slate-300 font-semibold">Localização</p>
                                <p class="text-[13px] text-slate-600 font-medium mt-0.5">
                                    {{ $veiculo->localizacao->nome ?? '—' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PREÇO --}}
                <div class="text-right flex-shrink-0">
                    <p class="text-[10px] uppercase tracking-widest text-slate-300 font-semibold">Preço/dia</p>
                    <p class="text-xl font-bold text-slate-900 tracking-tight mt-0.5">
                        € {{ number_format($veiculo->preco_diario, 2, ',', '.') }}
                    </p>
                </div>

            </div>

            {{-- AÇÕES --}}
            <div class="flex items-center gap-1 px-4 py-2.5 border-t border-slate-100" style="background:#fafbfc;">
                <a href="/veiculos/{{ $veiculo->id_veiculo }}/edit"
                   class="text-xs font-medium text-slate-500 hover:text-slate-900 hover:bg-slate-100 px-3 py-1.5 rounded-lg transition-colors">
                    Editar
                </a>
                <form method="POST" action="/veiculos/{{ $veiculo->id_veiculo }}">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="text-xs font-medium text-red-500 hover:text-red-700 hover:bg-red-50 px-3 py-1.5 rounded-lg transition-colors"
                            onclick="return confirm('Eliminar este veículo?')">
                        Eliminar
                    </button>
                </form>
            </div>

        </div>

        @empty
        <div class="text-center py-20">
            <p class="text-sm text-slate-400">Ainda não existem veículos registados.</p>
        </div>
        @endforelse
    </div>

</div>
</div>
</x-app-layout>