@extends('admin.setup.master')

@section('title', 'Cash Order')

@section('content')
    <!-- ========== table components start ========== -->
    <section class="table-components">

        <div class="container-fluid">
            <!-- ========== title-wrapper start ========== -->

            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>Cash Order</h2>
                        </div>
                    </div>
                    <!-- end col -->
                    <div class="col-md-6">
                        <div class="breadcrumb-wrapper">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ url('dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Cash Order
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- ========== title-wrapper end ========== -->


            {{-- search filter --}}
            <div class="row">
                <div class="col-lg-12">
                   
                    <div class="card-style mb-30">
                        <form action="{{ url('cash-order') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="select-style-1">
                                        <label for="">Shop</label>
                                        <div class="select-position">
                                            <select name="shop_id" class="form-control">
                                                <option value="" selected disabled>Choose shop</option>
                                                @isset($data['shops'])
                                                    @foreach ($data['shops'] as $shop)
                                                        <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                                                    @endforeach
                                                @endisset
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="input-style-1">
                                        <label for="">Phone</label>
                                        <input type="text" name="phone" class="form-control"
                                            placeholder="Search by phone...">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="input-style-1">
                                        <label for="">Date</label>
                                        <input type="date" name="date" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="input-style-1">
                                        <button type="submit" class="main-btn primary-btn rounded-full btn-hover"
                                            style="margin-top: 32px;">Search</button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            {{-- search filter --}}

            <!-- ========== tables-wrapper start ========== -->
            <div class="tables-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-style mb-30">
                            <h6 class="mb-10">Cash Orders</h6>
                            <div class="table-wrapper table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Shop Name</th>
                                            <th>Phone</th>
                                            <th>Code</th>
                                            <th>Color</th>
                                            <th>Double Sided</th>
                                            {{-- <th>Page Start</th>
                                            <th>Page End</th> --}}
                                            <th>Page Size</th>
                                            <th>Copy</th>
                                            <th>Type</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="shopTable">
                                        @if (count($data['print_jobs']) > 0)
                                            @foreach ($data['print_jobs'] as $key => $print)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>@if($print->printshop)
                                                            {{ $print->printshop->name }}
                                                        @else
                                                            Cash
                                                        @endif
                                                    </td>
                                                    <td>{{ $print->phone }}</td>
                                                    <td>{{ $print->code }}</td>
                                                    <td>{{ $print->color }}</td>
                                                    <td>
                                                    @if (($print->double_sided  == '' || $print->double_sided  == '0'))
                                                        Single Side
                                                    @elseif (($print->double_sided  == 'long-edge' || $print->double_sided  == '1'))
                                                        Double Side
                                                    @endif
                                                       
                                                    </td>
                                                    {{-- <td>{{ $print->pages_start }}</td>
                                                    <td>{{ $print->page_end }}</td> --}}
                                                    <td>
                                                    {{ $print->page_size ?? 'A4 ' }}
                                                        
                                                    </td>
                                                    <td>{{ $print->copies }}</td>
                                                    <td>
                                                        @if ($print->type == 'cash order')
                                                            Cash
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($print->status == 'Queued')
                                                        
                                                            <a type="button" href="{{route('cashApprove',[ $print->code ])}}"
                                                                class="main-btn active-btn square-btn btn-hover btn-sm"
                                                                data="{{ $print->code }}" >Approve </a>
                                                        @else
                                                                <p>Approved</p>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td>No user found!</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                <!-- end table -->
                            </div>
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->

            </div>
            <!-- ========== tables-wrapper end ========== -->
        </div>
        <!-- end container -->
    </section>
    <!-- ========== table components end ========== -->

@endsection

@section('script')

    <script>
        //status update
        $('#shopTable').on('click', '#btn_cash_status', function() {

            var id = $(this).attr('data');

            $.ajax({
                url: '{{ url('/update_status') }}',
                type: 'get',
                async: false,
                dataType: 'json',
                data: {
                    id: id
                },
                success: function(response) {

                    window.location.reload();

                },
                error: function() {
                    alert('somthing went wrong');
                }

            });

        });
    </script>

@endsection
