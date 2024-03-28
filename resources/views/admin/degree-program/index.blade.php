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
               <a href="{{route('degree-program.create')}}" class="btn btn-primary" id="AddUserText" data-toggle="modal"  data-target="#modal-default">Add Degree Program</a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
               <table id="example1" class="table table-bordered table-striped yajra-datatable">
                  <thead>
                     <tr>
                        <th>Sr #</th>
                        <th>Name</th>
                        <th>Total Years</th>
                        <th>Status</th>
                        <th>Action</th>
                     </tr>
                  </thead>
                  <tbody>
                    @if(count($programs))
                      @foreach($programs as $item)
                        <tr>
                            <td>1</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->total_years }}</td>
                            <td>active</td>
                            <td>
                                <a href="{{route('degree-program.edit',$item->id)}}" class="btn btn-primary btn-sm" ><i class="fa fa-edit"></i></a>
                                <form action="{{route('degree-program.destroy',$item->id)}}" method="POST" style="display: inline;">
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
            <h4 class="modal-title userTitle">Degree Program</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form id="quickForm" action="{{route('degree-program.store')}}" method="POST" enctype="multipart/form-data">
               @csrf
               <input type="hidden" name="id" id="id">
               <div class="form-group">
                  <label for="exampleInputEmail1">Degree name</label>
                  <input type="text" name="name" class="form-control" id="name" placeholder="Enter Degree name">
               </div>
               <div class="form-group">
                  <label for="exampleInputEmail1">Total Years</label>
                  <input type="number" name="total_years" class="form-control" id="total_years" placeholder="Enter Total Years">
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

       total_years: {
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
   
</script>
<script type="text/javascript">
   var csrf_token = $('meta[name="csrf-token"]').attr('content');
  
   
 
</script>
@endsection