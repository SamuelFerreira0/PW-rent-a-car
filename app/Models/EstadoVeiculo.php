<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoVeiculo extends Model
{
    protected $table = 'estado_veiculo';
    protected $primaryKey = 'id_estado_veiculo';
    public $timestamps = false;

    public function getNomeAttribute()
    {
        return $this->tipo ?? $this->descricao ?? 'N/A';
    }
}
