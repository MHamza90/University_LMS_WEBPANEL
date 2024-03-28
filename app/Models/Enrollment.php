<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function course_detail()
    {
        return $this->belongsTO(Course::class,'course_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class,'program_id');
    }

    public function major()
    {
        return $this->belongsTo(Major::class,'major_id');
    }

    public function course()
    {
        return $this->hasMany(Course::class,'id');
    }

    public function course_one()
    {
        return $this->hasOne(Course::class,'id','course_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function term()
    {
        return $this->hasMany(Term::class,'id','term_id');
    }
    public function term_one()
    {
        return $this->hasOne(Term::class,'id','term_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}