<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'event_id',
        'content',
    ];
    public function user(): BelongsTo 
    {
        return $this->belongsTo(User::class);
    }
    public function event(): BelongsTo 
    {
        return $this->belongsTo(Event::class);
    }
    public function post_likes(): HasMany 
    {
        return $this->hasMany(PostLike::class);
    }
}
