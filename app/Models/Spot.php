<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spot extends Model
{
protected $fillable = [
    'created_by',
    'name',
    'latitude',
    'longitude',
    'description',
];

public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}
}
