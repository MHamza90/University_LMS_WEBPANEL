<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Major;
use DB;

class EnrollmentController extends Controller
{
    public function enrollment()
    {
        // return Enrollment::with("program.major")->get();
         $courses =  User::with(['course.course_detail.major','course.course_detail.term.program'])->get();
         $inrollment =  Enrollment::get();
         
         $program_id = [];
         $major_id = [];
         $term_id = [];
         $user_id = [];
         $course_id = [];
         foreach($inrollment as $item)
         {
            array_push($program_id,$item->program_id);
            array_push($major_id,$item->major_id);
            array_push($term_id,$item->term_id);
            array_push($user_id,$item->user_id);
            array_push($course_id,$item->course_id);
         }
       


    $courses = User::with(['enrollment.course_one.term_one.program.major'])
    ->whereHas('enrollment', function ($q) {
        $q->orderBy('major_id', 'ASC')  ->orderBy('program_id', 'ASC')
        ->orderBy('major_id', 'ASC');
    })
  
    ->get();
    
    
        
        return view("admin.enrollment.index",compact('courses'));
    }

    public function mergeAllData($data){
        $my_array = [];
        foreach ($data as $key => $value) {
             
            $my_array[$value->user_id] = $value;
            // array_push($my_array,$value->major);
           
        }
        return $my_array;
    }   
    public function enrollment_status(Request $request)
    {
        // return $request->all();
         $statusChange = Enrollment::find($request->id)->update(['status'=>$request->status]);
        if($statusChange)
        {
            return array('message'=>'Enrollment status  has been changed successfully','type'=>'success');
        }else{
            return array('message'=>'Enrollment status has not changed please try again','type'=>'error');
        }
    }
}
