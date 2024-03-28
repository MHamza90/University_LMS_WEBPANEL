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
               <!-- <a href="#!" class="btn btn-primary" id="AddUserText" data-toggle="modal"  data-target="#modal-default">Add Artist</a> -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
               <table id="example1" class="table table-bordered table-striped yajra-datatable">
                  <thead>
                     <tr>
                       
                        <th>Student Name</th>
                        <th>Todo</th>
                         <th>Action</th>
                     </tr>
                  </thead>
                  <tbody>
                  @foreach($todos as $item)
            <tr>
                <td rowspan="{{ count($item->todo) }}">{{ $item->first_name }} {{ $item->last_name }}</td>
                            @foreach($item->todo as $key => $todo)
                                @if($key == 0)
                                    <td>{{ $todo->what_to_do }}</td>
                                    <td >Action</td>
                                @else
                                    <tr>
                                        <td>{{ $todo->what_to_do }}</td>
                                        <td ><a href="{{route('delete-todo',$todo->id)}}" onclick="return confirm('Are you sure?')"   class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a></td> 
                                    </tr>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                  </tbody>
               </table>
            </div>
            <!-- /.card-body -->
         </div>
      </div>
   </div>
</div>


@endsection
@section('footer-section')
@endsection
@section('footer-script')
<script src="{{asset('assets/js/waitMe.js')}}"></script>
<script>
  var APP_URL = {!! json_encode(url('/')) !!}
  
   
</script>
@endsection