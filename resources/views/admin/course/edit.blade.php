
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
                    <form id="quickForm" action="{{route('course.update',$course->id)}}" method="POST" enctype="multipart/form-data" >
                        @csrf
                        @method('PATCH')
                        
                           


                            <div class="form-group">
                            <label for="exampleInputEmail1">Major</label>
                            
                            <select name="major_id" id="major_id" class="form-control">
                                <option selected disabled>Select</option>
                                @php 
                                     $major_ids = [];
                                    @endphp
                                @if(count($majors)>0)
                                    @foreach($majors as $item)

                                    <option value="{{$item->id}}" @if($item->id == $course->major_id) selected @endif>{{$item->major_name}}</option>
                                      @php
                                        if($item->id == $course->major_id) 
                                        array_push($major_ids,$item->id);
                                      @endphp
                                    @endforeach  
                                @endif
                            </select>
                        </div>
                       
                        <div class="form-group">
                            <label for="exampleInputEmail1">Concentration</label>
                            @php 
                            $concentrations = \App\Models\Concentration::whereIn('major_id',$major_ids)->get();
                          
                               $program_ids = [];
                               $concentration_ids = [];
                              @endphp
                            
                            <select name="concentration_id" id="concentration_id" class="form-control">
                                <option selected disabled>Select</option>
                                @if(count($concentrations)>0)
                                    @foreach($concentrations as $item)
                                     
                                    <option value="{{$item->id}}" @if($item->id == $course->concentration_id) selected @endif >{{$item->name}}</option>
                                    @php
                                        if($item->id == $course->concentration_id) 
                                        array_push($concentration_ids,$item->id);
                                      @endphp
                                    @endforeach  
                                @endif
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Program</label>
                            @php 
                            $programs = \App\Models\Program::whereIn('concentration_id',$concentration_ids)->get();
                               $program_ids = [];
                               $program_name = [];
                              @endphp
                            
                            <select name="program_id" id="program_id" class="form-control">
                                <option selected disabled>Select</option>
                                @if(count($programs)>0)
                                    @foreach($programs as $item)
                                      @php
                                        if($item->id == $course->program_id){ 
                                        array_push($program_ids,$item->id);
                                        array_push($program_name,$item->name);
                                        }
                                        else{
                                          $program_name = [];
                                        }
                                      @endphp
                                    <option value="{{$item->id}}" @if($item->id == $course->program_id) selected @endif >{{$item->name}}</option>
                                    @endforeach  
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                          @php 
                            
                            $program_name = \App\Models\Semester::whereIn('program_name',$program_name)->groupBy('program_name')->pluck('program_name');
                            $years = \App\Models\Semester::whereIn('program_name',$program_name)->groupBy('year')->pluck('year');
                           
                          @endphp
                            <label for="exampleInputEmail1">Year</label>
                            <select name="year" id="year" class="form-control">
                                <option selected disabled>Select</option>
                                @if(count($years)>0)
                                    @foreach($years as $item)
                                    <option value="{{$item}}" @if($item == $course->year) selected @endif >{{$item}}</option>
                                    @endforeach  
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Term</label>
                            @php 
                            
                            $terms = \App\Models\Term::whereIn('program_id',$program_ids)->get();
                            
                            
                            $semster = \App\Models\Semester::whereIn('year',$years)->groupBy('season')->pluck('season');
                        
                            @endphp
                            <select name="term_name" id="term_name" class="form-control">
                                <option selected disabled>Select</option>
                                @if(count($semster)>0)
                                    @foreach($semster as $item)
                                    <option value="{{$item}}" @if($item == $course->term_name) selected @endif >{{$item}}</option>
                                    @endforeach  
                                @endif
                            </select>
                        </div>

                        <div class="form-group">
                                <label for="exampleInputEmail1">Course name</label>
                                <input type="text" name="course_name" class="form-control" id="course_name" placeholder="Enter Course name" value="{{$course->course_name}}" required>
                            </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Course Number</label>
                            <input type="text" name="course_number" class="form-control" id="course_number" value="{{$course->course_number}}"  placeholder="Enter Course Number" required>
                        </div>

                        <div class="form-group">
                          <label for="exampleInputEmail1">Course Career</label>
                          <input type="text" name="course_career" class="form-control" id="course_career" value="{{$course->course_career}}" placeholder="Enter Course Career" required>
                      </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Unit</label>
                            <input type="number" name="unit" class="form-control" id="unit" value="{{$course->unit}}" placeholder="Enter Unit" required>
                        </div>

                        <div class="row">
                          <div class="col-md-6">
                              <div class="form-group">
                                  <label for="exampleInputEmail1">File</label>
                                  <input type="file" name="file" class="form-control" id="file">
                              </div>
                          </div>

                          <br>
                          <div class="col-md-6">
                            
                            @if($course->file)
                              <a href='{{asset("documents/files/$course->file")}}'>
                                <br><br>
                                  <img src='{{asset("documents/files/icon.svg.png")}}' alt="pdf" width="50px;">
                              </a>
                            @endif
                          </div>

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

 
   
   
   
   var loadFile = function(event) {
   var image = document.getElementById('output');
   image.src = URL.createObjectURL(event.target.files[0])
   };


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

</script>

@endsection