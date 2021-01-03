<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $dates = ['created_at', 'updated_at'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

}
