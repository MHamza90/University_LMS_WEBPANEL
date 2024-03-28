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
               <a href="{{route('major.create')}}" class="btn btn-primary" id="AddUserText" data-toggle="modal"  data-target="#modal-default">Add Major</a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
               <table id="example1" class="table table-bordered table-striped yajra-datatable">
                  <thead>
                     <tr>
                        <th>Sr #</th>
                        <th>Name</th>
                        <th>Total Unit</th>
                        <th>Status</th>
                        <th>Action</th>
                     </tr>
                  </thead>
                  <tbody>
                    @if(count($majors)>0)
                      @foreach($majors as $item)
                        <tr>
                            <td>1</td>
                            <td>{{ $item->major_name }}</td>
                            <td>{{ $item->total_unit }}</td>
                          
                        
                            <td>
                                <div class="form-group">
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input switch-input" id="{{$item->id}}" {{($item->status==1)?"checked":""}}>
                                        <label class="custom-control-label" for="{{$item->id}}"></label>
                                    </div>
                                </div>
                                </td>
                            <td>
                                <a href="{{route('major.edit',$item->id)}}" class="btn btn-primary btn-sm" ><i class="fa fa-edit"></i></a>
                                <form action="{{route('major.destroy',$item->id)}}" method="POST" style="display: inline;">
                                  @method('DELETE')  
                                  @csrf
                                  <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>

                                </form>
                            </td>
                        </tr>
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
            <h4 class="modal-title userTitle">Major</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form id="quickForm" action="{{route('major.store')}}" method="POST" enctype="multipart/form-data">
               @csrf
            
               <div class="form-group">
                  <label for="exampleInputEmail1">Name</label>
                  <input type="text" name="major_name" class="form-control" id="major_name" placeholder="Enter Major Name" requird> 
               </div>

               <div class="form-group">
                  <label for="exampleInputEmail1">Total Unit</label>
                  <input type="number" name="total_unit" class="form-control" id="total_unit" placeholder="Enter Total Unit" requird> 
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
      
        major_name: {
        required: true,
       },

       total_unit: {
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

   $(".switch-input").change(function(){
    
    if(this.checked)
        var status=1;
    else
        var status=0;
    $.ajax({
        url : "{{route('major-status')}}", 
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