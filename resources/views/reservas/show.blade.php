<x-app-layout>
    <div class="max-w-3xl mx-auto py-8 px-4">

        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">

            <h1 class="text-xl font-bold mb-4">Detalhe da Reserva</h1>

            <div class="space-y-2 text-sm text-gray-700">
                <p><strong>Veículo:</strong> {{ $reserva->veiculo->marca }} {{ $reserva->veiculo->modelo }}</p>
                <p><strong>Cliente:</strong> {{ $reserva->cliente->nome }}</p>

                <p>
                    <strong>Início:</strong>
                    {{ \Carbon\Carbon::parse($reserva->data_reserva)->format('d/m/Y H:i') }}
                </p>

                <p>
                    <strong>Fim:</strong>
                    {{ \Carbon\Carbon::parse($reserva->data_prevista)->format('d/m/Y H:i') }}
                </p>

                <p class="text-lg font-semibold mt-3">
                    € {{ number_format($reserva->custo_total, 2, ',', '.') }}
                </p>
            </div>

            <a href="/reservas"
               class="inline-block mt-4 bg-gray-100 px-4 py-2 rounded-lg hover:bg-gray-200">
                Voltar
            </a>

        </div>
    </div>
</x-app-layout>