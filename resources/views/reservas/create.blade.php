<div style="max-width: 700px; margin: 40px auto; font-family: system-ui;">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
        <h1 style="font-size:28px; font-weight:600;">Nova Reserva</h1>
        <a href="/reservas" style="padding:8px 14px; border:1px solid #ddd; border-radius:10px; text-decoration:none;">
            Voltar
        </a>
    </div>

    <form method="POST" action="/reservas"
        style="background:white; padding:24px; border-radius:16px; box-shadow:0 8px 20px rgba(0,0,0,0.06);">

        @csrf

        @if(auth()->user()->funcionario)
            <div style="margin-bottom:18px;">
                <label style="font-weight:600;">Cliente</label>

                <select name="id_cliente"
                    style="width:100%; padding:12px; border-radius:10px; border:1px solid #ddd;">
                    
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id_cliente }}">
                            {{ $cliente->nome }}
                        </option>
                    @endforeach

                </select>
            </div>
        @endif

        <div style="margin-bottom:18px;">
            <label style="font-weight:600;">Veículo</label>
            <select id="id_veiculo" name="id_veiculo"
                style="width:100%; padding:12px; border-radius:10px; border:1px solid #ddd;">
                @foreach($veiculos as $veiculo)
                    <option value="{{ $veiculo->id_veiculo }}"
                        data-preco="{{ $veiculo->preco_diario }}">
                        {{ $veiculo->marca }} {{ $veiculo->modelo }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="display:flex; gap:12px; margin-bottom:18px;">
            <div style="flex:1;">
                <label style="font-weight:600;">Início</label>
                <input id="data_reserva" type="datetime-local" name="data_reserva"
                    style="width:100%; padding:12px; border-radius:10px; border:1px solid #ddd;">
            </div>

            <div style="flex:1;">
                <label style="font-weight:600;">Fim</label>
                <input id="data_prevista" type="datetime-local" name="data_prevista"
                    style="width:100%; padding:12px; border-radius:10px; border:1px solid #ddd;">
            </div>
        </div>

        <div id="disponibilidade-msg" style="margin-bottom:10px; font-weight:600;"></div>

        <div id="preview"
            style="display:none; background:#f9fafb; padding:14px; border-radius:12px; margin-bottom:15px; border:1px solid #eee;">
            <div><strong>Duração:</strong> <span id="preview-duracao"></span></div>
            <div><strong>Preço estimado:</strong> € <span id="preview-preco"></span></div>
        </div>

        <button id="btnSubmit" type="submit"
            style="background:#2563eb; color:white; padding:12px 18px; border:none; border-radius:10px; font-weight:600;">
            Criar Reserva
        </button>

    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    console.log("SCRIPT OK");

    const msg = document.getElementById('disponibilidade-msg');
    const btn = document.getElementById('btnSubmit');

    function validarDatas() {
        const inicio = document.getElementById('data_reserva').value;
        const fim = document.getElementById('data_prevista').value;

        if (!inicio || !fim) return false;

        const d1 = new Date(inicio);
        const d2 = new Date(fim);
        const agora = new Date();

        msg.style.display = 'block';

        if (d1 < agora) {
            msg.innerText = "✖ Data no passado";
            msg.style.color = "red";
            btn.disabled = true;
            return false;
        }

        if (d2 <= d1) {
            msg.innerText = "✖ Data de fim inválida";
            msg.style.color = "red";
            btn.disabled = true;
            return false;
        }

        return true;
    }

    async function verificarDisponibilidade() {
        const veiculo = document.getElementById('id_veiculo').value;
        const inicio = document.getElementById('data_reserva').value;
        const fim = document.getElementById('data_prevista').value;

        const res = await fetch('/reservas/check', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                id_veiculo: veiculo,
                data_reserva: inicio,
                data_prevista: fim
            })
        });

        const data = await res.json();

        if (!data.disponivel) {
            msg.innerText = "✖ Veículo indisponível";
            msg.style.color = "red";
            btn.disabled = true;
            return false;
        }

        return true;
    }

    function calcularPreview() {
        const inicio = document.getElementById('data_reserva').value;
        const fim = document.getElementById('data_prevista').value;

        const select = document.getElementById('id_veiculo');
        const preco = select.options[select.selectedIndex].dataset.preco;

        if (!inicio || !fim || !preco) return;

        const dataInicio = new Date(inicio);
        const dataFim = new Date(fim);

        let diffMin = (dataFim - dataInicio) / (1000 * 60);

        if (diffMin <= 0) return;

        let horas = Math.floor(diffMin / 60);
        let minutosRestantes = diffMin % 60;

        if (minutosRestantes > 30) horas++;

        horas = Math.max(1, horas);

        const dias = Math.floor(horas / 24);
        const horasRestantes = horas % 24;

        const precoHora = preco / 24;
        const custo = (dias * preco) + (horasRestantes * precoHora);

        // 👇 aqui estava o problema
        document.getElementById('preview-duracao').innerText =
            `${dias}d ${horasRestantes}h`;

        document.getElementById('preview-preco').innerText =
            custo.toFixed(2);

        document.getElementById('preview').style.display = 'block';
    }

    async function runAll() {
        const valido = validarDatas();
        if (!valido) return;

        const disponivel = await verificarDisponibilidade();
        if (!disponivel) return;

        msg.innerText = "✔ Disponível";
        msg.style.color = "green";
        btn.disabled = false;

        await calcularPreview();
    }

    document.getElementById('id_veiculo').addEventListener('change', runAll);
    document.getElementById('data_reserva').addEventListener('change', runAll);
    document.getElementById('data_prevista').addEventListener('change', runAll);

});
</script>