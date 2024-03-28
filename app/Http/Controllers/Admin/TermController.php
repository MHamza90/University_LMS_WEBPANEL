<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Major;
use App\Models\Term;
Use App\Models\Semester;

class TermController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $terms = Term::get();
        // $data['terms'] = Term::with('program','major','concentration')->get();
        $data['terms'] = Term::get();
        $data['programs'] = Program::where('status',1)->get();
        $data['majors'] = Major::where('status',1)->get();

        
        return view('admin.term.index',$data);
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
      
       $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
       
      $semster =  Semester::find($request->name);
      
         if($validator->fails()) {
              return redirect()->back()->with(array('message'=>$validator->messages()->first(),'type'=>'error'));
         }

         $check = Term::where(['name'=>$request->name,'major_id'=>$request->major_id,'program_id'=>$request->program_id])->first();
         if($check)
         return redirect()->back()->with(['message'=>'This Term has already been added','type'=>'error']);

        $input = $request->except(['name'],$request->all()); 
        $input['name']  = $semster->name;
        $input['semster_id']  = $semster->id;
        // return $input;
        $add = Term::create($input);
        if($add)
        {
            return redirect()->route('term.index')->with(['message'=>'Added successfully','type'=>'success']);
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
        
        $data['term'] = Term::find($id);
        // $data['programs'] = Program::where('status',1)->get();
        $data['majors'] = Major::where('status',1)->get();
        return view('admin.term.edit',$data);
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
        // return $request->all();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
              return redirect()->back()->with(array('message'=>$validator->messages()->first(),'type'=>'error'));
         }
        $term = Term::find($id);
        $update =  $term->update($request->all());
        if($update)
        {
            return redirect()->route('term.index')->with(['message'=>'Updated successfully','type'=>'success']);
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
        $delete = Term::find($id)->delete();
        if($delete)
        {
            return redirect()->route('term.index')->with(['message'=>'Deleted successfully','type'=>'success']);
        }else{
            return redirect()->back()->with(['message'=>'Something went wrong please try again','type'=>'error']);  
        }
    }

    public function term_status(Request $request)
    {
        $statusChange = Term::where('id',$request->id)->update(['status'=>$request->status]);
        if($statusChange)
        {
            return array('message'=>'Term status  has been changed successfully','type'=>'success');
        }else{
            return array('message'=>'Term status has not changed please try again','type'=>'error');
        }

    }

    public function getProgram(Request $request)
    {
        // return Program::where('major_id',$request->major_id)->get();
    }
}
