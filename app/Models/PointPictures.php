<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointPictures extends Model
{
    use HasFactory;

    protected $fillable = [
        'pictureTitle',
        'picturePath',
        'point_id',
    ];
}
