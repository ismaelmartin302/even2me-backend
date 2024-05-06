<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
