
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
            <a class="btn btn-primary" href="{{ route('roles.create') }}"> Create New Role</a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <table class="table table-bordered table-striped">
                <tr>
                  <th>No</th>
                  <th>Name</th>
                  <th>Status</th>
                  <th width="280px">Action</th>
                </tr>
                @forelse ($roles as $key => $role)
                  <tr>
                      <td>{{ $key+1 }}</td>
                      <td>{{ ucfirst($role->name) }}</td>
                       <td>
                      <div class="form-group">
                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                            <input type="checkbox" class="custom-control-input switch-input" id="{{$role->id}}" {{($role->status==1)?"checked":""}}>
                            <label class="custom-control-label" for="{{$role->id}}"></label>
                        </div>
                      </div>
                    </td>
                      <td>
                          <a class="btn btn-info btn-sm" href="{{ route('roles.show',$role->id) }}"><i class="fa fa-eye"></i></a>
                          
                          <a class="btn btn-primary btn-sm" href="{{ route('roles.edit',$role->id) }}"><i class="fa fa-edit"></i></a>
                          
                          @if(auth()->user()->role_id == 'admin' && $role->name == 'admin')    
                            @can('role-delete')
                            
                              <form action="{{route('roles.destroy', $role->id)}}" method="POST" style="display:inline">
                                  @method('DELETE')
                                  @csrf
                                
                                 <button  onclick="return confirm('Are you sure?')"  type="submit"  class="btn btn-danger btn-sm" disabled><i class="fa fa-trash"></i></button>
                              </form>
                            @endcan
                            @else
                            @can('role-delete')
                           
                                <form action="{{route('roles.destroy', $role->id)}}" method="POST" style="display:inline">
                                   @method('DELETE')
                                   @csrf
                                   <button  onclick="return confirm('Are you sure?')"  type="submit"  class="btn btn-danger btn-sm" ><i class="fa fa-trash"></i></button>
                                 
                                </form>
                            @endcan
                          @endif
                      </td>
                  </tr>
                  @empty
                  @endforelse
                </table>
                {!! $roles->render() !!}
            </div>
            <!-- /.card-body -->
         </div>
      </div>
        </div>
    </div>
</section>

@endsection


@section('footer-section')
@endsection

@section('footer-script')


<script>



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
</script>

<script type="text/javascript">
 
 var APP_URL = {!! json_encode(url('/')) !!}
 
 $(".switch-input").change(function(){
    
    if(this.checked)
        var status=1;
    else
        var status=0;
    $.ajax({
        url : "{{route('role-change-status')}}", 
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

@endsection