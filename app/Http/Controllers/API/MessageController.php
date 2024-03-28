<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
class MessageController extends Controller
{
    public function sendNotificationToSupport(Request $request)
    {
        $input = $request->except(['receiver_id','sender_id'],$request->all());
        // $input['receiver_id'] = $request->receiver_id;
        $input['sender_id'] = auth()->user()->id;
        Message::Create($input);
        $users = User::where('role_id','support')->get();
        foreach($users as $item){
            $this->notification_for_user("Testing",$request->messages,$item->device_id);
        }
    }
    public function notification_for_user($title,$message,$device_token)
       {
          
          
        //    $user = User::where('id',auth()->user()->id)->first();
        
        
            $serverKey = "AAAAYXLREc0:APA91bHXM6q-UnufzRMDRpcsknh7ogelOdZHjF4Cqed00ftqG4iY5p5CAXKrMjANDwjw16_XUBjojyETDq_yqjmhbQiUfB_bvNESXGxshocATVuubzXJ3U1CCDCWoksiQ8g1vwaeva7b";
    	    
        	$url = "https://fcm.googleapis.com/fcm/send";
        	
        	$recipient = $device_token;
        
           $notification =
        	[
        		'title'     => $title,
        		'message'   => $message,
        		
        	];
        
        
      
        	
        
        	$fields = 
        	[
        		'to'  => $recipient,
        		'notification' => $notification,
        // 		'data' => $dataPayload
        	];
        
        	//Set the appropriate headers
        	$headers = 
        	[
        	'Authorization: key=' . $serverKey,
        	'Content-Type: application/json'
        	];
        
        	//Send the message using cURL.
        	$ch = curl_init();
        	curl_setopt( $ch,CURLOPT_URL, $url);
        	curl_setopt( $ch,CURLOPT_POST, true );
        	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        	$result = curl_exec($ch );
        	curl_close( $ch );
        // 	return $result;
       }

}
