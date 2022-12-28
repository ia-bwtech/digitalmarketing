@extends('admin.layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="filtersdiv">

                <style>
                    .filter-control {
                        display: inline;
                    }

                    .filters .select2-container--default .select2-selection--single {
                        /* width: 220px; */
                    }
                </style>

                <div class="filters row" style="display: none;">
                    <div class="col-md-12">


                        <div class="float-left mx-3 my-3">
                            <label>Role:</label>
                            <select style="width: 120%" class="form-control filter-control filter-select" id="role_id"
                                name="role_id" onchange="filter(name=this.options[this.selectedIndex].value)">
                                <option @if (request()->get('role_id') == null) selected @endif value="">Select
                                </option>
                                <option value="1">Handicapper</option>
                                <option value="0">Bettor</option>

                            </select>
                        </div>


                    </div>



                    {{-- <div class="col-md-0 mx-3 my-3">
                            <button class="btn btn-primary">Submit</button>
                            <a href="{{ route('games.index') }}"><button type="button"
                                    class="btn btn-danger">Cancel</button></a>

                        </div> --}}
                </div>
            </div>

            <div class="row mb-2">
                <div class=" heading col-sm-6">
                    <h1>Games</h1>
                </div>
                {{-- <button class="btn1 info filterbtn">Filter</button> --}}
                <div class="col-sm-2">
                    <span data-toggle="tooltip" title="Filter" class="filterbtn"><i id="icon"
                            class='fas fa-angle-down icon'></i></span>
                </div>
                <div class=" offset-sm-2  col-sm-2">
                    <h1 class="float-sm-right"><span id="total"
                            class="badge badge-pill total">{{ $games->total() }}</span></h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <style>
        .btn1 {
            border: 2px solid black;
            background-color: white;
            color: black;
            padding: 8px 20px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 25px;
        }

        .info {
            border-color: #1F6182;
            color: #1F6182;
        }

        .info:hover {
            background: #1F6182;
            color: white;
        }
    </style>
    <div class="container-fluid">
        <div class="row">

            <div class="col-12">
                <div class="card searcharea">
                    <div class="align-right">
                    </div>
                    <div class="card-header">
                        <br>
                        <div class="card-tools">
                            <div class="input-group input-group-sm">
                                <form style="display: flex;" action="{{ route('admins.games.index') }}">
                                    <div class="input-group border rounded-pill m-1 ">
                                        <input name="keyword" id="keyword" type="search" placeholder="Search"
                                            aria-describedby="button-addon3" class="form-control bg-none border-0">
                                        <div class="input-group-append border-0">
                                            <button type="button" id="button-addon3" type="button"
                                                class="btn btn-link text-blue"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>

                                {{-- <a href="{{ route('products.import') }}"><button type="button"
                                        class="btn btn-danger rounded-pill specialbutton m-1">Import products</button></a> --}}
                                <a href="{{ route('admins.games.create') }}"><button type="button"
                                        class="btn btn-primary rounded-pill rounded-bill m-1">Add Game</button></a>
                            </div>
                        </div>

                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>



                                    <th>game_id</th>
                                    <th>league</th>
                                    <th>sport</th>
                                    <th>home team</th>
                                    <th>away team</th>
                                    <th>start date</th>
                                    {{-- <th>Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                <meta name="csrf-token" content="{{ csrf_token() }}" />
                                @include('admin.game.ajaxtable')
                            </tbody>
                        </table>
                        <div id="wow" class="align-right paginationstyle">
                            {{ $games->links() }}
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->


                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.2.min.js"
        integrity="sha256-2krYZKh//PcchRtd+H+VyyQoZ/e3EcrkxhM8ycwASPA=" crossorigin="anonymous"></script>

    <script>
        var icon = document.getElementById("icon");
        var toggleiconnumb = 0;
        $('.filterbtn').on('click', function() {
            toggleicon();
            tooglefilter();
        })

        function toggleicon() {
            if (toggleiconnumb == 1) {
                icon.className = "fas fa-angle-down icon";
                toggleiconnumb = 0;
            } else {
                icon.className = "fas fa-angle-up icon";
                toggleiconnumb = 1;
            }
        }

        function tooglefilter() {
            $('.filters').toggle(300);
        }
    </script>


    <script>
        //   {{-- ajaxSearch --}}
        function filter() {
            var role_id = $('#role_id').children("option:selected").val();
            $('tbody').addClass('animate__animated animate__fadeOut');
            ajaxSearch(null, null, role_id);
        }

        $('#keyword').on('keyup', function() {
            $value = $(this).val();
            $('tbody').addClass('animate__animated animate__fadeOut');

            // console.log($value);

            // ager yeh lagaya tou role null chalajayega aur fresh serach houga

            ajaxSearch($value);

            // ager yeh lagaya tou role jo houga usmay serach houga

            // ajaxSearch($value,null,null);
        });

        function ajaxSearch(value, page, role_id) {
            $.ajax({
                type: "POST",
                url: "/admins/gamesajax" + "?page=" + page,
                dataType: 'JSON',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'keyword': value,
                    'is_handicapper': role_id
                },
                success: function(responese) {


                    // console.log(responese.pagination)
                    $('tbody').removeClass('animate__animated animate__fadeOut');
                    $('tbody').html(responese.data);
                    $('tbody').addClass('animate__animated animate__fadeIn');
                    $('#wow').html(responese.pagination);
                    $('#total').html(responese.total);
                },
            });
        }
        //   {{-- ajaxSearch --}}

        //   {{-- ajaxPagination --}}
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            $value = $('#keyword').val();
            $('tbody').addClass('animate__animated animate__fadeOut');
            var href = $(this).attr('href');
            var page = $(this).attr('href').split('page=')[1];
            var role_id = $('#role_id').children("option:selected").val();
            // console.log(href);
            ajaxSearch($value, page, role_id)
        });


        function statusToggle(id) {
            var val;

            if ($('#status' + id).prop("checked") == true) {
                // console.log("Checkbox is checked.");
                val = 1;
            } else if ($('#status' + id).prop("checked") == false) {
                // console.log("Checkbox is unchecked.");
                val = 0;
            }


            $.ajax({
                type: "POST",
                url: "game/locked",
                dataType: 'JSON',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    locked_id: id,
                    locked_val: val
                },
            });

        }
    </script>
@endsection
