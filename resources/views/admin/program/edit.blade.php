
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
                    <form id="quickForm" action="{{route('program.update',$program->id)}}" method="POST" enctype="multipart/form-data" >
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
                                        <option value="{{$item->id}}" @if($item->id == $program->major_id) selected @endif >{{$item->major_name}}</option>
                                         @php
                                            if($item->id == $program->major_id) 
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
                              @endphp
                            
                            <select name="concentration_id" id="concentration_id" class="form-control">
                                <option selected disabled>Select</option>
                                @if(count($concentrations)>0)
                                    @foreach($concentrations as $item)
                                     
                                    <option value="{{$item->id}}" @if($item->id == $program->concentration_id) selected @endif >{{$item->name}}</option>
                                    @endforeach  
                                @endif
                            </select>
                        </div>
                        
                            <div class="form-group">
                                <label for="exampleInputEmail1">Total Years</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Degree name" value="{{$program->name}}">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Total Unit</label>
                                <input type="number" name="total_unit" class="form-control" id="total_unit" value="{{$program->total_unit}}" placeholder="Enter Unit" requird>
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
                                  
                                  @if($program->file)
                                    <a href='{{asset("documents/files/$program->file")}}'>
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



</script>

@endsection