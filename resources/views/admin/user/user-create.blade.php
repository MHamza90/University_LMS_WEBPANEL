
@extends('admin/layout/layout')
@section('title',' | User Create')
@section('header-script')

@endsection

@section('body-section')
<br>
 <section class="content">
    <div class="container-fluid">
    
        <div class="row">
          <div class="col-12">
              <div class="card">
                  <div class="card-header">
                  {!! Form::open(array('route' => 'users.store','method'=>'POST','enctype'=>'multipart/form-data','id'=>'quickForm')) !!}
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>First Name:</strong>
                                {!! Form::text('first_name', null, array('placeholder' => 'First Name','class' => 'form-control','required' => 'true')) !!}
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Last Name:</strong>
                                {!! Form::text('last_name', null, array('placeholder' => 'Last Name','class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Email:</strong>
                                {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control','required' => 'true')) !!}
                            </div>
                            @error('email')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Password:</strong>
                                {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control','required' => 'true','id'=>'password')) !!}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Confirm Password:</strong>
                                {!! Form::password(null, array('placeholder' => 'Confirm Password','class' => 'form-control','required' => 'true','id'=>'confirm-password')) !!}
                            </div>
                        </div>


                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Phone Number:</strong>
                                {!! Form::number('phone_number', null, array('placeholder' => 'Phone Number','class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Address:</strong>
                                {!! Form::textarea('address', null, array('placeholder' => 'Address','class' => 'form-control','rows'=>5)) !!}
                            </div>
                        </div>
                      
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Role:</strong>
                                
                                <select name="role_id" id="role_id" class="form-control">
                                @forelse($roles as $key => $item)
                                   <option value="{{$item}}">{{$item}}</option>
                                @empty
                                @endforelse   
                                </select>
                                
                            </div>
                        </div>
                        
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Profile:</strong>
                                {!! Form::file('profile', array('class' => 'form-control', 'onchange'=>'loadFile(event)')) !!}
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                  </div>
              </div> 
          </div>   
        </div>
    </div>
</section>

@endsection


@section('footer-section')
@endsection

@section('footer-script')

<!-- <script src="{{asset('assets/js/countrystatecity.js?v2')}}"></script> -->

<script src="{{asset('assets/js/waitMe.js')}}"></script>
<script>

$('.select2').select2()

//Initialize Select2 Elements
$('.select2bs4').select2({
  theme: 'bootstrap4'
})



  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": []
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });

  $(function () {
   
   $('#quickForm').validate({
     rules: {
      
       first_name: {
        required: true,
       },

       last_name: {
        required: true,
       },
       profile: {
            // required: true,
            extension: "JPEG|PNG|JPG",
        },
        password_confirm : {
           equalTo : "#password"
        }
       
     },
     messages: {
       // terms: "Please accept our terms"
     },
     errorElement: 'span',
     errorPlacement: function (error, element) {
       error.addClass('invalid-feedback');
       element.closest('.form-group').append(error);
     },
     highlight: function (element, errorClass, validClass) {
       $(element).addClass('is-invalid');
     },
     unhighlight: function (element, errorClass, validClass) {
       $(element).removeClass('is-invalid');
     }
   });
   });
   
   
   
   var loadFile = function(event) {
   var image = document.getElementById('output');
   image.src = URL.createObjectURL(event.target.files[0])
   };
</script>

<script type="text/javascript">
 
 var APP_URL = {!! json_encode(url('/')) !!}



</script>

@endsection