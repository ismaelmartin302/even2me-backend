<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'event_id',
        'parent_comment_id',
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
    public function comments(): HasMany 
    {
        return $this->hasMany(Comment::class, 'parent_comment_id'); 
    }
    public function parentComment(): BelongsTo 
    {
        return $this->belongsTo(Comment::class, 'parent_comment_id'); 
    }
    public function likes(): BelongsToMany 
    {
        return $this->belongsToMany(User::class, 'comment_likes', 'comment_id', 'user_id');
    }
}
