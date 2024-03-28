<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concentration extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function major()
    {
        return $this->belongsTo(Major::class,'major_id','id');
    }



    public function courses()
{
    return $this->hasMany(Course::class);
}
}
