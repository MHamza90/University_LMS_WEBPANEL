<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DegreeProgram;
use Illuminate\Support\Facades\Validator;

class DegreeProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $programs = DegreeProgram::get();
        return view('admin.degree-program.index',compact('programs'));
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
            'total_years' => 'required',
            'name' => 'required|unique:degree_programs,name',
            
        ]);

        if ($validator->fails()) {
              return redirect()->back()->with(array('message'=>$validator->messages()->first(),'type'=>'error'));
         }
        $add = DegreeProgram::create($request->all());
        if($add)
        {
            return redirect()->route('degree-program.index')->with(['message'=>'Added successfully','type'=>'success']);
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
        $program = DegreeProgram::find($id);
        return view("admin.degree-program.edit",compact('program'));
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
            'total_years' => 'required',
            // 'name' => 'required|unique:degree_programs,name',$id,
            
        ]);

        if ($validator->fails()) {
              return redirect()->back()->with(array('message'=>$validator->messages()->first(),'type'=>'error'));
         }
        $program = DegreeProgram::find($id);
        $update =  $program->update($request->all());
        if($update)
        {
            return redirect()->route('degree-program.index')->with(['message'=>'Updated successfully','type'=>'success']);
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
        $delete = DegreeProgram::find($id)->delete();
        if($delete)
        {
            return redirect()->route('degree-program.index')->with(['message'=>'Deleted successfully','type'=>'success']);
        }else{
            return redirect()->back()->with(['message'=>'Something went wrong please try again','type'=>'error']);  
        }
    }
}
