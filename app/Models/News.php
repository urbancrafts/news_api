<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    
    protected $fillable = [ 
        'id',
        'title',
        'description',
        'author',
        'source',
        'url',
        'image_url',
        'published_at'
    ];
}
