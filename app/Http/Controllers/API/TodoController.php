<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Todo;
use App\Models\User;
use App\Models\Notification;

class TodoController extends Controller
{
    public function addTodo(Request $request)
    {
        //  $what_to_do =  json_decode($request->what_to_do);
        //  return implode(', ', $request->what_to_do);
        $validatedData = $request->validate([
            'what_to_do.*' => 'required',
        ]);

        
       $string =  $request->what_to_do;
       $string = str_replace(array("[", "]", "'"), "", $string); 
       $array = explode(",", $string); 
       
        foreach ($array as $todo) {
           Todo::create([
            'what_to_do' =>$todo,
            'user_id' =>$request->user_id,
           ]);

        }
        return response()->json([
            'success' => true,
            'message' => 'Added successfully'
        ]);
    }

    public function getTodoList(Request $request)
    {
        // return auth()->user()->id;
        $data = Todo::select('id','what_to_do','date')->where('user_id',auth()->user()->id)->get();
        $notification = Notification::select('id','type','description','date_time')->WhereNotNull('admin_id')->where(['type'=>'announcement','status'=>1])->get();
        // $status = false;
        // if(count($data)>0)
        // {
        //     $data = $data;
        //     $status = true;
        // }
        // return response()->json(['data'=>$data,'success' => $status]);
        $formattedData = [];
        $formattedNotificationData = [];
        
        foreach ($data as $item) {
            $date = $item->date;
            $formattedData[$date][] = $item->what_to_do;
        }

        // $response = [
        //     'todoList' => $formattedData,
        //     'success' => true
        // ];

        if(count($formattedData)>0){
            $response = [
                'todoList' => $formattedData,
                'success' => true
            ]; 
        }else{
            $response = [
                'todoList' => null,
                'success' => false
            ]; 
        }

        foreach ($notification as $item) {
            $date = $item->date_time;
            $formattedNotificationData[$date][] = $item->type;
            $formattedNotificationData[$date][] = $item->description;
        }
      
        if(count($formattedNotificationData)>0){
            $notificationresponse = [
                'notificationList' => $formattedNotificationData,
                'success' => true
            ];
        }else{
            $notificationresponse = [
                'notificationList' => null,
                'success' => false
            ];
        }
        

        
        // return response()->json([$response,$notificationresponse]);


    $data = Todo::select('id', 'what_to_do', 'date')
        ->where('user_id', auth()->user()->id)->whereNotNull('date')
        ->get();
    
    $notification = Notification::select('id', 'description', 'date_time')
        ->whereNotNull('admin_id')
        ->where(['type' => 'announcement', 'status' => 1])->whereNotNull('date_time')
        ->get();
    
    $formattedData = [];
    
    // Process $data
    foreach ($data as $item) {
        $date = $item->date;
        $formattedData[$date][] = $item->what_to_do;
    }
    
    // Process $notification
    foreach ($notification as $item) {
        $date = $item->date_time;
        $formattedData[$date]['admin'][] = $item->description;
    }
    
    $response = [
        'data' => $formattedData,
        'success' => true
    ];
    
    return response()->json($response);
    }
}
