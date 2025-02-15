<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'title',
        'content',
    ];

    public function getStoredAttribute(){
        return $this->created_at->diffForHumans();
    }

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn($image) => asset('storage/post/' . $image),
        );
    }
}
