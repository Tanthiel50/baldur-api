<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articles extends Model
{
    use HasFactory;

    protected $fillable = [
        'articleTitle',
        'articleThumbnail',
        'articleThumbnailTitle',
        'articleContent',
        'user_id',
        'articleSlug',
        'category_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(ArticleCategories::class, 'category_id');
    }

}
