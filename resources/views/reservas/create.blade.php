<x-app-layout>
<div class="min-h-screen py-8 px-4" style="background:#f8f9fb;">
<div class="max-w-xl mx-auto">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-7">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Nova Reserva</h1>
            <p class="text-sm text-slate-400 mt-0.5">Preenche os dados abaixo</p>
        </div>
        <a href="/reservas"
           class="text-xs font-semibold text-slate-500 hover:text-slate-900 border border-slate-200 hover:border-slate-300 bg-white px-4 py-2 rounded-xl transition-colors">
            ← Voltar
        </a>
    </div>

    {{-- FORM CARD --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">

        <form method="POST" action="/reservas">
            @csrf

            <div class="p-6 flex flex-col gap-5">

                {{-- CLIENTE (só funcionários) --}}
                @if(auth()->user()->funcionario)
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-slate-400 font-semibold mb-1.5">
                        Cliente
                    </label>
                    <select name="id_cliente"
                            class="w-full px-4 py-2.5 text-sm text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition">
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id_cliente }}">{{ $cliente->nome }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- VEÍCULO --}}
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-slate-400 font-semibold mb-1.5">
                        Veículo
                    </label>
                    <select id="id_veiculo" name="id_veiculo"
                            class="w-full px-4 py-2.5 text-sm text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition">
                        @foreach($veiculos as $veiculo)
                            <option value="{{ $veiculo->id_veiculo }}" data-preco="{{ $veiculo->preco_diario }}">
                                {{ $veiculo->marca }} {{ $veiculo->modelo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- DATAS --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-slate-400 font-semibold mb-1.5">
                            Início
                        </label>
                        <input id="data_reserva" type="datetime-local" name="data_reserva"
                               class="w-full px-4 py-2.5 text-sm text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-slate-400 font-semibold mb-1.5">
                            Fim
                        </label>
                        <input id="data_prevista" type="datetime-local" name="data_prevista"
                               class="w-full px-4 py-2.5 text-sm text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition">
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
                <button id="btnSubmit" type="submit"
                        class="inline-flex items-center gap-2 text-white text-[13px] font-semibold px-6 py-2.5 rounded-xl transition-colors disabled:opacity-40 disabled:cursor-not-allowed"
                        style="background:#1e40af;">
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
        const fim = document.getElementById('data_prevista').value;
        if (!inicio || !fim) return false;
        const d1 = new Date(inicio), d2 = new Date(fim), agora = new Date();
        if (d1 < agora) { showMsg('Data de início no passado', 'erro'); btn.disabled = true; return false; }
        if (d2 <= d1)   { showMsg('Data de fim inválida', 'erro');      btn.disabled = true; return false; }
        return true;
    }

    async function verificarDisponibilidade() {
        const res = await fetch('/reservas/check', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({
                id_veiculo: document.getElementById('id_veiculo').value,
                data_reserva: document.getElementById('data_reserva').value,
                data_prevista: document.getElementById('data_prevista').value
            })
        });
        const data = await res.json();
        if (!data.disponivel) { showMsg('Veículo indisponível neste período', 'erro'); btn.disabled = true; return false; }
        return true;
    }

    function calcularPreview() {
        const inicio = document.getElementById('data_reserva').value;
        const fim = document.getElementById('data_prevista').value;
        const select = document.getElementById('id_veiculo');
        const preco = select.options[select.selectedIndex].dataset.preco;
        if (!inicio || !fim || !preco) return;
        const d1 = new Date(inicio), d2 = new Date(fim);
        let diffMin = (d2 - d1) / (1000 * 60);
        if (diffMin <= 0) return;
        let horas = Math.floor(diffMin / 60);
        if ((diffMin % 60) > 30) horas++;
        horas = Math.max(1, horas);
        const dias = Math.floor(horas / 24);
        const horasRestantes = horas % 24;
        const precoHora = preco / 24;
        const custo = (dias * preco) + (horasRestantes * precoHora);
        document.getElementById('preview-duracao').innerText = `${dias}d ${horasRestantes}h`;
        document.getElementById('preview-preco').innerText = custo.toFixed(2);
        document.getElementById('preview').classList.remove('hidden');
    }

    async function runAll() {
        document.getElementById('preview').classList.add('hidden');
        msg.classList.add('hidden');
        const valido = validarDatas();
        if (!valido) return;
        const disponivel = await verificarDisponibilidade();
        if (!disponivel) return;
        showMsg('Disponível', 'ok');
        btn.disabled = false;
        calcularPreview();
    }

    document.getElementById('id_veiculo').addEventListener('change', runAll);
    document.getElementById('data_reserva').addEventListener('change', runAll);
    document.getElementById('data_prevista').addEventListener('change', runAll);
});
</script>
</x-app-layout>