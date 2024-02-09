<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PointCategories extends Model
{
    use HasFactory;

    protected $fillable = [
        'pointCategoryName',
        'pointCategorySlug',
        'pointCategoryDescription',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pointCategory) {
            if (empty($pointCategory->pointCategorySlug)) {
                $pointCategory->pointCategorySlug = Str::slug($pointCategory->pointCategoryName, '-');
            }
        });
    }

    public function interestPoints()
    {
        return $this->hasMany(interestPoints::class, 'pointCategories_id');
    }
}
