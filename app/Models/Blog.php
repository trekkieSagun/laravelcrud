<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{

    protected $fillable = [
        'title',
        'description',
        'likes',
        'image'
    ];


    protected $guarded = [];

    public $timestamps = true;
    protected $attributes = [
        'published' => false,
    ];
}
