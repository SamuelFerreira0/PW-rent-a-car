<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Localizacao extends Model
{
    protected $table = 'localizacao';
    protected $primaryKey = 'id_localizacao';
    public $timestamps = false;

    public function getNomeAttribute()
    {
        return $this->nome_localizacao ?? 'N/A';
    }
}
