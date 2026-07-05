<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
protected $fillable = [
    'visit_id',
    'user_id',
    'body',
];

public function visit()
{
    return $this->belongsTo(Visit::class);
}

public function user()
{
    return $this->belongsTo(User::class);
}
}
