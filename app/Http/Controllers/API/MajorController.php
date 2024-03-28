<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Major;
use App\Models\Concentration;
use App\Models\Program;
use App\Models\Term;
use App\Models\Course;
use App\Models\DegreePlan;
use App\Models\Enrollment;
use App\Models\Notification;
use App\Models\Semester;
use Carbon\Carbon;


class MajorController extends Controller
{
    public function getMajor()
    {
        $data  = Major::Where('status',1)->get();
       
        $status = true;
        if(count($data)<0)
        {
            $data = [];
            $status = false;
        }
        return response()->json(['data'=>$data,'success' => $status]);
    }

    public function getMoncentration(Request $request)
    {
        $data  = Concentration::Where('status',1)->where('major_id',$request->major_id)->get();
       
        $status = false;
        if(count($data)>0)
        {
            $data = $data;
            $status = true;
        }
        return response()->json(['data'=>$data,'success' => $status]);
    }

    public function getProgram(Request $request)
    {
        $data  = Program::Where('status',1)->where('concentration_id',$request->concentration_id)->get();
       
        $status = false;
        if(count($data)>0)
        {
            $data = $data;
            $status = true;
        }
        return response()->json(['data'=>$data,'success' => $status]);
    }

    public function saveDegreePlan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'major_id' => 'required',
            'concentration_id' => 'required',
            'program_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->messages()->first(),'success' => false]);
         }
         $check = Program::where(['major_id'=>$request->major_id,'id'=>$request->program_id])->first();
         if($check){
            $path = asset("documents/files/$check->file");
            return response()->json(['path'=>$path,'data'=>$check,'success' => true]);
         }
       
     
         return response()->json(['data'=>$check,'success' => false]);
    }

    public function getTerm(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'program_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->messages()->first(),'success' => false]);
         }

      $program_id = DegreePlan::where('user_id',auth()->user()->id)->pluck('program_id');
      $major_id = DegreePlan::where('user_id',auth()->user()->id)->pluck('major_id');

    //   $data = Term::whereIn('program_id',$program_id)->get();
      $data = Term::with(['semester'])->where('program_id',$request->program_id)->get();
      $status = false;
        if(count($data)>0)
        {
            $data = $data;
            $status = true;
        }
        return response()->json(['data'=>$data,'success' => $status]);
    }

    public function getCourse(Request $request)
    {
        // return "hi";
        // return Enrollment::where('user_id',auth()->user()->id)->pluck('course_id');
        $course_id = Enrollment::where('user_id',auth()->user()->id)->where(['concentration_id'=>$request->concentration_id,'program_id'=>$request->program_id,'major_id'=>$request->major_id])->pluck('course_id');
        // $myCourse = Enrollment::where('user_id',auth()->user()->id)->where(['concentration_id'=>$request->concentration_id,'program_id'=>$request->program_id,'major_id'=>$request->major_id])->get();
        $myCourse = Course::whereNotIn('id',$course_id)->get();

        // $data = Course::where('term_id',$request->term_id)->whereNotIn('id',$course_id)->get();
        $data = Course::where(['term_name'=>$request->term_name,'year'=>$request->year,'concentration_id'=>$request->concentration_id,'program_id'=>$request->program_id])->whereNotIn('id',$course_id)->get();
        $status = false;
        if(count($data)>0)
        {
            $data = $data;
            $status = true;
        }
        return response()->json(['data'=>$data,'myCourse'=>$myCourse,'success' => $status]);
    }

    public function saveCourse1(Request $request)
    {
        // return $request->all();
        $validator = Validator::make($request->all(), [
            'course_id' => 'required',
            'program_id' => 'required',
            'major_id' => 'required',
            'term_name' => 'required',
            'year' => 'required',
            // 'term_id' => 'required',
        ]);

    if ($validator->fails()) {
          return response()->json(['message'=>$validator->messages()->first(),'success' => false]);
        }
       $string =  $request->course_id;
       $string = str_replace(array("[", "]", "'"), "", $string); 
       $array = explode(",", $string);

    //   $checkStudent = Enrollment::where(['user_id'=>auth()->user()->id])->Where('major_id',$request->major_id)->where('program_id',$request->program_id)->whereIn('course_id',$array)->get();
       $checkMajor = Enrollment::where(['user_id'=>auth()->user()->id])->Where('major_id',$request->major_id)->first();
       $checkProgram = Enrollment::where(['user_id'=>auth()->user()->id])->Where('program_id',$request->program_id)->first();
       if($checkMajor)
       {
          if($checkProgram)
          {
            $check = Enrollment::where(['user_id'=>auth()->user()->id])->WhereIn('course_id',$array)->get();
            if(count($check)>0)
                return response()->json(['message'=>'You have already enrolled this course','success' => false]);
                
            foreach($array as $item)
                {
                    $input = $request->all();
                    $input['user_id'] =auth()->user()->id;
                    $input['course_id'] =$item;
                    $add = Enrollment::Create($input);
                }
                if($add)
                {
                        $message = 'You have enrolled successfully';
                        $success = true;
                        $data = [];
                        $data['description'] =  auth()->user()->first_name.' '. auth()->user()->last_name. ' has buy a course against '.$checkProgram->major->major_name.' and Program is:- '.$checkProgram->program->name.' at that time '.Carbon::now();
                        $data['user_id'] = auth()->user()->id;
                        $data['type']    = 'enroll';
                        $data['date_time'] = Carbon::now();
                        Notification::Create($data);
                }else{
                        $message = 'Error! Something went wrong please try again';
                        $success = false;
                }
               return response()->json(['message'=>$message,'success' => $success]);
          }else{
            return response()->json(['message'=>"You have already enrolled program",'success' => false]);
          }
            
       }else{
            if(!$checkMajor)
            {
               
                foreach($array as $item)
                {
                    $input = $request->except(['course_id'],$request->all());
                    $input['user_id'] =auth()->user()->id;
                    $input['course_id'] = $item;
                    // return $input;
                    $add = Enrollment::Create($input);
                }
                if($add)
                {
                        $message = 'You have enrolled successfully';
                        $success = true;
                        $data = [];
                        // $data['description'] =  auth()->user()->first_name.' '. auth()->user()->last_name. ' has buy a course against '.$checkProgram->major->major_name.' and Program is:- '.$checkProgram->program->name.' at that time '.Carbon::now();
                        $data['user_id'] = auth()->user()->id;
                        $data['type']    = 'enroll';
                        $data['date_time'] = Carbon::now();
                        Notification::Create($data);
                }else{
                        $message = 'Error! Something went wrong please try again';
                        $success = false;
                }
            return response()->json(['message'=>$message,'success' => $success]);
           }else{

               return response()->json(['message'=>"You have already enrolled  major",'success' => false]);
           }
       }

       
      

       
    }

    public function saveCourse(Request $request)
    {
        // return $request->all();
        // return $request->all();
        $validator = Validator::make($request->all(), [
            'course_id' => 'required',
            'program_id' => 'required',
            'major_id' => 'required',
            'term_name' => 'required',
            'year' => 'required',
            // 'term_id' => 'required',
        ]);

    if ($validator->fails()) {
          return response()->json(['message'=>$validator->messages()->first(),'success' => false]);
        }
       $string =  $request->course_id;
       $string = str_replace(array("[", "]", "'"), "", $string); 
       $array = explode(",", $string);

    //   $checkStudent = Enrollment::where(['user_id'=>auth()->user()->id])->Where('major_id',$request->major_id)->where('program_id',$request->program_id)->whereIn('course_id',$array)->get();
      $checkMajor = Enrollment::where(['user_id'=>auth()->user()->id])->Where('major_id',$request->major_id)->first();
       $checkProgram = Enrollment::where(['user_id'=>auth()->user()->id])->Where('program_id',$request->program_id)->first();
       $checkConcentration = Enrollment::where(['user_id'=>auth()->user()->id])->Where('concentration_id',$request->concentration_id)->first();
       $checkYear = Enrollment::where(['user_id'=>auth()->user()->id])->Where('year',$request->year)->first();
       $checkTerm = Enrollment::where(['user_id'=>auth()->user()->id])->Where('term_name',$request->term_name)->first();
       if($checkMajor)
       {
          if($checkConcentration)
          {
               if($checkProgram)
               {
                    if($checkYear)
                    {
                        if($checkTerm)
                        {
                            $check = Enrollment::where(['user_id'=>auth()->user()->id])->WhereIn('course_id',$array)->get();
                            if(count($check)>0)
                                return response()->json(['message'=>'You have already enrolled this course','success' => false]);
                                
                            foreach($array as $item)
                                {
                                    $input = $request->all();
                                    $input['user_id'] =auth()->user()->id;
                                    $input['course_id'] =$item;
                                    $add = Enrollment::Create($input);
                                }
                                if($add)
                                {
                                        $message = 'You have enrolled successfully';
                                        $success = true;
                                        $data = [];
                                        // $data['description'] =  auth()->user()->first_name.' '. auth()->user()->last_name. ' has buy a course against '.$checkProgram->major->major_name.' and Program is:- '.$checkProgram->program->name.' at that time '.Carbon::now();
                                        $data['description'] =  auth()->user()->first_name.' '. auth()->user()->last_name. ' has buy a course';
                                        $data['user_id'] = auth()->user()->id;
                                        $data['type']    = 'enroll';
                                        $data['date_time'] = Carbon::now();
                                        Notification::Create($data);
                                }else{
                                        $message = 'Error! Something went wrong please try again';
                                        $success = false;
                                }
                        }else{
                            return response()->json(['message'=>"You have already enrolled another Term",'success' => false]);
                        }        
                    }else{
                      return response()->json(['message'=>"You have already enrolled another year",'success' => false]);  
                    }
               }else{
                  return response()->json(['message'=>"You have already enrolled another program",'success' => false]); 
               }
               return response()->json(['message'=>$message,'success' => $success]);
          }else{
            return response()->json(['message'=>"You have already enrolled program",'success' => false]);
          }
            
       }else{
            if(!$checkMajor)
            {
               
                foreach($array as $item)
                {
                    $input = $request->except(['course_id'],$request->all());
                    $input['user_id'] =auth()->user()->id;
                    $input['course_id'] = $item;
                    // return $input;
                    $add = Enrollment::Create($input);
                }
                if($add)
                {
                        $message = 'You have enrolled successfully';
                        $success = true;
                        $data = [];
                        // $data['description'] =  auth()->user()->first_name.' '. auth()->user()->last_name. ' has buy a course against '.$checkProgram->major->major_name.' and Program is:- '.$checkProgram->program->name.' at that time '.Carbon::now();
                        $data['description'] =  auth()->user()->first_name.' '. auth()->user()->last_name. ' has buy a course';
                        $data['user_id'] = auth()->user()->id;
                        $data['type']    = 'enroll';
                        $data['date_time'] = Carbon::now();
                        Notification::Create($data);
                }else{
                        $message = 'Error! Something went wrong please try again';
                        $success = false;
                }
            return response()->json(['message'=>$message,'success' => $success]);
           }else{

               return response()->json(['message'=>"You have already enrolled  major",'success' => false]);
           }
       }

       
    }


    public function myTerm()
    {
        
        // $program_id = Enrollment::where(['user_id'=>auth()->user()->id])->pluck('program_id');
        // $data = Term::whereIn('program_id',$program_id)->get();

        $data = Enrollment::select('id','term_name','year')->groupBy('year')->where(['user_id'=>auth()->user()->id])->get();
        // $data = Term::whereIn('id',$term_id)->get();
        $status = false;
        if(count($data)>0)
        {
            $data = $data;
            $status = true;
        }
        return response()->json(['data'=>$data,'success' => $status]);
    }

    public function myCourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'term_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->messages()->first(),'success' => false]);
         }
        $concentration_id = Enrollment::where(['user_id'=>auth()->user()->id])->pluck('concentration_id');
        // $program_id = Enrollment::where(['user_id'=>auth()->user()->id])->pluck('program_id');
        // $data = Course::where('term_id',$request->term_id)->whereIn('concentration_id',$concentration_id)->whereIn('program_id',$program_id)->get();
        $course_id = Enrollment::where(['user_id'=>auth()->user()->id,'term_name'=>$request->term_name,'year'=>$request->year])->pluck('course_id');
        $data = Course::whereIn('id',$course_id)->get();
        $status = false;
        if(count($data)>0)
        {
            $data = $data;
            $status = true;
        }
        return response()->json(['data'=>$data,'success' => $status]);

    }

   


    public function getSubject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->messages()->first(),'success' => false]);
         }

        $data = Course::where('id',$request->course_id)->first();
        $status = false;
        if($data)
        {
            $data = $data;
            $status = true;
            
        }
        
        return response()->json(['data'=>$data,'success' => $status]);
    }

    public function saveCourseCatalog(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required',
            // 'term_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->messages()->first(),'success' => false]);
         }

        // $data = Course::where(['id'=>$request->course_id,'term_id'=>$request->term_id])->first();
        $data = Course::where(['id'=>$request->course_id])->first();
        $path = asset("documents/files/");
        $status = false;
        if($data)
        {
            $data = $data;
            $status = true;
            
        }
        
        return response()->json(['path'=>$path,'data'=>$data,'success' => $status]); 
    }
    public function announcement(Request $request)
    {
        $data = Notification::WhereNotNull('admin_id')->where(['type'=>'announcement','status'=>1])->get();

        $status = false;
        if(count($data)>0)
        {
            $data = $data;
            $status = true;
            
        }
        
        return response()->json(['data'=>$data,'success' => $status]);
    }

    public function getYears(Request $request)
    {
        $data = Semester::where('program_name',$request->name)->groupBy('year')->pluck('year');
       $status = false;
       if(count($data)>0)
       {
           $data = $data;
           $status = true;
           
       }
       return response()->json(['data'=>$data,'success' => $status]);
    }


    public function getSemester(Request $request)
    {
        $data = Semester::where('year',$request->year)->groupBy('season')->pluck('season');
       $status = false;
       if(count($data)>0)
       {
           $data = $data;
           $status = true;
           
       }
       return response()->json(['data'=>$data,'success' => $status]);
    }


    

   


}
