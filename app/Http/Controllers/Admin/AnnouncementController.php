<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\Notification;
use Illuminate\Support\Facades\Validator;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $announcements = Notification::get();
        return view('admin.announcement.index',compact("announcements"));
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
            'date_time' => 'required',
            'description' => 'required',
            
            
        ]);

        if ($validator->fails()) {
              return redirect()->back()->with(array('message'=>$validator->messages()->first(),'type'=>'error'));
         }
         $input = $request->all();
         if($request->type)
         $input['type']  = $request->type;
         $input['admin_id'] = auth()->user()->id;
        $add = Notification::create($input);
        if($add)
        {
            return redirect()->route('announcement.index')->with(['message'=>'Added successfully','type'=>'success']);
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
        $announcement = Notification::find($id);
        return view('admin.announcement.edit',compact('announcement'));
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
            'date_time' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
              return redirect()->back()->with(array('message'=>$validator->messages()->first(),'type'=>'error'));
         }
        $program = Notification::find($id);
        $update =  $program->update($request->all());
        if($update)
        {
            return redirect()->route('announcement.index')->with(['message'=>'Updated successfully','type'=>'success']);
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
        $delete = Notification::find($id)->delete();
        if($delete)
        {
            return redirect()->route('announcement.index')->with(['message'=>'Deleted successfully','type'=>'success']);
        }else{
            return redirect()->back()->with(['message'=>'Something went wrong please try again','type'=>'error']);  
        }
    }

    public function announcement_status(Request $request)
    {
        $statusChange = Notification::where('id',$request->id)->update(['status'=>$request->status]);
        if($statusChange)
        {
            return array('message'=>'Announcement status  has been changed successfully','type'=>'success');
        }else{
            return array('message'=>'Announcement status has not changed please try again','type'=>'error');
        }

    }
}
