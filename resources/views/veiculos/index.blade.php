<x-app-layout>
<div class="min-h-screen py-6 px-4" style="background:#f8f9fb;">
<div class="max-w-4xl mx-auto">

    {{-- FEEDBACK --}}
    @if(session('success'))
    <div class="mb-4 px-4 py-3 rounded-xl bg-green-50 border border-green-100 text-sm text-green-700 font-medium">
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="mb-4 px-4 py-3 rounded-xl bg-red-50 border border-red-100 text-sm text-red-700 font-medium">
        {{ $errors->first() }}
    </div>
    @endif

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-5">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Veículos</h1>
            <p class="text-sm text-slate-400 mt-0.5">{{ $veiculos->count() }} veículos registados</p>
        </div>
        <a href="{{ route('veiculos.create') }}"
           class="inline-flex items-center gap-1.5 text-white text-xs font-semibold px-3.5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Novo veículo
        </a>
    </div>

    {{-- LISTA --}}
    <div class="flex flex-col gap-2">
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

            <div class="flex items-center gap-1 px-3 py-1.5 border-t border-slate-100 bg-slate-50/50">
                <a href="{{ route('veiculos.edit', $veiculo->id_veiculo) }}"
                   class="text-xs font-medium text-slate-500 hover:text-slate-900 hover:bg-white border border-transparent hover:border-slate-200 px-3 py-1 rounded-lg transition-colors">
                    Editar
                </a>
                <form method="POST" action="{{ route('veiculos.destroy', $veiculo->id_veiculo) }}">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="text-xs font-medium text-red-500 hover:text-red-700 hover:bg-red-50 border border-transparent hover:border-red-100 px-3 py-1 rounded-lg transition-colors"
                            onclick="return confirm('Eliminar este veículo?')">
                        Eliminar
                    </button>
                </form>
            </div>

        </div>

        @empty
        <div class="text-center py-16">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mx-auto mb-3 bg-slate-100">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M5 17H3a2 2 0 01-2-2v-4l3-6h12l3 6v4a2 2 0 01-2 2h-2M5 17a2 2 0 104 0 2 2 0 00-4 0zm10 0a2 2 0 104 0 2 2 0 00-4 0z"/>
                </svg>
            </div>
            <p class="text-sm font-medium text-slate-400">Ainda não existem veículos registados</p>
            <a href="{{ route('veiculos.create') }}"
               class="inline-flex items-center gap-1.5 text-white text-xs font-semibold px-4 py-2 rounded-lg mt-4 bg-blue-600 hover:bg-blue-700 transition-colors">
                Adicionar primeiro veículo
            </a>
        </div>
        @endforelse
    </div>

</div>
</div>
</x-app-layout>
