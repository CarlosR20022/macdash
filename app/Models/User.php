<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'apellido_paterno',   // <-- Nuevo
        'apellido_materno',   // <-- Nuevo
        'username',           // <-- Nuevo
        'email',
        'password',
        'role_id',            // <-- Nuevo
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * ATAJO: Obtener el nombre completo del usuario.
     * Esto te servirá para mostrarlo en la esquina superior del panel.
     */
    public function getNombreCompletoAttribute()
    {
        // trim() quita los espacios extra por si alguien no tiene apellido materno
        return trim("{$this->name} {$this->apellido_paterno} {$this->apellido_materno}");
    }
}