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
                            <form id="quickForm" action="{{ route('school.update', $data->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Owner Name</label>
                                    <input type="text" name="name" class="form-control" id="name"
                                        placeholder="Enter Course name" value="{{ $data->name }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">School Name</label>
                                    <input type="text" name="school_name" class="form-control" id="school_name"
                                        placeholder="Enter School Name" value="{{ $data->school_name }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">School Email</label>
                                    <input type="email" name="email" class="form-control" id="email"
                                        placeholder="Enter School Email" value="{{ $data->email }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Phone Number</label>
                                     <input type="text" name="phone" class="form-control" id="phone"
                                        placeholder="Enter Phone Number" value="{{ $data->phone }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">  Address</label>
                                    <input type="text" name="address" class="form-control" id="address"
                                        placeholder="Enter Address" value="{{ $data->address }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">  City</label>
                                    <input type="text" name="address" class="form-control" id="address"
                                        placeholder="Enter City" value="{{ $data->address }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">  State</label>
                                    <input type="text" name="state" class="form-control" id="state"
                                        placeholder="Enter State" value="{{ $data->state }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">  Country</label>
                                    <input type="text" name="country" class="form-control" id="country"
                                        placeholder="Enter Country" value="{{ $data->country }}" required>
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
    <!-- <script src="{{ asset('assets/js/countrystatecity.js?v2') }}"></script> -->

    <script src="{{ asset('assets/js/waitMe.js') }}"></script>
    <script>
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })



        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
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


        $(document).on('change', '#program_id', function() {
            var program_id = $("#program_id").val();
            $("#year").empty();
            $.ajax({
                url: "{{ route('get-term') }}",
                type: 'GET',
                /*dataType: 'json',*/
                data: {
                    'program_id': program_id
                },
                success: function(response) {
                    $("#year").append(`<option disabled selected>Select</option>`);
                    if (response) {
                        console.log(response)
                        for (var i = 0; i < response.length; i++) {

                            $("#year").append(
                                `<option value="${response[i].year}">${response[i].year}</option>`);
                        }
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(error) {
                    toastr.error("Some error occured!");
                }
            });
        });




        $("#concentration_id").change(function() {
            var concentration_id = $("#concentration_id").val();

            $("#program_id").empty();
            $.ajax({
                url: "{{ route('get-program') }}",
                type: 'GET',
                /*dataType: 'json',*/
                data: {
                    'concentration_id': concentration_id
                },
                success: function(response) {
                    $("#program_id").append(`<option disabled selected>Select</option>`);
                    if (response) {
                        for (var i = 0; i < response.length; i++) {
                            $("#program_id").append(
                                `<option value="${response[i].id}">${response[i].name}</option>`);
                        }
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(error) {
                    toastr.error("Some error occured!");
                }
            });
        });

        $("#major_id").change(function() {
            var major_id = $("#major_id").val();
            $("#concentration_id").empty();
            $.ajax({
                url: "{{ route('get-concentration') }}",
                type: 'GET',
                /*dataType: 'json',*/
                data: {
                    'major_id': major_id
                },
                success: function(response) {
                    $("#concentration_id").append(`<option disabled selected>Select</option>`);
                    if (response) {
                        for (var i = 0; i < response.length; i++) {
                            $("#concentration_id").append(
                                `<option value="${response[i].id}">${response[i].name}</option>`);
                        }
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(error) {
                    toastr.error("Some error occured!");
                }
            });
        })
        // For Yearas
        $("#year").change(function() {
            var year = $("#year").val();
            $("#term_id").empty();
            $.ajax({
                url: "{{ route('get-semesters') }}",
                type: 'GET',
                /*dataType: 'json',*/
                data: {
                    'year': year
                },
                success: function(response) {
                    $("#term_id").append(`<option disabled selected>Select</option>`);
                    if (response) {
                        for (var i = 0; i < response.length; i++) {
                            $("#term_id").append(
                                `<option value="${response[i]}">${response[i]}</option>`);
                        }
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(error) {
                    toastr.error("Some error occured!");
                }
            });
        })
    </script>

    <script type="text/javascript">
        var APP_URL = {!! json_encode(url('/')) !!}

        $(function() {

            $('#quickForm').validate({
                rules: {

                    name: {
                        required: true,
                    },
                    term_id: {
                        required: true,
                    },
                    major_id: {
                        required: true,
                    },
                    file: {
                        extension: "docx|rtf|doc|pdf",
                    },

                },
                messages: {
                    // terms: "Please accept our terms"
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endsection
