<x-app-layout>
<div class="min-h-screen py-8 px-4" style="background:#f8f9fb;">
<div class="max-w-xl mx-auto">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-7">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Novo Veículo</h1>
            <p class="text-sm text-slate-400 mt-0.5">Preenche os dados abaixo</p>
        </div>
        <a href="{{ route('veiculos.index') }}"
           class="text-xs font-semibold text-slate-500 hover:text-slate-900 border border-slate-200 hover:border-slate-300 bg-white px-4 py-2 rounded-xl transition-all duration-150">
            ← Voltar
        </a>
    </div>

    {{-- ERROS --}}
    @if($errors->any())
    <div class="mb-5 px-4 py-3 rounded-xl bg-red-50 border border-red-100 text-sm text-red-700">
        <p class="font-semibold mb-1">Corrige os seguintes erros:</p>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- FORM CARD --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden"
         style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 1px 2px rgba(0,0,0,0.04);">
        <form method="POST" action="{{ route('veiculos.store') }}">
            @csrf

            <div class="p-6 flex flex-col gap-5">

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">
                        Matrícula
                    </label>
                    <input type="text" name="matricula" value="{{ old('matricula') }}" required
                           class="w-full px-4 py-2.5 text-sm text-slate-800 bg-white border border-slate-200 rounded-xl transition-all duration-150
                                  shadow-[inset_0_1px_2px_rgba(0,0,0,0.05)] focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">
                            Marca
                        </label>
                        <input type="text" name="marca" value="{{ old('marca') }}" required
                               class="w-full px-4 py-2.5 text-sm text-slate-800 bg-white border border-slate-200 rounded-xl transition-all duration-150
                                      shadow-[inset_0_1px_2px_rgba(0,0,0,0.05)] focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">
                            Modelo
                        </label>
                        <input type="text" name="modelo" value="{{ old('modelo') }}" required
                               class="w-full px-4 py-2.5 text-sm text-slate-800 bg-white border border-slate-200 rounded-xl transition-all duration-150
                                      shadow-[inset_0_1px_2px_rgba(0,0,0,0.05)] focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">
                            Categoria
                        </label>
                        <select name="id_categoria" required
                                class="w-full px-4 py-2.5 text-sm text-slate-800 bg-white border border-slate-200 rounded-xl transition-all duration-150
                                       shadow-[inset_0_1px_2px_rgba(0,0,0,0.05)] focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                            <option value="">Selecionar...</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id_categoria }}" @selected(old('id_categoria') == $categoria->id_categoria)>
                                    {{ $categoria->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">
                            Estado
                        </label>
                        <select name="id_estado_veiculo" required
                                class="w-full px-4 py-2.5 text-sm text-slate-800 bg-white border border-slate-200 rounded-xl transition-all duration-150
                                       shadow-[inset_0_1px_2px_rgba(0,0,0,0.05)] focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                            <option value="">Selecionar...</option>
                            @foreach($estadosVeiculo as $estado)
                                <option value="{{ $estado->id_estado_veiculo }}" @selected(old('id_estado_veiculo') == $estado->id_estado_veiculo)>
                                    {{ $estado->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">
                        Localização
                    </label>
                    <select name="id_localizacao" required
                            class="w-full px-4 py-2.5 text-sm text-slate-800 bg-white border border-slate-200 rounded-xl transition-all duration-150
                                   shadow-[inset_0_1px_2px_rgba(0,0,0,0.05)] focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        <option value="">Selecionar...</option>
                        @foreach($localizacoes as $localizacao)
                            <option value="{{ $localizacao->id_localizacao }}" @selected(old('id_localizacao') == $localizacao->id_localizacao)>
                                {{ $localizacao->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">
                        Preço diário (€)
                    </label>
                    <input type="number" name="preco_diario" step="0.01" min="0.01" value="{{ old('preco_diario') }}" required
                           class="w-full px-4 py-2.5 text-sm text-slate-800 bg-white border border-slate-200 rounded-xl transition-all duration-150
                                  shadow-[inset_0_1px_2px_rgba(0,0,0,0.05)] focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>

            </div>

            <div class="flex items-center justify-end px-6 py-4 border-t border-slate-100" style="background:#fafbfc;">
                <button type="submit"
                        class="text-white text-[13px] font-semibold px-6 py-2.5 rounded-xl transition-all duration-150 hover:-translate-y-px"
                        style="background:linear-gradient(135deg,#2563eb 0%,#1e40af 100%); box-shadow:0 2px 8px rgba(30,64,175,0.25);">
                    Criar veículo
                </button>
            </div>

        </form>
    </div>

</div>
</div>
</x-app-layout>
