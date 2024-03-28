<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function program()
    {
        return $this->belongsTo(Program::Class,'program_id','id');
    }

    public function major()
    {
        return $this->belongsTo(Major::Class,'major_id','id');
    }

    public function course()
    {
       
        return $this->hasMany(Course::class);
    }

    public function concentration()
    {
        return $this->belongsTo(Concentration::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::Class,'semster_id','id');
    }
}
