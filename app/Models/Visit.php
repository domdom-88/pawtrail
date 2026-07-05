<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
protected $fillable = [
    'spot_id',
    'dog_id',
    'user_id',
    'notes',
    'visited_at',
];

protected function casts(): array
{
    return [
        'visited_at' => 'datetime',
    ];
}

public function spot()
{
    return $this->belongsTo(Spot::class);
}

public function dog()
{
    return $this->belongsTo(Dog::class);
}

public function user()
{
    return $this->belongsTo(User::class);
}
public function comments()
{
    return $this->hasMany(Comment::class);
}
public function images()
{
    return $this->hasMany(VisitImage::class);
}

}
