<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
Use App\Models\Term;
Use App\Models\Course;
Use App\Models\Program;
Use App\Models\Major;
Use App\Models\Concentration;
Use App\Models\Semester;

class CourseController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $terms = Term::get();
          $data['terms'] = Term::with('program','major')->get();
         $data['myterms'] = Term::get();
        $data['programs'] = Program::where('status',1)->get();
        $data['majors'] = Major::where('status',1)->get();
        // $data['courses'] = Course::with('major','concentration','program')->get();
        // $courses = Course::with('major','concentration','program')->get();
        // term_name,year come from course table be aware make a array


        $program_id = Course::pluck('program_id');
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





//     $years = Course::with('major', 'concentration', 'program')->whereIn('program_id', $program_id)
//     ->distinct('year')
//     ->get();

// $result = [];

// foreach ($years as $year) {
//     $majorName = $year->major->major_name;
//     $concentrationName = $year->concentration->name;
//     $programName = $year->program->name;

//     $result[$majorName]['concentration'][$concentrationName]['program'][$programName][$year->year] = [];

//     $seasons = Course::whereIn('program_id', $program_id)
//         ->where('year', $year->year)
//         ->distinct('term_name')
//         ->get('term_name');

//     foreach ($seasons as $season) {
//         $courses = Course::whereIn('program_id', $program_id)
//             ->where('year', $year->year)
//             ->where('term_name', $season->term_name)
//             ->get(['id', 'course_name', 'course_number']);

//         $result[$majorName]['concentration'][$concentrationName]['program'][$programName][$year->year][$season->term_name]['courses'] = $courses->toArray();
//     }
// }

// return $result;


$years = Course::with('major', 'concentration', 'program')->whereIn('program_id', $program_id)
    ->distinct('year')
    ->get();

$result = [];

foreach ($years as $year) {
    $majorName = $year->major->major_name;
    $concentrationName = $year->concentration->name;
    $programName = $year->program->name;

    if (!isset($result[$majorName])) {
        $result[$majorName] = [
            'concentration' => [],
        ];
    }

    if (!isset($result[$majorName]['concentration'][$concentrationName])) {
        $result[$majorName]['concentration'][$concentrationName] = [
            'program' => [],
        ];
    }

    if (!isset($result[$majorName]['concentration'][$concentrationName]['program'][$programName])) {
        $result[$majorName]['concentration'][$concentrationName]['program'][$programName] = [
            'year' => [],
        ];
    }

    $result[$majorName]['concentration'][$concentrationName]['program'][$programName]['year'][$year->year] = [
        'term' => [],
    ];
}

foreach ($result as $majorName => &$majorData) {
    foreach ($majorData['concentration'] as $concentrationName => &$concentrationData) {
        foreach ($concentrationData['program'] as $programName => &$programData) {
            $years = array_keys($programData['year']);

            foreach ($years as $year) {
                $terms = Course::whereIn('program_id', $program_id)
                    ->where('year', $year)
                    ->distinct('term_name')
                    ->pluck('term_name');

                foreach ($terms as $term) {
                    $courses = Course::whereIn('program_id', $program_id)
                        ->where('year', $year)
                        ->where('term_name', $term)
                        ->get(['id', 'course_name', 'course_number','program_id','major_id','concentration_id'])
                        ->toArray();

                    $programData['year'][$year]['term'][$term] = [
                        'courses' => $courses,
                    ];
                }
            }
        }
    }
}

return $result;

$data['courses'] = $result;



        return view('admin.course.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();


        $validator = Validator::make($request->all(), [
            'course_name' => 'required',
        ]);

        if ($validator->fails()) {
              return redirect()->back()->with(array('message'=>$validator->messages()->first(),'type'=>'error'));
        }

        // return $program_name =  Program::select('name')->where('id',$request->program_id)->where('status',1)->first();
        $check = Course::where(['course_name'=>$request->course_name,'major_id'=>$request->major_id,'concentration_id'=>$request->concentration_id,'term_name'=>$request->term_id])->first();
        // if($check)
        // return redirect()->back()->with(['message'=>'This Course has already been added','type'=>'error']);



        $input = $request->except(['_token','file'],$request->all());
        if($request->hasFile('file'))
        {
            $img = Str::random(20).$request->file('file')->getClientOriginalName();
            $input['file'] = $img;
            $request->file->move(public_path("documents/files"), $img);
        }
        $input['term_name'] = $request->term_id;
        $add = Course::create($input);

        if($add)
        {
            return redirect()->back()->with(['message'=>'Added successfully','type'=>'success']);
        }else{
            return redirect()->back()->with(['message'=>'Something went wrong please try again','type'=>'error']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $data['course'] = Course::find($id);
        // $terms = Term::get();
        // $data['terms'] = Term::where('status',1)->get();

        $data['majors'] = Major::where('status',1)->get();

        return view('admin.course.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'course_name' => 'required',
        ]);

        if ($validator->fails()) {
              return redirect()->back()->with(array('message'=>$validator->messages()->first(),'type'=>'error'));
         }
        $course = Course::find($id);
        $input = $request->except(['_token','file'],$request->all());
        if($request->hasFile('file'))
        {
            $img = Str::random(20).$request->file('file')->getClientOriginalName();
            $input['file'] = $img;
            $request->file->move(public_path("documents/files"), $img);
        }

        $update =  $course->update($input);
        if($update)
        {
            return redirect()->route('course.index')->with(['message'=>'Updated successfully','type'=>'success']);
        }else{
            return redirect()->back()->with(['message'=>'Something went wrong please try again','type'=>'error']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = Course::find($id)->delete();
        if($delete)
        {
            return redirect()->back()->with(['message'=>'Deleted successfully','type'=>'success']);
        }else{
            return redirect()->back()->with(['message'=>'Something went wrong please try again','type'=>'error']);
        }
    }

    public function course_status(Request $request)
    {
        $statusChange = Course::where('id',$request->id)->update(['status'=>$request->status]);
        if($statusChange)
        {
            return array('message'=>'Course status  has been changed successfully','type'=>'success');
        }else{
            return array('message'=>'Course status has not changed please try again','type'=>'error');
        }

    }

    public function getTerm(Request $request)
    {
        // return Term::where('program_id',$request->program_id)->where('status',1)->get();
        // return Term::where('status',1)->get();
       $program_name =  Program::where('id',$request->program_id)->where('status',1)->first();
        return $data = Semester::where('program_name',$program_name->name)->groupBy('year')->get();
    }

    public function getProgram(Request $request)
    {
        // return Program::where('major_id',$request->major_id)->get();
        return Program::where('concentration_id',$request->concentration_id)->get();
    }

    public function getConcentration(Request $request)
    {
        return Concentration::where('major_id',$request->major_id)->get();
    }


    public function getSemesters(Request $request)
    {
       return  $data = Semester::where('year',$request->year)->groupBy('season')->pluck('season');
    }

    public function getSemster(Request $request)
    {
        $name = $request->program_id;
        return Semester::where('program_name',$name)->get();
    }


}
