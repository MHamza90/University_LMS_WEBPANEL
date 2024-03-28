
@extends('admin/layout/layout')

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
                    <form id="quickForm" action="{{route('degree-program.update',$program->id)}}" method="POST" enctype="multipart/form-data" >
                        @csrf
                        @method('PATCH')
                        
                            <div class="form-group">
                                <label for="exampleInputEmail1">Degree name</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Degree name" value="{{$program->name}}">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Total Years</label>
                                <input type="number" name="total_years" class="form-control" id="total_years" placeholder="Enter Total Years" value="{{$program->total_years}}" >
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
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