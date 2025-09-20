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
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'role_id'           => 'integer',
        ];
    }

    // Accessor untuk nama role
    public function getRoleNameAttribute(): string
    {
        return match ($this->role_id) {
            1 => 'admin',
            2 => 'kasir',
            3 => 'pimpinan',
            default => 'unknown',
        };
    }
}
