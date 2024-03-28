<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function program()
    {
        return $this->hasMany(Program::class);
    }

    public function concentration()
    {
        return $this->hasMany(Concentration::class);
    }

    public function courses()
{
    return $this->hasMany(Course::class);
}
}
