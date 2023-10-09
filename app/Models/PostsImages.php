<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostsImages extends Model
{
    use HasFactory;
    protected $table='images_posts';
    protected $guarded = []; 
}
