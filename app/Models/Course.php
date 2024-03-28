<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function term()
    {
        return $this->belongsTo(Term::class,'term_id','id');
    }

    public function major()
    {
        return $this->belongsTo(Major::class,'major_id','id');
    }

    public function course_detail()
    {
        return $this->belongsTo(Enrollment::class,'course_id');
    }


    public function program()
    {
        return $this->belongsTo(Program::class,'program_id','id');
    }

    public function term_one()
    {
        return $this->hasOne(Term::class,'id','term_id');
    }

    public function concentration()
    {
        return $this->belongsTo(Concentration::class);
    }


    
}
