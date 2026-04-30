<div style="max-width: 760px; margin: 32px auto; padding: 0 16px; font-family: Arial, sans-serif; color: #1f2937;">
    <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 20px;">
        <h1 style="margin: 0; font-size: 28px;">Editar Veiculo</h1>
        <a href="/veiculos" style="text-decoration: none; color: #374151; border: 1px solid #d1d5db; padding: 8px 12px; border-radius: 8px; font-weight: 600;">
            Voltar
        </a>
    </div>

    @if ($errors->any())
        <div style="margin-bottom: 16px; padding: 12px 14px; border: 1px solid #fca5a5; background: #fef2f2; color: #991b1b; border-radius: 8px;">
            <strong>Existem erros no formulario:</strong>
            <ul style="margin: 8px 0 0 18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/veiculos/{{ $veiculo->id_veiculo }}" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 18px; box-shadow: 0 1px 2px rgba(0,0,0,0.04);">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 14px;">
            <label for="matricula" style="display: block; margin-bottom: 6px; font-weight: 600;">Matricula</label>
            <input id="matricula" type="text" name="matricula" value="{{ old('matricula', $veiculo->matricula) }}" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
        </div>

        <div style="margin-bottom: 14px;">
            <label for="marca" style="display: block; margin-bottom: 6px; font-weight: 600;">Marca</label>
            <input id="marca" type="text" name="marca" value="{{ old('marca', $veiculo->marca) }}" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
        </div>

        <div style="margin-bottom: 14px;">
            <label for="modelo" style="display: block; margin-bottom: 6px; font-weight: 600;">Modelo</label>
            <input id="modelo" type="text" name="modelo" value="{{ old('modelo', $veiculo->modelo) }}" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
        </div>

        <div style="margin-bottom: 14px;">
            <label for="id_categoria" style="display: block; margin-bottom: 6px; font-weight: 600;">Categoria</label>
            <select id="id_categoria" name="id_categoria" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                <option value="">Selecione uma categoria</option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id_categoria }}" @selected(old('id_categoria', $veiculo->id_categoria) == $categoria->id_categoria)>
                        {{ $categoria->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 14px;">
            <label for="id_estado_veiculo" style="display: block; margin-bottom: 6px; font-weight: 600;">Estado</label>
            <select id="id_estado_veiculo" name="id_estado_veiculo" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                <option value="">Selecione um estado</option>
                @foreach ($estadosVeiculo as $estado)
                    <option value="{{ $estado->id_estado_veiculo }}" @selected(old('id_estado_veiculo', $veiculo->id_estado_veiculo) == $estado->id_estado_veiculo)>
                        {{ $estado->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 14px;">
            <label for="id_localizacao" style="display: block; margin-bottom: 6px; font-weight: 600;">Localizacao</label>
            <select id="id_localizacao" name="id_localizacao" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                <option value="">Selecione uma localizacao</option>
                @foreach ($localizacoes as $localizacao)
                    <option value="{{ $localizacao->id_localizacao }}" @selected(old('id_localizacao', $veiculo->id_localizacao) == $localizacao->id_localizacao)>
                        {{ $localizacao->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 18px;">
            <label for="preco_diario" style="display: block; margin-bottom: 6px; font-weight: 600;">Preco diario</label>
            <input id="preco_diario" type="number" step="0.01" min="0.01" name="preco_diario" value="{{ old('preco_diario', $veiculo->preco_diario) }}" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
        </div>

        <button type="submit" style="background: #2563eb; color: #fff; border: none; padding: 10px 16px; border-radius: 8px; font-weight: 700; cursor: pointer;">
            Guardar Alteracoes
        </button>
    </form>
</div>
