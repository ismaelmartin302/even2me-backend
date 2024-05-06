<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser, HasName
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'nickname',
        'email',
        'phone',
        'biography',
        'location',
        'website',
        'birthday',
        'avatar',
        'banner',
        'type',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
            'birthday' => 'date',
            'password' => 'hashed',
        ];
    }
    public function getFilamentName(): string
    {
        return "{$this->username}";
    }
    public function canAccessPanel(Panel $panel): bool
    {
        return true; // _ Cambiar en el futuro para configurar el acceso en produccion
    }

    // Si Avatar o Banner se envia como null, lo modifica a default.png/defaultbanner.png
    public function setAvatarAttribute($value) {
        $this->attributes['avatar'] = $value ?? 'default.png';
    }
    public function setBannerAttribute($value) {
        $this->attributes['banner'] = $value ?? 'defaultbanner.png';
    }
    // Si Avatar o Banner se saca como null, lo modifica a default.png/defaultbanner.png
    public function getAvatarAttribute($value) {
        return $value ?? 'default.png';
    }
    public function getBannerAttribute($value) {
        return $value ?? 'defaultbanner.png';
    }
}
