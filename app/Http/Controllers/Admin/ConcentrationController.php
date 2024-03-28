<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use App\Models\Major;
Use App\Models\Concentration;
use Illuminate\Support\Str;

class ConcentrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['majors'] = Major::where('status',1)->get();
       $data['concentrations'] = Concentration::with('major')->get();
       
        return view('admin.concentration.index',$data);
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
            'major_id' => 'required',
         ]);

        if ($validator->fails()) {
              return redirect()->back()->with(array('message'=>$validator->messages()->first(),'type'=>'error'));
         }

         $input = $request->except(['_token','file'],$request->all());
         if($request->hasFile('file'))
         {
             $img = Str::random(20).$request->file('file')->getClientOriginalName();
             $input['file'] = $img;
             $request->file->move(public_path("documents/files"), $img);
         }
 
         $add = Concentration::create($input);

        if($add)
        {
            return redirect()->route('concentration.index')->with(['message'=>'Added successfully','type'=>'success']);
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
        
        $data['majors'] = Major::where('status',1)->get();
        $data['concentration'] = Concentration::find($id);
        return view('admin.concentration.edit',$data);
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
            'name' => 'required',
            'major_id' => 'required',
        ]);

        if ($validator->fails()) {
              return redirect()->back()->with(array('message'=>$validator->messages()->first(),'type'=>'error'));
         }
        $program = Concentration::find($id);
       
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
            return redirect()->route('concentration.index')->with(['message'=>'Updated successfully','type'=>'success']);
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
        $delete = Concentration::find($id)->delete();
        if($delete)
        {
            return redirect()->route('concentration.index')->with(['message'=>'Deleted successfully','type'=>'success']);
        }else{
            return redirect()->back()->with(['message'=>'Something went wrong please try again','type'=>'error']);  
        }
    }

    public function concentration_status(Request $request)
    {
        $statusChange = Concentration::where('id',$request->id)->update(['status'=>$request->status]);
        if($statusChange)
        {
            return array('message'=>'Concentration status  has been changed successfully','type'=>'success');
        }else{
            return array('message'=>'Concentration status has not changed please try again','type'=>'error');
        }

    }
}
