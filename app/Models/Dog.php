<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dog extends Model
{
  protected $fillable = [
    'user_id',
    'name',
    'breed',
    'age',
    'favourite_food',
    'description',
    'photo_path',
];

public function user()
{
    return $this->belongsTo(User::class);
}
}
