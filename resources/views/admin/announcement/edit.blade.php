
@extends('admin/layout/layout')
@section('title',' | Announcement Edit')
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
                    <form id="quickForm" action="{{route('announcement.update',$announcement->id)}}" method="POST" enctype="multipart/form-data" >
                        @csrf
                        @method('PATCH')
                        
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Announcement</label>
                                    <textarea type="text" name="description" class="form-control" id="description" placeholder="Enter Announcement">{{$announcement->description}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Date Time</label>
                                    <input type="datetime-local" name="date_time" class="form-control" id="date_time" value="{{$announcement->date_time}}" placeholder="Enter Total Years">
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