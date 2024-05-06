<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event_tag extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_id',
        'tag_id',
    ];
    public function tag(): BelongsTo 
    {
        return $this->belongsTo(Tag::class);
    }
    public function event(): BelongsTo 
    {
        return $this->belongsTo(Event::class);
    }
}
