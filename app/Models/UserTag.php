<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTag extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'tag_id',
    ];
    public function tag(): BelongsTo 
    {
        return $this->belongsTo(Tag::class);
    }
    public function user(): BelongsTo 
    {
        return $this->belongsTo(User::class);
    }
}
