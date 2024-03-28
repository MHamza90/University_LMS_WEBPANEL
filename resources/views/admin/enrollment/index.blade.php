@extends('admin/layout/layout')
@section('title',' | Announcement List')
@section('header-script') 
  <style>
   /* .last-row td:first-child,
.last-row td:nth-child(2) {
  color: red;
} */
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
              
            </div>
            <!-- /.card-header -->
            <div class="card-body">

           
      <table class="table table-bordered table-striped yajra-datatable dataTable no-footer dtr-inline">
      <thead>
    <tr>

      <th>Student Name</th>
      <th>Major</th>
      <th>Program</th>
      <th>Term</th>
      <th>Course Name</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
  
  @foreach($courses as $course)
    @if($course->enrollment)
        @php 
        $majorsDisplayed = [];
        $programDisplayed = [];
        $prevMajor = null;
        @endphp
        @foreach($course->enrollment as $index => $enrollment)
            @php
                $major = $enrollment->course_one->term_one->program->major->major_name??null;
                $program = $enrollment->course_one->term_one->program->name??null;
                $bgColor = ($prevMajor === $major) ? 'rgba(0,0,0,.05)' : 'white';
                $prevMajor = $major;
            @endphp
            <tr style="background-color: {{ $bgColor }};">
                @if($loop->first)
                    <td rowspan="{{ count($course->enrollment) }}">{{ $course->first_name??null }} {{ $course->last_name??null }}</td>
                @endif
                @if(!in_array($major, $majorsDisplayed))
                    <td>{{ $major }}</td>
                    @php $majorsDisplayed[] = $major; @endphp
                @else
                    <td></td>
                @endif

                @if(!in_array($program, $programDisplayed))
                    <td>{{ $program }}</td>
                    @php $programDisplayed[] = $program; @endphp
                @else
                    <td></td>
                @endif
                <td>{{ $enrollment->course_one->term_one->name??null }}</td>
                <td>{{ $enrollment->course_one->course_name??null }}</td>
                <td>
                  <select name="" data-id="{{$enrollment->id}}" id="change-status{{$enrollment->id}}" class="form-control change-status">
                  @if($enrollment->status == 'block' || $enrollment->status == 'approve') 
                     <option value="approve" @if($enrollment->status == 'approve') selected @endif>Approve</option>
                     <option value="block" @if($enrollment->status == 'block') selected @endif>Block</option>
                  @else

                  <option value="pending" @if($enrollment->status == 'pending') selected @endif>Pending</option>
                  <option value="approve" @if($enrollment->status == 'approve') selected @endif>Approve</option>
                     <option value="block" @if($enrollment->status == 'block') selected @endif>Block</option>
                  @endif
                     
                  </select>
                </td>
            </tr>
            @if($enrollment->course_two)
                @php
                    $bgColor = ($prevMajor === $major) ? 'rgba(0,0,0,.05)' : 'white';
                    $prevMajor = $major;
                @endphp
                <tr style="background-color: {{ $bgColor }};">
                    @if(!in_array($major, $majorsDisplayed))
                        <td>{{ $enrollment->course_two->term_two->program->major->major_name }}</td>
                        @php $majorsDisplayed[] = $major; @endphp
                    @else
                        <td></td>
                    @endif
                    @if(!in_array($program, $programDisplayed))
                        <td>{{ $program }}</td>
                        @php $programDisplayed[] = $program; @endphp
                    @else
                        <td></td>
                    @endif
                    <td>{{ $enrollment->course_two->term_one->name }}</td>
                    <td>{{ $enrollment->course_two->course_name }}</td>
                    <td>Active</td>
                </tr>
            @endif
        @endforeach
    @endif
@endforeach

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
            <h4 class="modal-title userTitle">Announcement</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form id="quickForm" action="{{route('announcement.store')}}" method="POST" enctype="multipart/form-data">
               @csrf
               
               <div class="form-group">
                  <label for="exampleInputEmail1">Announcement</label>
                  <textarea type="text" name="description" class="form-control" id="description" placeholder="Enter Announcement"></textarea>
                  <input type="hidden" name="type" id="type" value="Announcement">
               </div>
               <div class="form-group">
                  <label for="exampleInputEmail1">Date Time</label>
                  <input type="datetime-local" name="date_time" class="form-control" id="date_time" placeholder="Enter Total Years">
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
      
        description: {
        required: true,
       },

       date_time: {
        required: true,
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

  
 $(document).on('change','.change-status',function(){
      var id = $(this).attr("data-id");
      var status = $("#change-status"+id).val();
      if (confirm('Are you sure you want to delete this item?')) {
         $.ajax({
            url : "{{route('enrollment-status')}}", 
            type: 'GET',
            /*dataType: 'json',*/
            data: {'status': status,'id':id},
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

    }else{
     
    }
      
 });  
</script>
<script type="text/javascript">
   var csrf_token = $('meta[name="csrf-token"]').attr('content');
  
   
 
</script>
@endsection