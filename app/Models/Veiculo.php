<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Veiculo extends Model
{
    protected $table = 'veiculo';
    protected $primaryKey = 'id_veiculo';
    public $timestamps = false;
    protected $fillable = [
        'matricula',
        'marca',
        'modelo',
        'preco_diario',
        'id_categoria',
        'id_estado_veiculo',
        'id_localizacao',
    ];

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'id_veiculo');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    public function estadoVeiculo()
    {
        return $this->belongsTo(EstadoVeiculo::class, 'id_estado_veiculo', 'id_estado_veiculo');
    }

    public function localizacao()
    {
        return $this->belongsTo(Localizacao::class, 'id_localizacao', 'id_localizacao');
    }
}