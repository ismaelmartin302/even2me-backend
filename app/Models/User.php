<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use function Filament\Support\is_app_url;

class User extends Authenticatable implements FilamentUser, HasName, HasAvatar, MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;

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
    public function getFilamentAvatarUrl(): ?string
    {
        return "{$_ENV['APP_URL']}/storage/{$this->avatar}";
    }
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


    
    /* MUTACIONES DE ATRIBUTOS ----------------------------------- */
    public function setAvatarAttribute($value) {
        $this->attributes['avatar'] = $value ?? 'default.png';
    }
    public function getAvatarAttribute($value) {
        return $value ?? 'default.png';
    }
    public function setBannerAttribute($value) {
        $this->attributes['banner'] = $value ?? 'defaultbanner.png';
    }
    public function getBannerAttribute($value) {
        return $value ?? 'defaultbanner.png';
    }
    public function setNicknameAttribute($value) {
        $this->attributes['nickname'] = $value ?? $this->attributes['username'];
    }
    public function getNicknameAttribute($value) {
        return $value ?? $this->attributes['username'];
    }


    
    public function canAccessFilament(): bool
    {
        // return str_ends_with($this->email, '@even2me.com') && $this->hasVerifiedEmail() && ($this->type === 'moderator' || $this-type === 'admin'); // <- Este es el que hay que usar en produccion
        return $this->type === 'moderator' || $this->type === 'admin';
    }



    /* RELACIONES -----------------------------------------------------------*/
    public function followings(): HasMany 
    {
        return $this->hasMany(Follower::class, 'follower_id', 'id');
    }
    public function followers(): HasMany 
    {
        return $this->hasMany(Follower::class, 'following_id', 'id');
    }
    public function tags(): BelongsToMany 
    {
        return $this->belongsToMany(Tag::class);
    }
    public function repost(): BelongsToMany 
    {
        return $this->belongsToMany(Event::class, 'reposts', 'user_id', 'event_id');
    }
    public function likedEvents(): BelongsToMany 
    {
        return $this->belongsToMany(Event::class, 'post_likes', 'user_id', 'event_id');
    }
    public function likedComments(): BelongsToMany 
    {
        return $this->belongsToMany(Comment::class, 'comment_likes', 'user_id', 'comment_id');
    }
    public function comments(): HasMany 
    {
        return $this->hasMany(Comment::class);
    }
    public function events(): HasMany 
    {
        return $this->hasMany(Event::class);
    }
}
