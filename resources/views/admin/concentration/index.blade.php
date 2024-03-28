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
               <a href="{{route('concentration.create')}}" class="btn btn-primary" id="AddUserText" data-toggle="modal"  data-target="#modal-default">Add Concentration</a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
               <table id="example1" class="table table-bordered table-striped yajra-datatable">
                  <thead>
                     <tr>
                       
                     <th rowspan="2">Major</th>
            <th rowspan="2">Name</th>
            <th rowspan="2">File</th>
            <th rowspan="2">Status</th>
            <th rowspan="2">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                  @if(count($concentrations) > 0)
            @foreach($concentrations->groupBy('major.major_name') as $majorName => $concentrationGroup)
                @foreach($concentrationGroup as $index => $concentration)
                    <tr>
                        @if($index == 0)
                            <td rowspan="{{count($concentrationGroup)}}">{{ $majorName }}</td>
                        @endif
                        <td>{{ $concentration->name }}</td>
                        <td>
                            @if($concentration->file)
                                <a href='{{ asset("documents/files/$concentration->file") }}'>
                                    <img src='{{ asset("documents/files/icon.svg.png") }}' alt="pdf" width="50px;">
                                </a>
                            @endif
                        </td>
                        <td>
                            <div class="form-group">
                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input type="checkbox" class="custom-control-input switch-input" id="{{ $concentration->id }}" {{ $concentration->status == 1 ? "checked" : "" }}>
                                    <label class="custom-control-label" for="{{ $concentration->id }}"></label>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('concentration.edit', $concentration->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                            <form action="{{ route('concentration.destroy', $concentration->id) }}" method="POST" style="display: inline;">
                                @method('DELETE')
                                @csrf
                                <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
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
            <h4 class="modal-title userTitle">Announcement</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form id="quickForm" action="{{route('concentration.store')}}" method="POST" enctype="multipart/form-data">
               @csrf
               
             
              

               <div class="form-group">
                  <label for="exampleInputEmail1">Major</label>
                  <select name="major_id" id="major_id"  class="form-control">
                     <option selected disabled>Select</option>
                     @if(count($majors)>0)
                       @foreach($majors as $item)
                        <option value="{{$item->id}}">{{$item->major_name}}</option>
                        @endforeach
                     @endif
                  </select>
               </div>
               <div class="form-group">
                  <label for="exampleInputEmail1">Name</label>
                  <input type="name" name="name" class="form-control" id="name" placeholder="Enter name">
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

       major_id: {
        required: true,
       },
       file:{
            extension: "docx|rtf|doc|pdf",
       },
      
       
     },
     messages: {
         resume:{
               extension:"select valied input file format"
         }
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
        url : "{{route('concentration-status')}}", 
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
   
</script>
<script type="text/javascript">
   var csrf_token = $('meta[name="csrf-token"]').attr('content');
  
   
 
</script>
@endsection