<x-app-layout>
<div class="min-h-screen py-8 px-4" style="background:#f8f9fb;">
<div class="max-w-xl mx-auto">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-7">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Editar Reserva</h1>
            <p class="text-sm text-slate-400 mt-0.5">
                {{ $reserva->veiculo->marca }} {{ $reserva->veiculo->modelo }} · {{ $reserva->cliente->nome }}
            </p>
        </div>
        <a href="{{ route('reservas.index') }}"
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

        <form method="POST" action="{{ route('reservas.update', $reserva->id_reserva) }}">
            @csrf
            @method('PUT')

            <div class="p-6 flex flex-col gap-5">

                {{-- CLIENTE --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">
                        Cliente
                    </label>
                    <select name="id_cliente"
                            class="w-full px-4 py-2.5 text-sm text-slate-800 bg-white border border-slate-200 rounded-xl transition-all duration-150
                                   shadow-[inset_0_1px_2px_rgba(0,0,0,0.05)] focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id_cliente }}"
                                {{ old('id_cliente', $reserva->id_cliente) == $cliente->id_cliente ? 'selected' : '' }}>
                                {{ $cliente->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- VEÍCULO --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">
                        Veículo
                    </label>
                    <select name="id_veiculo"
                            class="w-full px-4 py-2.5 text-sm text-slate-800 bg-white border border-slate-200 rounded-xl transition-all duration-150
                                   shadow-[inset_0_1px_2px_rgba(0,0,0,0.05)] focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        @foreach($veiculos as $veiculo)
                            <option value="{{ $veiculo->id_veiculo }}"
                                {{ old('id_veiculo', $reserva->id_veiculo) == $veiculo->id_veiculo ? 'selected' : '' }}>
                                {{ $veiculo->marca }} {{ $veiculo->modelo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- DATAS --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">
                            Início
                        </label>
                        <input type="datetime-local" name="data_reserva"
                               value="{{ old('data_reserva', \Carbon\Carbon::parse($reserva->data_reserva)->format('Y-m-d\TH:i')) }}"
                               class="w-full px-4 py-2.5 text-sm text-slate-800 bg-white border border-slate-200 rounded-xl transition-all duration-150
                                      shadow-[inset_0_1px_2px_rgba(0,0,0,0.05)] focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">
                            Fim
                        </label>
                        <input type="datetime-local" name="data_prevista"
                               value="{{ old('data_prevista', \Carbon\Carbon::parse($reserva->data_prevista)->format('Y-m-d\TH:i')) }}"
                               class="w-full px-4 py-2.5 text-sm text-slate-800 bg-white border border-slate-200 rounded-xl transition-all duration-150
                                      shadow-[inset_0_1px_2px_rgba(0,0,0,0.05)] focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    </div>
                </div>

            </div>

            {{-- FOOTER DO CARD --}}
            <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100" style="background:#fafbfc;">
                <p class="text-xs text-slate-400">Reserva #{{ $reserva->id_reserva }}</p>
                <button type="submit"
                        class="text-white text-[13px] font-semibold px-6 py-2.5 rounded-xl transition-all duration-150 hover:-translate-y-px"
                        style="background:linear-gradient(135deg,#2563eb 0%,#1e40af 100%); box-shadow:0 2px 8px rgba(30,64,175,0.25);">
                    Guardar alterações
                </button>
            </div>

        </form>
    </div>

</div>
</div>
</x-app-layout>
