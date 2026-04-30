<x-app-layout>
    <!-- FUNDO -->
    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4">

            <!-- HEADER -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">
                    Reservas
                </h1>

                @if(auth()->user()->funcionario)
                    <a href="/reservas/create"
                       class="bg-blue-600 text-white px-5 py-2 rounded-xl font-semibold shadow hover:bg-blue-700 transition">
                        + Nova Reserva
                    </a>
                @endif
            </div>

            <div class="py-10">
                <div class="max-w-2xl mx-auto space-y-4">

                    @foreach($reservas as $reserva)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition">

                        <div class="flex justify-between items-start">

                            <!-- LEFT -->
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">
                                    {{ $reserva->veiculo->marca }} {{ $reserva->veiculo->modelo }}
                                </h2>

                                <div class="text-sm text-gray-500">
                                    {{ $reserva->cliente->nome }}
                                </div>

                                <div class="text-sm text-gray-600 mt-2">
                                    <strong>Início:</strong>
                                    {{ \Carbon\Carbon::parse($reserva->data_reserva)->format('d/m/Y H:i') }}
                                </div>

                                <div class="text-sm text-gray-600">
                                    <strong>Fim:</strong>
                                    {{ \Carbon\Carbon::parse($reserva->data_prevista)->format('d/m/Y H:i') }}
                                </div>
                            </div>

                            <!-- RIGHT -->
                            <div class="text-right">
                                <div class="text-xs text-gray-400">Total</div>
                                <div class="text-xl font-bold text-blue-600">
                                    € {{ number_format($reserva->custo_total, 2, ',', '.') }}
                                </div>

                                <div class="text-sm font-semibold mt-1 {{ $reserva->id_estado_reserva == 1 ? 'text-green-600' : 'text-blue-600' }}">
                                    {{ $reserva->id_estado_reserva == 1 ? 'Ativa' : 'Concluída' }}
                                </div>
                            </div>

                        </div>

                        <!-- ACTIONS -->
                        @if(auth()->user()->funcionario)
                        <div class="flex gap-3 mt-4 text-sm">

                            <a href="/reservas/{{ $reserva->id_reserva }}/edit"
                            class="text-gray-600 hover:text-black">
                                Editar
                            </a>

                            <form method="POST" action="/reservas/{{ $reserva->id_reserva }}">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:text-red-800">
                                    Eliminar
                                </button>
                            </form>

                            @if($reserva->id_estado_reserva != 2)
                                <form method="POST" action="/reservas/{{ $reserva->id_reserva }}/concluir">
                                    @csrf
                                    @method('PUT')
                                    <button class="ml-auto bg-blue-600 text-white px-4 py-1 rounded-md hover:bg-blue-700">
                                        Concluir
                                    </button>
                                </form>
                            @endif

                        </div>
                        @endif

                    </div>
                    @endforeach

                </div>
            </div>

        </div>
    </div>
</x-app-layout>