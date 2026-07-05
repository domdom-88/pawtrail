<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Spot extends Model
{
protected $fillable = [
    'created_by',
    'name',
    'slug',
    'latitude',
    'longitude',
    'description',
];

public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}

public function pawedByUsers()
{
    return $this->belongsToMany(User::class)->withTimestamps();
}

protected static function booted(): void
{
    static::creating(function (Spot $spot) {
        $spot->slug = Str::slug($spot->name);
    });
}

public function getRouteKeyName(): string
{
    return 'slug';
}

public function visits()
{
    return $this->hasMany(Visit::class);
}
}

