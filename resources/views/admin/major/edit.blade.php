
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
                    <form id="quickForm" action="{{route('major.update',$major->id)}}" method="POST" enctype="multipart/form-data" >
                        @csrf
                        @method('PATCH')
                        
                            <div class="form-group">
                                <label for="exampleInputEmail1">Degree name</label>
                                <input type="text" name="major_name" class="form-control" id="major_name" placeholder="Enter Degree name" value="{{$major->major_name}}">
                            </div>

                            <div class="form-group">
                              <label for="exampleInputEmail1">Total Unit</label>
                              <input type="number" name="total_unit" class="form-control" id="total_unit" value="{{$major->total_unit}}" placeholder="Enter Total Unit" requird> 
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
</script>

<script type="text/javascript">
 
 var APP_URL = {!! json_encode(url('/')) !!}



</script>

@endsection