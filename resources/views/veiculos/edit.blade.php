<x-app-layout>
<div class="min-h-screen py-8 px-4" style="background:#f8f9fb;">
<div class="max-w-xl mx-auto">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-7">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Editar Veículo</h1>
            <p class="text-sm text-slate-400 mt-0.5">
                {{ $veiculo->marca }} {{ $veiculo->modelo }} · {{ $veiculo->matricula }}
            </p>
        </div>
        <a href="/veiculos"
           class="text-xs font-semibold text-slate-500 hover:text-slate-900 border border-slate-200 hover:border-slate-300 bg-white px-4 py-2 rounded-xl transition-colors">
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
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <form method="POST" action="/veiculos/{{ $veiculo->id_veiculo }}">
            @csrf
            @method('PUT')

            <div class="p-6 flex flex-col gap-5">

                {{-- MATRÍCULA --}}
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-slate-400 font-semibold mb-1.5">
                        Matrícula
                    </label>
                    <input type="text" name="matricula" value="{{ old('matricula', $veiculo->matricula) }}" required
                           class="w-full px-4 py-2.5 text-sm text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition">
                </div>

                {{-- MARCA + MODELO --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-slate-400 font-semibold mb-1.5">
                            Marca
                        </label>
                        <input type="text" name="marca" value="{{ old('marca', $veiculo->marca) }}" required
                               class="w-full px-4 py-2.5 text-sm text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-slate-400 font-semibold mb-1.5">
                            Modelo
                        </label>
                        <input type="text" name="modelo" value="{{ old('modelo', $veiculo->modelo) }}" required
                               class="w-full px-4 py-2.5 text-sm text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition">
                    </div>
                </div>

                {{-- CATEGORIA + ESTADO --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-slate-400 font-semibold mb-1.5">
                            Categoria
                        </label>
                        <select name="id_categoria" required
                                class="w-full px-4 py-2.5 text-sm text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition">
                            <option value="">Selecionar...</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id_categoria }}" @selected(old('id_categoria', $veiculo->id_categoria) == $categoria->id_categoria)>
                                    {{ $categoria->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-slate-400 font-semibold mb-1.5">
                            Estado
                        </label>
                        <select name="id_estado_veiculo" required
                                class="w-full px-4 py-2.5 text-sm text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition">
                            <option value="">Selecionar...</option>
                            @foreach($estadosVeiculo as $estado)
                                <option value="{{ $estado->id_estado_veiculo }}" @selected(old('id_estado_veiculo', $veiculo->id_estado_veiculo) == $estado->id_estado_veiculo)>
                                    {{ $estado->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- LOCALIZAÇÃO --}}
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-slate-400 font-semibold mb-1.5">
                        Localização
                    </label>
                    <select name="id_localizacao" required
                            class="w-full px-4 py-2.5 text-sm text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition">
                        <option value="">Selecionar...</option>
                        @foreach($localizacoes as $localizacao)
                            <option value="{{ $localizacao->id_localizacao }}" @selected(old('id_localizacao', $veiculo->id_localizacao) == $localizacao->id_localizacao)>
                                {{ $localizacao->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- PREÇO --}}
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-slate-400 font-semibold mb-1.5">
                        Preço diário (€)
                    </label>
                    <input type="number" name="preco_diario" step="0.01" min="0.01" value="{{ old('preco_diario', $veiculo->preco_diario) }}" required
                           class="w-full px-4 py-2.5 text-sm text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition">
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100" style="background:#fafbfc;">
                <p class="text-xs text-slate-400">Veículo #{{ $veiculo->id_veiculo }}</p>
                <button type="submit"
                        class="text-white text-[13px] font-semibold px-6 py-2.5 rounded-xl transition-colors"
                        style="background:#1e40af;">
                    Guardar alterações
                </button>
            </div>

        </form>
    </div>

</div>
</div>
</x-app-layout>