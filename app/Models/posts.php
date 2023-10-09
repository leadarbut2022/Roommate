<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class posts extends Model
{
    use HasFactory;
    protected $guarded = []; 

    public function images()
    {
        return $this->hasMany(PostsImages::class, 'post_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id','user_id');
    }


}
