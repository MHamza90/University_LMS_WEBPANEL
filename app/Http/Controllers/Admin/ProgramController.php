<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Major;
use Illuminate\Support\Str;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        $data['majors'] = Major::where('status',1)->get();
        $data['programs'] = Program::with('major','concentration')->orderBy('major_id','ASC')->get()->groupBy('major.major_name');
        return view('admin.program.index',$data);
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
       
        if ($validator->fails()) {
              return redirect()->back()->with(array('message'=>$validator->messages()->first(),'type'=>'error'));
         }

         $check = Program::where(['name'=>$request->name,'major_id'=>$request->major_id,'concentration_id'=>$request->concentration_id])->first();
        if($check)
        return redirect()->back()->with(['message'=>'This Program has already been added','type'=>'error']);
        
        $input = $request->except(['_token','file'],$request->all());
        if($request->hasFile('file'))
        {
            $img = Str::random(20).$request->file('file')->getClientOriginalName();
            $input['file'] = $img;
            $request->file->move(public_path("documents/files"), $img);
        }

        $add = Program::create($input);
        if($add)
        {
            return redirect()->route('program.index')->with(['message'=>'Added successfully','type'=>'success']);
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
      

        $data['program'] = Program::find($id);
        $data['majors'] = Major::where('status',1)->get();

        return view('admin.program.edit',$data);
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
        $program = Program::find($id);
        $input = $request->except(['_token','file'],$request->all());
        if($request->hasFile('file'))
        {
            $img = Str::random(20).$request->file('file')->getClientOriginalName();
            $input['file'] = $img;
            $request->file->move(public_path("documents/files"), $img);
        }

        $update =  $program->update($input);
        if($update)
        {
            return redirect()->route('program.index')->with(['message'=>'Updated successfully','type'=>'success']);
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
        $delete = Program::find($id)->delete();
        if($delete)
        {
            return redirect()->route('program.index')->with(['message'=>'Deleted successfully','type'=>'success']);
        }else{
            return redirect()->back()->with(['message'=>'Something went wrong please try again','type'=>'error']);  
        }
    }

    public function program_status(Request $request)
    {
        $statusChange = Program::where('id',$request->id)->update(['status'=>$request->status]);
        if($statusChange)
        {
            return array('message'=>'Program status  has been changed successfully','type'=>'success');
        }else{
            return array('message'=>'Program status has not changed please try again','type'=>'error');
        }

    }
}
