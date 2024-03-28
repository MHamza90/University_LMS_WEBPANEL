@extends('admin/layout/layout')
@section('header-script')

<style>
   .dataTables_wrapper .dataTables_processing {
    display:    none;
    position:   fixed;
    z-index:    1000;
    top:        0;
    left:       0;
    /* height:     100%; */
    width:      100%;
    background: rgba( 255, 255, 255, .8 )
                url('http://i.stack.imgur.com/FhHRx.gif')
                50% 50%
                no-repeat;
   }
</style>
@endsection
@section('body-section')
<br>
<section class="content">
<div class="container-fluid">
   <div class="row">
      <div class="col-12">
         <div class="card">
            <div class="card-header">
               <a href="{{route('course.create')}}" class="btn btn-primary" id="AddUserText" data-toggle="modal"  data-target="#modal-default">Add Course</a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

               <table id="example1" class="table table-bordered table-striped yajra-datatable">
                  <thead>
                     <tr>
                        <th>Major</th>
                        <th>Program</th>
                        <th>Concentration</th>
                        <th>Year</th>
                        <th>Term</th>

                        <th>Course Name</th>

                     </tr>
                  </thead>
                    <tbody>
                            @if(count($courses)>0)
                                @foreach ($courses as $item)

                                    
                                @endforeach
                            @endif
                    </tbody>
                  </table>
            </div>
            <!-- /.card-body -->
         </div>
      </div>
   </div>
</div>
<div class="modal fade myModel" id="modal-default">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title userTitle">Course</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form id="quickForm" action="{{route('course.store')}}" method="POST" enctype="multipart/form-data">
               @csrf
               <div class="form-group">
                  <label for="exampleInputEmail1">Major</label>

                  <select name="major_id" id="major_id" class="form-control">
                      <option selected disabled>Select</option>
                      @if(count($majors)>0)
                        @foreach($majors as $item)
                          <option value="{{$item->id}}">{{$item->major_name}}</option>
                        @endforeach
                      @endif
                  </select>
               </div>

               <div class="form-group">
                  <label for="exampleInputEmail1">Concentrations</label>

                  <select name="concentration_id" id="concentration_id" class="form-control">

                  </select>
               </div>

               <div class="form-group">
                  <label for="exampleInputEmail1">Program</label>

                  <select name="program_id" id="program_id" class="form-control">

                  </select>
               </div>

               <div class="form-group">
                  <label for="exampleInputEmail1">Year</label>

                  <select name="year" id="year" class="form-control">
                     <option selected disabled>Select</option>
                  </select>
               </div>

               <div class="form-group">
                  <label for="exampleInputEmail1">Term</label>

                  <select name="term_id" id="term_id" class="form-control">
                     <option selected disabled>Select</option>

                    {{-- @if(count($myterms))
                      @foreach($myterms as $item)
                      <option value="{{$item->id}}">{{$item->name}}</option>
                      @endforeach
                     @endif
                     --}}
                  </select>
               </div>

               <div class="form-group">
                  <label for="exampleInputEmail1">Name</label>
                  <input type="text" name="course_name" class="form-control" id="course_name" placeholder="Enter Course Name" required>
               </div>

               <div class="form-group">
                  <label for="exampleInputEmail1">Course Number</label>
                  <input type="text" name="course_number" class="form-control" id="course_number" placeholder="Enter Course Number" required>
               </div>

               <div class="form-group">
                  <label for="exampleInputEmail1">Course Career</label>
                  <input type="text" name="course_career" class="form-control" id="course_career" placeholder="Enter Course Career" >
               </div>

               <div class="form-group">
                  <label for="exampleInputEmail1">Unit</label>
                  <input type="number" name="unit" class="form-control" id="unit" placeholder="Enter Unit" >
               </div>

               <div class="form-group">
                  <label for="exampleInputEmail1">File</label>
                  <input type="file" name="file" class="form-control" id="file">
               </div>


               <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
               </div>
            </form>
         </div>
      </div>
      <!-- /.modal-content -->
   </div>
   <!-- /.modal-dialog -->
</div>

@endsection
@section('footer-section')
@endsection
@section('footer-script')
<script src="{{asset('assets/js/waitMe.js')}}"></script>
<script>
  var APP_URL = {!! json_encode(url('/')) !!}

   $(function () {

   $('#quickForm').validate({
     rules: {

        name: {
        required: true,
       },
       term_id:{
        required:true,
       },
       major_id:{
        required:true,
       },

       program_id:{
        required:true,
       },
       year:{
        required:true,
       },
       concentration_id:{
         required:true,
       },
       file:{
            extension: "docx|rtf|doc|pdf",
       },

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
   image.src = URL.createObjectURL(event.target.files[0]);
   };

   $(".switch-input").change(function(){

    if(this.checked)
        var status=1;
    else
        var status=0;
    $.ajax({
        url : "{{route('course-status')}}",
        type: 'GET',
        /*dataType: 'json',*/
        data: {'id': this.id,'status':status},
        success: function (response) {
          if(response)
            {
             toastr.success(response.message);
            }else{
             toastr.error(response.message);
            }
        }, error: function (error) {
            toastr.error("Some error occured!");
        }
    });
});

   $(document).on('change', '#program_id', function() {
      var program_id = $("#program_id").val();
         $("#year").empty();
         $.ajax({
            url : "{{route('get-term')}}",
            type: 'GET',
            /*dataType: 'json',*/
            data: {'program_id': program_id},
            success: function (response) {
               $("#year").append(`<option disabled selected>Select</option>`);
               if(response)
                  {
                     console.log(response)
                  for(var i = 0;i<response.length;i++)
                  {

                     $("#year").append(`<option value="${response[i].year}">${response[i].year}</option>`);
                  }
                  }else{
                  toastr.error(response.message);
                  }
            }, error: function (error) {
                  toastr.error("Some error occured!");
            }
         });
   });




$("#concentration_id").change(function(){
    var concentration_id = $("#concentration_id").val();

    $("#program_id").empty();
    $.ajax({
        url : "{{route('get-program')}}",
        type: 'GET',
        /*dataType: 'json',*/
        data: {'concentration_id': concentration_id},
        success: function (response) {
         $("#program_id").append(`<option disabled selected>Select</option>`);
          if(response)
            {
              for(var i = 0;i<response.length;i++)
              {
                $("#program_id").append(`<option value="${response[i].id}">${response[i].name}</option>`);
              }
            }else{
             toastr.error(response.message);
            }
        }, error: function (error) {
            toastr.error("Some error occured!");
        }
    });
});

$("#major_id").change(function(){
    var major_id = $("#major_id").val();
    $("#concentration_id").empty();
    $.ajax({
        url : "{{route('get-concentration')}}",
        type: 'GET',
        /*dataType: 'json',*/
        data: {'major_id': major_id},
        success: function (response) {
         $("#concentration_id").append(`<option disabled selected>Select</option>`);
          if(response)
            {
              for(var i = 0;i<response.length;i++)
              {
                $("#concentration_id").append(`<option value="${response[i].id}">${response[i].name}</option>`);
              }
            }else{
             toastr.error(response.message);
            }
        }, error: function (error) {
            toastr.error("Some error occured!");
        }
    });
})
// For Yearas
$("#year").change(function(){
    var year = $("#year").val();
    $("#term_id").empty();
    $.ajax({
        url : "{{route('get-semesters')}}",
        type: 'GET',
        /*dataType: 'json',*/
        data: {'year': year},
        success: function (response) {
         $("#term_id").append(`<option disabled selected>Select</option>`);
          if(response)
            {
              for(var i = 0;i<response.length;i++)
              {
                $("#term_id").append(`<option value="${response[i]}">${response[i]}</option>`);
              }
            }else{
             toastr.error(response.message);
            }
        }, error: function (error) {
            toastr.error("Some error occured!");
        }
    });
})



</script>
<script type="text/javascript">
   var csrf_token = $('meta[name="csrf-token"]').attr('content');



</script>
@endsection
