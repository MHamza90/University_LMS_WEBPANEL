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
               <a href="{{route('term.create')}}" class="btn btn-primary" id="AddUserText" data-toggle="modal"  data-target="#modal-default">Add Term</a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
               <table id="example1" class="table table-bordered table-striped yajra-datatable">
                  <thead>
                     <tr>
                        
                        <th>Major</th>
                        <th>Concentration / Program</th>
                        <th>Term</th>
                        <th>Status</th>
                        <th>Action</th>
                     </tr>
                  </thead>
                  <tbody>
                  @if(count($terms)>0)
                        @php
                           $prev_major = '';
                           $prev_program = '';
                        @endphp
                        @foreach($terms as $item)
                        
                         @isset($item->major->major_name)
                           @if($prev_major != $item->major->major_name || $prev_program != $item->program->name)
                              @php
                                    $rowspan = count($terms->where('major_id', $item->major_id)->where('program_id', $item->program_id));
                                    $prev_major = $item->major->major_name??null;
                                    $prev_program = $item->program->name??null;
                              @endphp
                              <tr>
                                    <td rowspan="{{ $rowspan }}">{{ $prev_major }}</td>
                                    <td rowspan="{{ $rowspan }}"><b>{{$item->concentration->name??null}}</b>   {{ $prev_program }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                       <div class="form-group">
                                          <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                <input type="checkbox" class="custom-control-input switch-input" id="{{$item->id}}" {{($item->status==1)?"checked":""}}>
                                                <label class="custom-control-label" for="{{$item->id}}"></label>
                                          </div>
                                       </div>
                                    </td>
                                    <td>
                                       <a href="{{route('term.edit',$item->id)}}" class="btn btn-primary btn-sm" ><i class="fa fa-edit"></i></a>
                                       <form action="{{route('term.destroy',$item->id)}}" method="POST" style="display: inline;">
                                          @method('DELETE')  
                                          @csrf
                                          <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                       </form>
                                    </td>
                              </tr>
                            @else
                              <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                       <div class="form-group">
                                          <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                <input type="checkbox" class="custom-control-input switch-input" id="{{$item->id}}" {{($item->status==1)?"checked":""}}>
                                                <label class="custom-control-label" for="{{$item->id}}"></label>
                                          </div>
                                       </div>
                                    </td>
                                    <td>
                                       <a href="{{route('term.edit',$item->id)}}" class="btn btn-primary btn-sm" ><i class="fa fa-edit"></i></a>
                                       <form action="{{route('term.destroy',$item->id)}}" method="POST" style="display: inline;">
                                          @method('DELETE')  
                                          @csrf
                                          <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                       </form>
                                    </td>
                              </tr>
                           @endif
                          @endisset 
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
            <h4 class="modal-title userTitle">Term</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form id="quickForm" action="{{route('term.store')}}" method="POST" enctype="multipart/form-data">
               @csrf

               <div class="form-group">
                  <label for="">Major</label>
                
                  <select name="major_id" id="major_id" class="form-control">
                      <option selected disabled>Select</option>
                      @if(count($majors)>0)
                        @foreach($majors as $item)
                          <option value="{{$item->id}}">{{$item->major_name}}</option>
                        @endforeach  
                      @endif
                  </select>
               </div>

               <div class="form-group">
                  <label for="">Concentrations</label>
                
                  <select name="concentration_id" id="concentration_id" class="form-control">
                    
                  </select>
               </div>

               <div class="form-group">
                  <label for="">Program</label>
                
                  <select name="program_id" id="program_id" class="form-control"></select>
               </div>

               <div class="form-group">
                  <label for="">Term</label>
                  <select name="name" id="term" class="form-control">

                  </select>
                  <!-- <input type="text" name="name" class="form-control" id="name" placeholder="Enter Term"> -->
                  {{--<select name="name" id="name" class="form-control">
                     <option selected disabled>Select</option>
                    
                     @if(count($terms))
                      @foreach($terms as $item)
                      <option value="{{$item->id}}">{{$item->name}}</option>
                      @endforeach
                     @endif
                  </select>
               </div> --}}

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
       program_id:{
        required:true,
       },
       major_id:{
        required:true,
       }
       
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
        url : "{{route('term-status')}}", 
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


// $("#major_id").change(function(){
//     var major_id = $("#major_id").val();
//     $("#program_id").empty();
//     $.ajax({
//         url : "{{route('get-program')}}", 
//         type: 'GET',
//         /*dataType: 'json',*/
//         data: {'major_id': major_id},
//         success: function (response) {
//           if(response)
//             {
//               for(var i = 0;i<response.length;i++)
//               {
//                 $("#program_id").append(`<option value="${response[i].id}">${response[i].name}</option>`);
//               }
//             }else{
//              toastr.error(response.message);
//             } 
//         }, error: function (error) {
//             toastr.error("Some error occured!");
//         }
//     });
// });


// $(document).change('#concentration_id',function(){
$("#concentration_id").change(function(){
    var concentration_id = $("#concentration_id").val();
  
    $("#program_id").empty();
    $.ajax({
        url : "{{route('get-program')}}", 
        type: 'GET',
        /*dataType: 'json',*/
        data: {'concentration_id': concentration_id},
        success: function (response) {
         $("#program_id").append(`<option disabled selected>Select</option>`);
          if(response)
            {
              for(var i = 0;i<response.length;i++)
              {
                $("#program_id").append(`<option data-name="${response[i].name}" value="${response[i].id}">${response[i].name}</option>`);
              }
            }else{
             toastr.error(response.message);
            } 
        }, error: function (error) {
            toastr.error("Some error occured!");
        }
    });
});




      $("#major_id").change(function(){
         var major_id = $("#major_id").val();
               $("#concentration_id").empty();
         
               $.ajax({
                  url : "{{route('get-concentration')}}", 
                  type: 'GET',
                  
                  data: {'major_id': major_id},
                  success: function (response) {
                     $("#concentration_id").append(`<option disabled selected>Select</option>`);
                     if(response)
                        {
                        for(var i = 0;i<response.length;i++)
                        {
                           $("#concentration_id").append(`<option value="${response[i].id}">${response[i].name}</option>`);
                        }
                        }else{
                        toastr.error(response.message);
                        } 
                  }, error: function (error) {
                        toastr.error("Some error occured!");
                  }
               });
      });

$("#program_id").change(function(){
         // var program_id = $(this).attr('data-name');
         var program_id = $(this).find("option:selected").data("name");
        
         $("#term").empty();
   
         $.ajax({
            url : "{{route('get-semster')}}", 
            type: 'GET',
            
            data: {'program_id': program_id},
            success: function (response) {
               $("#term").append(`<option disabled selected>Select</option>`);
               if(response)
                  {
                  for(var i = 0;i<response.length;i++)
                  {
                     $("#term").append(`<option value="${response[i].id}">${response[i].name}</option>`);
                  }
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