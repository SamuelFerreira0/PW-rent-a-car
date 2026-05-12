<x-app-layout>
<div class="min-h-screen py-8 px-4" style="background:#f8f9fb;">
<div class="max-w-4xl mx-auto">

    {{-- FEEDBACK --}}
    @if(session('success'))
    <div class="mb-6 px-4 py-3 rounded-xl bg-green-50 border border-green-100 text-sm text-green-700 font-medium">
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 px-4 py-3 rounded-xl bg-red-50 border border-red-100 text-sm text-red-700 font-medium">
        {{ $errors->first() }}
    </div>
    @endif

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-7">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Veículos</h1>
            <p class="text-sm text-slate-400 mt-0.5">{{ $veiculos->count() }} veículos registados</p>
        </div>
        <a href="{{ route('veiculos.create') }}"
           class="inline-flex items-center gap-1.5 text-white text-[13px] font-semibold px-4 py-2.5 rounded-xl transition-all duration-150 hover:-translate-y-px"
           style="background:linear-gradient(135deg,#2563eb 0%,#1e40af 100%); box-shadow:0 2px 8px rgba(30,64,175,0.25);">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Novo veículo
        </a>
    </div>

    {{-- LISTA --}}
    <div class="flex flex-col gap-3">
        @forelse($veiculos as $veiculo)

        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden transition-all duration-150 hover:-translate-y-px"
             style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 1px 2px rgba(0,0,0,0.04);">

            {{-- IMAGE PLACEHOLDER --}}
            <div class="h-28 flex items-center justify-center relative overflow-hidden"
                 style="background:linear-gradient(135deg,#1e3a8a 0%,#2563eb 60%,#3b82f6 100%);">
                <svg class="w-20 h-20 text-white/10" fill="none" stroke="currentColor" stroke-width="0.8" viewBox="0 0 24 24">
                    <path d="M5 17H3a2 2 0 01-2-2v-4l3-6h12l3 6v4a2 2 0 01-2 2h-2M5 17a2 2 0 104 0 2 2 0 00-4 0zm10 0a2 2 0 104 0 2 2 0 00-4 0z"/>
                </svg>
                <div class="absolute top-3 right-3">
                    <span class="text-[11px] font-semibold text-white/80 bg-white/10 px-2 py-0.5 rounded-full">
                        {{ $veiculo->matricula }}
                    </span>
                </div>
                <div class="absolute bottom-3 left-4">
                    <p class="text-white font-bold text-base tracking-tight">{{ $veiculo->marca }} {{ $veiculo->modelo }}</p>
                </div>
            </div>

            {{-- BODY --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 px-5 py-4">
                <div>
                    <p class="text-[10px] uppercase tracking-widest text-slate-300 font-semibold">Categoria</p>
                    <p class="text-[13px] text-slate-700 font-medium mt-0.5">
                        {{ $veiculo->categoria->nome_categoria ?? $veiculo->categoria->nome ?? '—' }}
                    </p>
                </div>
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
                <div class="sm:text-right">
                    <p class="text-[10px] uppercase tracking-widest text-slate-300 font-semibold">Preço/dia</p>
                    <p class="text-xl font-bold text-slate-900 tracking-tight mt-0.5">
                        € {{ number_format($veiculo->preco_diario, 2, ',', '.') }}
                    </p>
                </div>
            </div>

            {{-- AÇÕES --}}
            <div class="flex items-center gap-1 px-4 py-2.5 border-t border-slate-100" style="background:#fafbfc;">
                <a href="{{ route('veiculos.edit', $veiculo->id_veiculo) }}"
                   class="text-xs font-medium text-slate-500 hover:text-slate-900 hover:bg-slate-100 px-3 py-1.5 rounded-lg transition-colors">
                    Editar
                </a>
                <form method="POST" action="{{ route('veiculos.destroy', $veiculo->id_veiculo) }}">
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
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:#eff6ff;">
                <svg class="w-6 h-6" fill="none" stroke="#2563eb" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M5 17H3a2 2 0 01-2-2v-4l3-6h12l3 6v4a2 2 0 01-2 2h-2M5 17a2 2 0 104 0 2 2 0 00-4 0zm10 0a2 2 0 104 0 2 2 0 00-4 0z"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-slate-400">Ainda não existem veículos registados</p>
            <a href="{{ route('veiculos.create') }}"
               class="inline-flex items-center gap-1.5 text-white text-xs font-semibold px-4 py-2 rounded-xl mt-4 transition-all duration-150 hover:-translate-y-px"
               style="background:linear-gradient(135deg,#2563eb 0%,#1e40af 100%); box-shadow:0 2px 8px rgba(30,64,175,0.25);">
                Adicionar primeiro veículo
            </a>
        </div>
        @endforelse
    </div>

</div>
</div>
</x-app-layout>
