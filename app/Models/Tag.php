<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tag extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function user_tags(): HasMany 
    {
        return $this->hasMany(User_tag::class);
    }
    public function event_tags(): HasMany 
    {
        return $this->hasMany(Event_tag::class);
    }
}
