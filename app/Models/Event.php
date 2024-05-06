<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',    
        'description',    
        'location',    
        'price',    
        'capacity',    
        'current_attendees',    
        'category',    
        'picture',    
        'website',    
        'starts_at',    
        'finish_in',    
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'finish_in' => 'datetime',
        ];
    }
    public function event_tags(): HasMany 
    {
        return $this->hasMany(Event_tag::class);
    }
    public function reposts(): HasMany 
    {
        return $this->hasMany(Repost::class);
    }
    public function post_likes(): HasMany 
    {
        return $this->hasMany(Post_like::class);
    }
    public function comments(): HasMany 
    {
        return $this->hasMany(Comment::class);
    }
    public function user(): BelongsTo 
    {
        return $this->belongsTo(User::class);
    }
}
