<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterestPoints extends Model
{
    use HasFactory;

    protected $fillable = [
        'pointName',
        'pointSlug',
        'pointTitle',
        'pointDescription',
        'pointThumbnail',
        'pointThumbnailTitle',
        'user_id',
        'pointtips',
        'pointAdress',
        'pointSpeciality',
        'pointContent',
        'pointPicture_id',
        'pointCategories_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pointPicture()
    {
        return $this->belongsTo(PointPictures::class);
    }

    public function pointCategories()
    {
        return $this->belongsTo(PointCategories::class);
    }
}
