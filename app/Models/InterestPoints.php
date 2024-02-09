<?php

namespace App\Models;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'pointCategories_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function pointCategories()
    {
        return $this->belongsTo(PointCategories::class);
    }
}


