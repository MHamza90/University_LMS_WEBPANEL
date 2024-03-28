<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class SchoolController extends Controller
{
    public function getSchool(){
        $data = School::where('status',1)->get();
        if(count($data) == 0){
            return response()->json(['data'=>$data,'success' => false]);
        }
        return response()->json(['data'=>$data,'success' => true]);
    }
}
