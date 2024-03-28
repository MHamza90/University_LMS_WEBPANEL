<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Country;
use App\Models\Todo;
use App\Models\ContactUsPage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use DB;
use Hash;
use DataTables;
use Mail;
use Carbon\Carbon;
use Session;
use PHPExcel_IOFactory;
use PHPExcel_Settings;
use PHPExcel_Worksheet;
use Spatie\PdfToText\Pdf;
use Spatie\PdfToText\Pdf as PdfToText;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Exports\PdfToExcelExport;
use Spatie\PdfToImage\Pdf as PdfToImage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Spatie\PdfParser\PdfParser;
use Smalot\PdfParser\Parser;
use Intervention\Image\ImageManager;




class AdminController extends Controller
{

    function __construct()
    {
        // $this->middleware(['auth','verified']);
        $this->middleware('permission:user-status', ['only' => ['change_status']]);
    }

 

   
    public function user_login(Request $request)
    {
        // return view('admin/login');
        if(Auth::check()){
            return redirect('dashboard');
        }else{
           return view('admin/login');
        }
    }


    public function register(Request $request)
    {
        return view('admin/register');
    }

    public function loginAdminProcess(Request $request)
    {

        
        if (Auth::attempt(array('email' => $request->email, 'password' => $request->password)))
        {
           if(Auth::check()){
            
              if(auth()->user()->email_verified_at){
                  if(auth()->user()->status == 1)
                  {
                      return redirect('dashboard')->with(array('message'=>'Login success','type'=>'success'));
                  }else{
                        Auth::logout(); 
                        return redirect()->back()->with(array('message'=>'Please wait for admin approval','type'=>'error'));;
                  }
                return redirect('dashboard')->with(array('message'=>'Login success','type'=>'success'));
                }else{
                    Session::flush();
                    Auth::logout();
                    // return auth()->user()->email_verified_at;
                    return redirect('/')->with(array('message'=>'Your email is not verified','type'=>'error'));;
                   
                   
                }   
            }
        }else{
            
            return redirect()->back()->with(array('message'=>'Invalid email or Password','type'=>'error'));
        }
    }
    
    
    public function AdminRegisterPrcess(Request $request)
    {
       
        $token = Str::random(40); 
        $validator = Validator::make($request->all(), [
           'email'         => 'required|email|unique:users',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with(array('message'=>'This email is already exists','type'=>'error'));
        }
        
        $users = $request->except(['password','password_confirmation'],$request->all());
        if($request->hasFile('profile'))
        {
            $img = Str::random(20).$request->file('profile')->getClientOriginalName();
            $users['profile'] = $img;
            $request->profile->move(public_path("documents/profile"), $img);
        }
        $users['role_id'] = 'Manager';
        $users['password'] = hash::make($request->password);
        $users['remember_token'] = $token;
        $user = User::create($users);
        $user->assignRole('Manager');
        $myUser = User::where('id',$user->id)->first();
        if($myUser)
        {
            if($myUser->email_verified_at == null)
            {
                $data = ['token'=>$token];
                $this->CheckEmailVerify($token,$request->email);
            
            }
        }
        if($user)
        {
            return redirect()->back()->with(array('message'=>'account created succssfully Please check your email','type'=>'success'));
            
        }else{
            return redirect()->with(array('message'=>'Somethig wrong please try again','type'=>'error'));
        }


    }

    public function dashboard(Request $request)
    {
        // return \Request::getClientIp(true);
        
        return view('admin/dashboard');
    }

    public function view_user()
    {
      return view('admin/view-user');
    }

    public function get_users(Request $request)
    {
        if ($request->ajax()) {
            $data = User::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    if($row->status == '0'){
                        $status = '<a href="javascript:void(0)" data-id='.$row->id.' data-status="1" class="active-record btn btn-danger btn-sm">Inactive</a>';
                    }else{
                        $status = '<a href="javascript:void(0)" data-id='.$row->id.' data-status="0" class="active-record btn btn-success btn-sm">Active</a>'; 
                    }
                    $actionBtn = ''.$status.' <a href="javascript:void(0)" data-toggle="modal"  data-target="#modal-default" class="update_user btn btn-success btn-sm"  data-id='.$row->id.'>Update</a>  <a href="javascript:void(0)" data-id='.$row->id.' class="delete-record btn btn-danger btn-sm">Delete</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function change_status(Request $request)
    {
        $statusChange = User::where('id',$request->id)->update(['status'=>$request->status]);
        if($statusChange)
        {
            return array('message'=>'User status  has been changed successfully','type'=>'success');
        }else{
            return array('message'=>'User status has not changed please try again','type'=>'error');
        }

    }

    public function delete_user(Request $request)
    {
       
       $deleteUser = User::where('id',$request->id)->delete();
        if($deleteUser)
        {
            return array('message'=>'User has been deleted successfully','type'=>'success');
        }else{
            return array('message'=>'User has not deleted please try again','type'=>'error');
        }
    }

   
   public function logouts()
    {
        Auth::logout();
        return redirect('/');
    }

    public function profile()
    {
        return view('admin/profile'); 
    }

    public function updateProfileProcess(Request $request)
    {
       $data = $request->except(['_token','id'],$request->all());
        if($request->hasFile('profile'))
        {
            $img = Str::random(20).$request->file('profile')->getClientOriginalName();
            $data['profile'] = $img;
            $request->profile->move(public_path("documents/profile"), $img);
        }
        $profileUp = User::find(auth()->user()->id);
        $up =  $profileUp->update($data);
        if($up)
        {
            return redirect()->back()->with(array('message'=>'Profile has been updated successfully','type'=>'success'));
        }else{
            return redirect()->back()->with( array('message'=>'Profile has not update please try again','type'=>'error'));
        }
    }


    public function changePassword(Request $request)
    {
       
        if(!Hash::check($request->oldPassword, auth()->user()->password))
         return redirect()->back()->with(array('message'=>'Old Password Doesnt match!','type'=>'error'));
    
        User::find(auth()->user()->id)->update([
            'password' => Hash::make($request->newPassword)
        ]);
       return redirect()->back()->with(array('message'=>'Password updated successfully','type'=>'success'));
	}

    

    public function addUpdateUser(Request $request)
    {
        if(!$request->id)
        {
            $validator = Validator::make($request->all(), [
                'email'         => 'required|email|unique:users',
            ]);
            if ($validator->fails()) {
                 return array('message'=>'This email is already exists','type'=>'error');
            }
        }
        
        $users = [
            'first_name'    =>$request->first_name,
            'last_name'     =>$request->last_name,
            'email'         =>$request->email,
            'phone_number'  =>$request->phone_number,
            'address'       =>$request->address,
            // 'country_id'   =>$request->country_id,
        ];
        if($request->hasFile('profile'))
        {
            $img = Str::random(20).$request->file('profile')->getClientOriginalName();
            $users['profile'] = $img;
            $request->profile->move(public_path("/documents/profile"), $img);
        }
        if($request->id)
        {
            $user = User::where('id',$request->id)->update($users);
            $message = 'User update successfully';
        }else{
            $user = User::create($users);
            $message = 'account created succssfully';
        }
        
        if($user)
        {
            return array('message'=>$message,'type'=>'success');
        }else{
            return array('message'=>'Somethig wrong please try again','type'=>'error');
        }
    }

    public function getUsers(Request $request)
    {
        return User::where('id',$request->id)->first();
    }


    public function CheckEmailVerify($token,$email)
    {
           $data = ['token'=> $token];

           $mail = Mail::send('admin.check-email', array(
                'token' => $data,
               
            ), function($message) use ($email){
                $message->to($email)->subject('Verify  Mail');
            });
    }
    
    public function verrifyEmail($token)
    {
        $user = User::where('remember_token',$token)->first();
        $myToken = Str::random(40);
        if($user)
        {
            User::where('remember_token',$token)->update(['email_verified_at' => Carbon::now(),'remember_token'=>$myToken]);
            return redirect('/')->with(['message'=>'Email verified please login your account','type'=>'success']);
        }else{
           return redirect()->back()->with(array('message'=>'Something went wrong please try again','type'=>'error')); 
        }
    }

    public function todoList()
    {
        $todos =  User::with('todo')->whereHas("todo")->get();
        return view("admin.todo.index",compact('todos'));
    }

    public function deleteTodo($id)
    {
        $delete = Todo::where('id',$id)->delete();
        if($delete)
        {
            return redirect()->back()->with(array('message'=>'Deleted successfully','type'=>'success'));
        }else{
            return redirect()->back()->with(array('message'=>'Something went wrong please try again','type'=>'error'));
        }
    }


    public function sendEmail()
    {
        $mail = new PHPMailer(true);

        // Configure PHPMailer settings
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = 'e22002962ec14b';
        $mail->Password = '553d8953a1d982';
        $mail->Port = 587;

        // Set email content
        $mail->setFrom('sender@example.com', 'Sender Name');
        $mail->addAddress('recipient@example.com', 'Recipient Name');
        $mail->Subject = 'Your Subject';
        $mail->Body = 'Your message body';

        // Send the email
        try {
            $mail->send();
            echo 'Email sent successfully';
        } catch (Exception $e) {
            echo 'Email could not be sent. Error: ' . $mail->ErrorInfo;
        }
    }

    public function convertPDFtoExcel()
{
    // Path to the PDF file
    $pdfPath = public_path('pdf/Real-Hub-Organization-Admin-(1).pdf');

    // Extract text from the PDF
    $pdfText = Pdf::getText($pdfPath);

    // Transform the text into an array for Excel
    $data = [];
    $lines = preg_split("/\r\n|\n|\r/", $pdfText);

    foreach ($lines as $line) {
        $data[] = explode("\t", $line);
    }

    // Set output Excel file path
    $excelPath = 'excel/myexce1l.xlsx';

    try {
        if (file_exists($excelPath)) {
            $export = new PdfToExcelExport($data);
            Excel::store($export, $excelPath, null, \Maatwebsite\Excel\Excel::XLSX);
        } else {
            $export = new PdfToExcelExport($data);
            Excel::store($export, $excelPath, null, \Maatwebsite\Excel\Excel::XLSX);
        }

        return 'PDF converted to Excel successfully!';
    } catch (\Exception $e) {
        // Log or display the error message
        return 'Error: ' . $e->getMessage();
    }
}


public function convertPDFtoExcel1()
{
     // Path to the PDF file
     $pdfPath = public_path('pdf/staples-scan.pdf');

     // Set output Excel file path
     $excelPath = 'excel/myexce1l.xlsx';
    
    // Extract text from the PDF
    $pdfText = Pdf::getText($pdfPath);

    // Transform the text into an array for Excel
    $data = [];
    $lines = preg_split("/\r\n|\n|\r/", $pdfText);

    foreach ($lines as $line) {
        $data[] = explode("\t", $line);
    }

    // Create a new instance of PhpSpreadsheet
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set the text data in the Excel sheet
    foreach ($data as $row => $rowData) {
        foreach ($rowData as $column => $cellData) {
            $sheet->setCellValueByColumnAndRow($column + 1, $row + 1, $cellData);
        }
    }

    // Get the number of pages in the PDF
    $parser = new Parser();
    $pdf = $parser->parseFile($pdfPath);
    $numberOfPages = count($pdf->getPages());

    // Convert PDF pages to images
    $images = [];
    $imageManager = new ImageManager();
    for ($pageNumber = 1; $pageNumber <= $numberOfPages; $pageNumber++) {
        $imagePath = public_path('pdf/images/page_' . $pageNumber . '.jpg');
        $imageManager->make($pdfPath . '[' . ($pageNumber - 1) . ']')
            ->save($imagePath);
        $images[$pageNumber] = $imagePath;
    }

    // Insert images into the Excel sheet
    foreach ($images as $pageNumber => $imagePath) {
        $drawing = new Drawing();
        $drawing->setName("Image");
        $drawing->setDescription("PDF Image");
        $drawing->setPath($imagePath);
        $drawing->setCoordinates('A' . ($pageNumber + 1));
        $drawing->setHeight(100);
        $drawing->setWorksheet($sheet);
    }

    // Save the Excel file
    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save($excelPath);

    return 'PDF converted to Excel successfully!';
}

    


    

 }
