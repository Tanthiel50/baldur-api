<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticlePictures extends Model
{
    use HasFactory;

    protected $fillable = [
        'pictureTitle',
        'picturePath',
        'article_id',
    ];
}
