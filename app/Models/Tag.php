<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tag extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function users(): BelongsToMany 
    {
        return $this->belongsToMany(User::class);
    }
    public function events(): BelongsToMany 
    {
        return $this->belongsToMany(Event::class);
    }
}
