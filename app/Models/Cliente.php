<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'cliente';
    protected $primaryKey = 'id_cliente';
    public $timestamps = false;

    protected $fillable = [
        'nome',
        'email',
        'nif',
        'telefone',
        'id_morada',
        'id_user',
    ];

    public function reservas()
    {
        return $this->hasMany(\App\Models\Reserva::class, 'id_cliente', 'id_cliente');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_user');
    }
}