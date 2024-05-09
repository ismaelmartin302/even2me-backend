<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
    public function setPriceAttribute($value) {
        if ($value > 0) {
            $this->attributes['price'] = $value;
        } else {
            $this->attributes['price'] = 0;
        }
    }
    public function getPriceAttribute($value) {
        if ($value > 0) {
            return $value;  
        } else {
            return 'Free';
        }
    }
    public function setCapacityAttribute($value) {
        if ($value > 0) {
            $this->attributes['capacity'] = $value;
        } else {
            $this->attributes['capacity'] = 0;
        }
    }
    public function getCapacityAttribute($value) {
        if ($value > 0) {
            return $value;  
        } else {
            return 'Unlimited';
        }
    }
    public function setCurrentAttendeesAttribute($value) {
        if ($value > 0 && $value <= $this->attributes['capacity']) {
            $this->attributes['current_attendees'] = $value;
        } else if ($value > 0 && $value > $this->attributes['capacity']) {
            $this->attributes['current_attendees'] = $this->attributes['capacity'];
        } else {
            $this->attributes['current_attendees'] = 0;
        }
    }

    public function tags(): BelongsToMany 
    {
        return $this->belongsToMany(Tag::class);
    }
    public function reposts(): BelongsToMany 
    {
        return $this->belongsToMany(User::class, 'reposts', 'event_id', 'user_id');
    }
    
    public function likes(): BelongsToMany 
    {
        return $this->belongsToMany(User::class, 'likes', 'event_id', 'user_id');
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
