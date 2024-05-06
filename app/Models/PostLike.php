<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostLike extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'event_id',
        'comment_id',
    ];
    public function user(): BelongsTo 
    {
        return $this->belongsTo(User::class);
    }
    public function event(): BelongsTo 
    {
        return $this->belongsTo(Event::class);
    }
    public function comment(): BelongsTo 
    {
        return $this->belongsTo(Comment::class);
    }
}
