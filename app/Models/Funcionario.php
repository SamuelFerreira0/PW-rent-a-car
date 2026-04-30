<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    protected $table = 'funcionario';
    protected $primaryKey = 'id_funcionario';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_user', 'id');
    }
}