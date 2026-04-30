<div style="max-width: 760px; margin: 32px auto; padding: 0 16px; font-family: Arial, sans-serif; color: #1f2937;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 style="margin: 0; font-size: 28px;">Editar Reserva</h1>

        <a href="/reservas" style="text-decoration: none; color: #374151; border: 1px solid #d1d5db; padding: 8px 12px; border-radius: 8px; font-weight: 600;">
            Voltar
        </a>
    </div>

    @if ($errors->any())
        <div style="margin-bottom: 16px; padding: 12px; background: #fee2e2; color: #991b1b; border-radius: 8px;">
            <strong>Erros:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/reservas/{{ $reserva->id_reserva }}" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 20px;">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 14px;">
            <label style="font-weight: 600;">Cliente</label>
            <select name="id_cliente" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id_cliente }}"
                        {{ $reserva->id_cliente == $cliente->id_cliente ? 'selected' : '' }}>
                        {{ $cliente->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 14px;">
            <label style="font-weight: 600;">Veículo</label>
            <select name="id_veiculo" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
                @foreach($veiculos as $veiculo)
                    <option value="{{ $veiculo->id_veiculo }}"
                        {{ $reserva->id_veiculo == $veiculo->id_veiculo ? 'selected' : '' }}>
                        {{ $veiculo->marca }} {{ $veiculo->modelo }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 14px;">
            <label style="font-weight: 600;">Data início</label>
            <input type="datetime-local" name="data_reserva"
                value="{{ \Carbon\Carbon::parse($reserva->data_reserva)->format('Y-m-d\TH:i') }}"
                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="font-weight: 600;">Data fim</label>
            <input type="datetime-local" name="data_prevista"
                value="{{ \Carbon\Carbon::parse($reserva->data_prevista)->format('Y-m-d\TH:i') }}"
                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
        </div>

        <button type="submit" style="background: #2563eb; color: white; padding: 10px 16px; border: none; border-radius: 8px; cursor: pointer;">
            Guardar Alterações
        </button>

    </form>
</div>