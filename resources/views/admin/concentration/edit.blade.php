
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
                    <form id="quickForm" action="{{route('concentration.update',$concentration->id)}}" method="POST" enctype="multipart/form-data" >
                        @csrf
                        @method('PATCH')
                               

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Major</label>
                                    <select name="major_id" id="major_id"  class="form-control">
                                       
                                        @if(count($majors)>0)
                                        @foreach($majors as $item)
                                            <option value="{{$item->id}}" @if($item->id == $concentration->major_id) selected @endif>{{$item->major_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Name</label>
                                    <input type="text" name="name" class="form-control" id="name" value="{{$concentration->name}}" placeholder="Enter Total Years">
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
                                  
                                  @if($concentration->file)
                                    <a href='{{asset("documents/files/$concentration->file")}}'>
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
</script>

<script type="text/javascript">
 
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

</script>

@endsection