<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleCategories extends Model
{
    use HasFactory;

    protected $fillable = [
        'categoryName',
        'categorySlug',
        'categoryDescription',
    ];

    public function articles()
    {
        return $this->hasMany(Articles::class, 'category_id');
    }
}
