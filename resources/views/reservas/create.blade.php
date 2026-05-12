<x-app-layout>
<div class="min-h-screen py-8 px-4" style="background:#f8f9fb;">
<div class="max-w-xl mx-auto">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-7">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Nova Reserva</h1>
            <p class="text-sm text-slate-400 mt-0.5">Preenche os dados abaixo</p>
        </div>
        <a href="{{ route('reservas.index') }}"
           class="text-xs font-semibold text-slate-500 hover:text-slate-900 border border-slate-200 hover:border-slate-300 bg-white px-4 py-2 rounded-xl transition-all duration-150">
            ← Voltar
        </a>
    </div>

    {{-- ERROS DO SERVIDOR --}}
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

        <form method="POST" action="{{ route('reservas.store') }}">
            @csrf

            <div class="p-6 flex flex-col gap-5">

                {{-- CLIENTE (só funcionários/admins) --}}
                @if(auth()->user()->isFuncionarioOrAdmin())
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">
                        Cliente
                    </label>
                    <select name="id_cliente"
                            class="w-full px-4 py-2.5 text-sm text-slate-800 bg-white border border-slate-200 rounded-xl transition-all duration-150
                                   shadow-[inset_0_1px_2px_rgba(0,0,0,0.05)] focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id_cliente }}"
                                {{ old('id_cliente') == $cliente->id_cliente ? 'selected' : '' }}>
                                {{ $cliente->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- VEÍCULO --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">
                        Veículo
                    </label>
                    <select id="id_veiculo" name="id_veiculo"
                            class="w-full px-4 py-2.5 text-sm text-slate-800 bg-white border border-slate-200 rounded-xl transition-all duration-150
                                   shadow-[inset_0_1px_2px_rgba(0,0,0,0.05)] focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        @foreach($veiculos as $veiculo)
                            <option value="{{ $veiculo->id_veiculo }}"
                                {{ (old('id_veiculo') ?? $veiculoPreSelecionado) == $veiculo->id_veiculo ? 'selected' : '' }}>
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
                        <input id="data_reserva" type="datetime-local" name="data_reserva"
                               value="{{ old('data_reserva') }}"
                               class="w-full px-4 py-2.5 text-sm text-slate-800 bg-white border border-slate-200 rounded-xl transition-all duration-150
                                      shadow-[inset_0_1px_2px_rgba(0,0,0,0.05)] focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">
                            Fim
                        </label>
                        <input id="data_prevista" type="datetime-local" name="data_prevista"
                               value="{{ old('data_prevista') }}"
                               class="w-full px-4 py-2.5 text-sm text-slate-800 bg-white border border-slate-200 rounded-xl transition-all duration-150
                                      shadow-[inset_0_1px_2px_rgba(0,0,0,0.05)] focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    </div>
                </div>

                {{-- MENSAGEM DISPONIBILIDADE --}}
                <div id="disponibilidade-msg" class="hidden text-sm font-semibold px-4 py-2.5 rounded-xl"></div>

                {{-- PREVIEW --}}
                <div id="preview" class="hidden rounded-xl border border-slate-100 overflow-hidden" style="background:#f8f9fb;">
                    <div class="px-4 py-2 border-b border-slate-100">
                        <p class="text-[10px] uppercase tracking-widest text-slate-400 font-semibold">Resumo estimado</p>
                    </div>
                    <div class="grid grid-cols-2 divide-x divide-slate-100">
                        <div class="px-4 py-3">
                            <p class="text-[10px] uppercase tracking-widest text-slate-400 font-semibold mb-1">Duração</p>
                            <p id="preview-duracao" class="text-base font-bold text-slate-800"></p>
                        </div>
                        <div class="px-4 py-3">
                            <p class="text-[10px] uppercase tracking-widest text-slate-400 font-semibold mb-1">Preço estimado</p>
                            <p class="text-base font-bold" style="color:#1e40af;">€ <span id="preview-preco"></span></p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- FOOTER DO CARD --}}
            <div class="flex items-center justify-end px-6 py-4 border-t border-slate-100" style="background:#fafbfc;">
                <button id="btnSubmit" type="submit" disabled
                        class="inline-flex items-center gap-2 text-white text-[13px] font-semibold px-6 py-2.5 rounded-xl transition-all duration-150 hover:-translate-y-px disabled:opacity-40 disabled:cursor-not-allowed disabled:translate-y-0"
                        style="background:linear-gradient(135deg,#2563eb 0%,#1e40af 100%); box-shadow:0 2px 8px rgba(30,64,175,0.25);">
                    Criar reserva
                </button>
            </div>

        </form>
    </div>

</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const msg = document.getElementById('disponibilidade-msg');
    const btn = document.getElementById('btnSubmit');

    function showMsg(texto, tipo) {
        msg.classList.remove('hidden', 'bg-green-50', 'text-green-700', 'bg-red-50', 'text-red-700');
        if (tipo === 'ok') {
            msg.classList.add('bg-green-50', 'text-green-700');
            msg.innerText = '✔ ' + texto;
        } else {
            msg.classList.add('bg-red-50', 'text-red-700');
            msg.innerText = '✖ ' + texto;
        }
    }

    function validarDatas() {
        const inicio = document.getElementById('data_reserva').value;
        const fim    = document.getElementById('data_prevista').value;
        if (!inicio || !fim) return false;
        const d1 = new Date(inicio), d2 = new Date(fim), agora = new Date();
        if (d1 < agora) { showMsg('Data de início no passado', 'erro'); btn.disabled = true; return false; }
        if (d2 <= d1)   { showMsg('Data de fim inválida', 'erro');      btn.disabled = true; return false; }
        return true;
    }

    async function verificarDisponibilidade() {
        const res = await fetch('{{ route('reservas.check') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({
                id_veiculo:   document.getElementById('id_veiculo').value,
                data_reserva: document.getElementById('data_reserva').value,
                data_prevista: document.getElementById('data_prevista').value
            })
        });
        const data = await res.json();
        if (!data.disponivel) { showMsg('Veículo indisponível neste período', 'erro'); btn.disabled = true; return false; }
        return true;
    }

    async function calcularPreview() {
        const inicio  = document.getElementById('data_reserva').value;
        const fim     = document.getElementById('data_prevista').value;
        const veiculo = document.getElementById('id_veiculo').value;
        if (!inicio || !fim || !veiculo) return;

        try {
            const res = await fetch('{{ route('reservas.preview') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ id_veiculo: veiculo, data_reserva: inicio, data_prevista: fim })
            });
            const data = await res.json();
            if (!data.success) return;

            const d1 = new Date(inicio), d2 = new Date(fim);
            const diffMin = (d2 - d1) / (1000 * 60);
            let horas = Math.floor(diffMin / 60);
            if ((diffMin % 60) > 30) horas++;
            horas = Math.max(1, horas);
            const dias = Math.floor(horas / 24);
            const horasRestantes = horas % 24;

            document.getElementById('preview-duracao').innerText = `${dias}d ${horasRestantes}h`;
            document.getElementById('preview-preco').innerText   = parseFloat(data.custo).toFixed(2);
            document.getElementById('preview').classList.remove('hidden');
        } catch (e) {
            // falha silenciosa — o preview é apenas informativo
        }
    }

    async function runAll() {
        document.getElementById('preview').classList.add('hidden');
        msg.classList.add('hidden');
        btn.disabled = true;

        if (!validarDatas()) return;
        const disponivel = await verificarDisponibilidade();
        if (!disponivel) return;

        showMsg('Disponível', 'ok');
        btn.disabled = false;
        calcularPreview();
    }

    document.getElementById('id_veiculo').addEventListener('change', runAll);
    document.getElementById('data_reserva').addEventListener('change', runAll);
    document.getElementById('data_prevista').addEventListener('change', runAll);

    // Auto-executar se o formulário recarregar com valores antigos (ex: erro de validação)
    const inicio = document.getElementById('data_reserva').value;
    const fim    = document.getElementById('data_prevista').value;
    if (inicio && fim) runAll();
});
</script>
</x-app-layout>
