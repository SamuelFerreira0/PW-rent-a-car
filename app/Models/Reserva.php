<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reserva extends Model
{
    public const ESTADO_ATIVA = 1;
    public const ESTADO_CONCLUIDA = 2;

    protected $table = 'reserva';
    protected $primaryKey = 'id_reserva';
    public $timestamps = false;

    protected $fillable = [
        'id_cliente',
        'id_veiculo',
        'data_reserva',
        'data_prevista',
        'id_estado_reserva',
        'preco_diario_usado',
        'custo_total',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class, 'id_veiculo', 'id_veiculo');
    }

    public function isConcluida(): bool
    {
        return (int) $this->id_estado_reserva === self::ESTADO_CONCLUIDA;
    }

    public static function calcularCusto($dataInicio, $dataFim, $precoDiario): float
    {
        $precoDiario = (float) $precoDiario;
    
        if ($precoDiario <= 0) {
            throw new \InvalidArgumentException('Preco diario deve ser maior que zero.');
        }
    
        $inicio = Carbon::parse($dataInicio);
        $fim = Carbon::parse($dataFim);
    
        if ($fim->lt($inicio)) {
            throw new \InvalidArgumentException('Data fim deve ser igual ou posterior a data inicio.');
        }
    
        // Total em minutos
        $minutosTotais = $inicio->diffInMinutes($fim);
    
        // Horas completas
        $horas = intdiv($minutosTotais, 60);
    
        // Minutos restantes
        $minutosRestantes = $minutosTotais % 60;
    
        // Regra de tolerância: só cobra mais 1 hora se passar 30 min
        if ($minutosRestantes > 30) {
            $horas++;
        }
    
        // Garantir mínimo de 1 hora
        $horas = max(1, $horas);
    
        // Converter para dias + horas
        $dias = intdiv($horas, 24);
        $horasRestantes = $horas % 24;
    
        // Preço por hora
        $precoHora = $precoDiario / 24;
    
        $custo = ($dias * $precoDiario) + ($horasRestantes * $precoHora);
    
        return round($custo, 2);
    }

    public static function temConflito($idVeiculo, $dataInicio, $dataFim, $ignorarId = null): bool
    {
        // IMPORTANTE: usar datetime completo (nao usar startOfDay)
        // para manter consistencia com calculo por horas
        $inicio = Carbon::parse($dataInicio);
        $fim = Carbon::parse($dataFim);

        return self::where('id_veiculo', $idVeiculo)
            ->where('id_estado_reserva', self::ESTADO_ATIVA)
            ->when($ignorarId !== null, function ($query) use ($ignorarId) {
                $query->where('id_reserva', '!=', $ignorarId);
            })
            ->where(function ($query) use ($inicio, $fim) {
                $query->whereBetween('data_reserva', [$inicio, $fim])
                    ->orWhereBetween('data_prevista', [$inicio, $fim])
                    ->orWhere(function ($q) use ($inicio, $fim) {
                        $q->where('data_reserva', '<=', $inicio)
                            ->where('data_prevista', '>=', $fim);
                    });
            })
            ->exists();
    }
}