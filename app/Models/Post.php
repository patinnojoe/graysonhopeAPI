<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //

    protected $fillable = [
        'title',
        'subtitle',
        'category',
        'date',
        'content',
        'images',
        'extra_details',
        'author',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
            'extra_details' => 'array',
        ];
    }
}
