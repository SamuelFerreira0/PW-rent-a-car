<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    public function isFuncionarioOrAdmin(): bool
    {
        return $this->isAdmin() || $this->funcionario !== null;
    }

    public function clienteId(): ?int
    {
        return $this->cliente?->id_cliente;
    }

    public function funcionario()
    {
        return $this->hasOne(Funcionario::class, 'id_user', 'id');
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'id_user');
    }
}
