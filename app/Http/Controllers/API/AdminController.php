<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\NotificationRead;
use App\Models\User;
use App\Models\Program;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Str;
use App\Models\Semester;


use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function addAnnouncement(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'description' => 'required',
            'date_time'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->messages()->first(),'success' => false]);
         }

        $input = $request->all();
        $input['type'] = 'Announcement';
        $input['admin_id'] = auth()->user()->id;
        $add = Notification::create($input);

        $status = false;
        $message = 'Something went wrong please ty again';
        if($add)
        {
            $message = 'Announcement added successfully';
            $status = true;
            
        }
        return response()->json(['message'=>$message,'success' => $status]); 
        
    }

    public function adminNotificationView()
    {
        $id = Notification::whereHas('notification_read',function ($query) {
            $query->where('user_id', auth()->user()->id);
        })
        // ->where(['status'=>1])
        ->whereNotNull('user_id')
        ->pluck('id');

          
        $data = Notification::whereNotNull('user_id')
        ->whereNotIn('id',$id)
        ->get();
        $status = false;
        if(count($data)>0)
        {
            $data = $data;
            $status = true;
        }
        return response()->json(['data'=>$data,'success' => $status]);
    }


    public function adminNotificationRead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'notification_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->messages()->first(),'success' => false]);
         }

        $add = NotificationRead::Create(['notification_id'=>$request->notification_id,'user_id'=>auth()->user()->id,'status'=>1]); 
        if($add)
        {
            $message = 'Read';
            $status = true;
        }else{
            $message = '';
            $status = false;
        }
        return response()->json(['message'=>$message,'success' => $status]);
    }


    public function studentList()
    {
        $data = User::where('role_id','student')->where('status',0)->get();
        
        $status = false;
        if(count($data)>0)
        {
            $data = $data;
            $status = true;
        }
        return response()->json(['data'=>$data,'success' => $status]);
    }

    public function accountVerification(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->messages()->first(),'success' => false]);
         }

        $statusChange = User::where('id',$request->user_id)->update(['status'=>$request->status]);

        if($statusChange)
        {
            $message = 'User Account verified';
            $status = true;
        }else{
            $message = 'Something went wrong please try agaom';
            $status = false;
        }
        return response()->json(['message'=>$message,'success' => $status]);

    }

    public function accountDelete(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->messages()->first(),'success' => false]);
         }
        $delete =  User::where('id',$request->user_id)->delete();
        // $delete = $delete->delete();
        if($delete)
        {
            $message = 'User delete successfully';
            $status = true;
        }else{
            $message = 'Something went wrong please try agaom';
            $status = false;
        }
        return response()->json(['message'=>$message,'success' => $status]);
    }

    public function adminSearchStudent(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'search' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->messages()->first(),'success' => false]);
         }

        $searchTerm = $request->search;
        $data = User::where('first_name', 'LIKE', '%'.$searchTerm.'%')->get();
        $profile_path = asset('documents/profile/');
        $status = false;
        if(count($data)>0)
        {
            $data = $data;
            $status = true;
        }
        return response()->json(['profile_path'=>$profile_path,'data'=>$data,'success' => $status]);
    }


    public function adminUpdate(Request $request)
    {
        
         $validator = Validator::make($request->all(), [
            
            'major_id'    => 'required',
            // 'concentration_id' => 'required',
            'program_id' => 'required',
            'plan_type'  =>'required',
            'type'       =>'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->messages()->first(),'success' => false]);
         }
           
           if($request->plan_type == 'road_map')
           {
               if(!$request->concentration_id){
                    return response()->json(['message'=>'concentration is required','success' => false]);
                }
               
                if($request->type == 'view')
                {
                    $data =  Program::where('major_id',$request->major_id)->where('id',$request->program_id)->first();
                   
                    $profile_path = asset('documents/file/');
                    $status = false;
                    if($data)
                    {
                        $data = $data;
                        $status = true;
                        $profile_path = asset('documents/file/'.$data->road_map_file);
                    }
                    return response()->json(['path'=>$profile_path,'data'=>$data,'success' => $status]);
                }else{
                    
                    // $data = $request->all();
                    $data = $request->except(['major_id','concentration_id', 'program_id','road_map_file','type','plan_type'],$request->all());
                    if($request->hasFile('road_map_file'))
                    {
                        $img = Str::random(20).$request->file('road_map_file')->getClientOriginalName();
                        $data['road_map_file'] = $img;
                        $request->road_map_file->move(public_path("documents/file"), $img);
                    }
                
                    $check =  Program::where('id',$request->program_id)->update($data);
                    if($check)
                    {
                        $message = 'Updated successfully';
                        $status = true;
                    }else{
                        $message = 'Something went wrong please try agaom';
                        $status = false;
                    }
                   return response()->json(['message'=>$message,'success' => $status]);
                }
           }else if($request->plan_type == 'degree_plan'){

                if(!$request->concentration_id){
                    return response()->json(['message'=>'concentration is required','success' => false]);
                }

              if($request->type == 'view')
                {
                    $data =  Program::where('major_id',$request->major_id)->where('id',$request->program_id)->first();
                    $profile_path = asset('documents/file/');
                    $status = false;
                    if($data)
                    {
                        $data = $data;
                        $status = true;
                        $profile_path = asset('documents/file/'.$data->file);
                    }
                    return response()->json(['path'=>$profile_path,'data'=>$data,'success' => $status]);
                }else{
                    
                    // $data = $request->all();
                    $data = $request->except(['major_id','concentration_id', 'program_id','file','type','plan_type'],$request->all());
                    if($request->hasFile('road_map_file'))
                    {
                        $img = Str::random(20).$request->file('road_map_file')->getClientOriginalName();
                        $data['file'] = $img;
                        $request->road_map_file->move(public_path("documents/file"), $img);
                    }
                
                    $check =  Program::where('id',$request->program_id)->update($data);
                    if($check)
                    {
                        $message = 'Updated successfully';
                        $status = true;
                    }else{
                        $message = 'Something went wrong please try agaom';
                        $status = false;
                    }
                   return response()->json(['message'=>$message,'success' => $status]);
                } 
           }else if($request->plan_type == 'catalogue')
           {
            
               if(!$request->term_id){
                 return response()->json(['message'=>'Term is required','success' => false]);
               }

               if(!$request->course_id){
                 return response()->json(['message'=>'Course is required','success' => false]);
               }

               if($request->type == 'view')
                {
                    $data =  Course::where(['major_id'=>$request->major_id,'program_id'=>$request->program_id,'term_id'=>$request->term_id])->where('id',$request->course_id)->first();
                   
                    $profile_path = asset('documents/file/');
                    $status = false;
                    if($data)
                    {
                        $data = $data;
                        $status = true;
                        $profile_path = asset('documents/file/'.$data->file);
                    }
                    return response()->json(['path'=>$profile_path,'data'=>$data,'success' => $status]);
                }else{
                    
                    // $data = $request->all();
                    $data = $request->except(['concentration_id','road_map_file','type','plan_type','course_id'],$request->all());
                    if($request->hasFile('road_map_file'))
                    {
                        $img = Str::random(20).$request->file('road_map_file')->getClientOriginalName();
                        $data['file'] = $img;
                        $request->road_map_file->move(public_path("documents/file"), $img);
                    }
                
                    $check =  Course::where('id',$request->course_id)->update($data);
                    if($check)
                    {
                        $message = 'Updated successfully';
                        $status = true;
                    }else{
                        $message = 'Something went wrong please try agaom';
                        $status = false;
                    }
                   return response()->json(['message'=>$message,'success' => $status]);
                }
           }
    }

    public function myProgramData()
    {
        $program_id = Enrollment::where('user_id',auth()->user()->id)->pluck('program_id');
       $year_of_program =  Program::whereIn('id',$program_id)->pluck('name');
        $program_id =  Program::whereIn('id',$program_id)->pluck('id');
    //   return Semester::whereIn('program_name',$year_of_program)->pluck('year')->unique();;
        $uniqueYears = Semester::whereIn('program_name', $year_of_program)
        ->select('year', 'season')
        ->distinct()
        ->get()
        ->groupBy('year')
        ->map(function ($items) {
            return $items->pluck('season')->toArray();
        })
        ->toArray();
        // return Course::whereIn('program_id',$program_id)->get();


    $years = Course::whereIn('program_id', $program_id)
        ->distinct('year')
        ->get('year');
    
    $result = [];
    
    foreach ($years as $year) {
        $seasons = Course::whereIn('program_id', $program_id)
            ->where('year', $year->year)
            ->distinct('term_name')
            ->get('term_name');
    
        $result[$year->year] = [];
    
        foreach ($seasons as $season) {
            $courses = Course::whereIn('program_id', $program_id)
                ->where('year', $year->year)
                ->where('term_name', $season->term_name)
                ->get(['id','course_name', 'course_number']);
    
            $result[$year->year][$season->term_name]['courses'] = $courses->toArray();
        }
    }
    // return json_encode($result);
    // $program_id = Enrollment::where('user_id',auth()->user()->id)->whereIn('program_id',$program_id)->get();


    $program_id = Enrollment::where('user_id', auth()->user()->id)
    ->whereIn('program_id', $program_id)
    ->pluck('course_id');

$enrolledCourses = Course::whereIn('id', $program_id)
    ->join('semesters', 'courses.semester_id', '=', 'semesters.id')
    ->select('semesters.id', 'courses.course_name', 'courses.course_number', 'semesters.year', 'semesters.season')
    ->get();

$remainingCourses = Course::whereNotIn('id', $program_id)
    ->get(['id', 'course_name', 'course_number']);

$result = [
    'enrolled_courses' => $enrolledCourses,
    'remaining_courses' => $remainingCourses,
];

return json_encode($result);
    }
}
