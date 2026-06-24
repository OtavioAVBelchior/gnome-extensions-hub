<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'gitlab_token',
        'github_token',
        'gitlab_id',
        'github_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'gitlab_token',
        'github_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'gitlab_token' => 'encrypted',
        'github_token' => 'encrypted',
    ];

    public function extensions()
    {
        return $this->hasMany(Extension::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}