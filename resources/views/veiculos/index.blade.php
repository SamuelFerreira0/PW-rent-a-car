<x-app-layout>

    <div class="py-10">
        <div class="max-w-3xl mx-auto px-4">

            <!-- HEADER -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">
                    Veículos
                </h1>

                <a href="/veiculos/create"
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-700 shadow">
                    + Novo Veículo
                </a>
            </div>

            <!-- LISTA -->
            <div class="space-y-4">

                @foreach($veiculos as $veiculo)
                <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition">

                    <div class="flex justify-between items-start">

                        <!-- INFO -->
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">
                                {{ $veiculo->marca }} {{ $veiculo->modelo }}
                            </h2>

                            <p class="text-sm text-gray-500 mt-1">
                                Categoria: 
                                <span class="font-medium text-gray-700">
                                    {{ $veiculo->categoria->nome_categoria ?? 'Sem categoria' }}
                                </span>
                            </p>
                        </div>

                        <!-- PREÇO -->
                        <div class="text-right">
                            <p class="text-xs text-gray-400">Preço</p>
                            <p class="text-lg font-bold text-gray-900">
                                € {{ number_format($veiculo->preco_diario, 2, ',', '.') }}/dia
                            </p>
                        </div>

                    </div>

                    <!-- AÇÕES -->
                    <div class="flex gap-2 mt-4">

                        <a href="/veiculos/{{ $veiculo->id_veiculo }}/edit"
                           class="px-3 py-1 text-sm rounded-md bg-gray-100 hover:bg-gray-200">
                            Editar
                        </a>

                        <form method="POST" action="/veiculos/{{ $veiculo->id_veiculo }}">
                            @csrf
                            @method('DELETE')
                            <button class="px-3 py-1 text-sm rounded-md bg-red-100 text-red-700 hover:bg-red-200">
                                Eliminar
                            </button>
                        </form>

                    </div>

                </div>
                @endforeach

            </div>

        </div>
    </div>
</x-app-layout>