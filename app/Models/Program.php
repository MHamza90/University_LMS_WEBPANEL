<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function major()
    {
        return $this->belongsTo(Major::Class,'major_id','id');
    }

    public function concentration()
    {
        return $this->belongsTo(Concentration::class);
    }

    public function term()
    {
        return $this->hasMany(Term::class);
    }


    // public function course()
    // {
    //     return $this->hasManyThrough(Course::class, Term::class);
    // }


    // public function courses()
    // {
    //     return $this->belongsToMany(Course::class);
    // }

    public function courses()
    {
        return $this->hasMany(Course::class,'id');
    }

   
}
