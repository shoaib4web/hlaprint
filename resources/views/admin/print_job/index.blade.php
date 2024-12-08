@extends('admin.setup.master')

@section('title', 'Print Jobs')

@section('content')
    <!-- ========== table components start ========== -->

    <style>
        .table-container {
            overflow-x: auto;
            max-width: 100%;
        }
    </style>


    <section class="table-components">

        <div class="container-fluid">
            <!-- ========== title-wrapper start ========== -->

            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>Print Jobs</h2>
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
                                        Print Jobs
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

                        <form action="{{ url('print-jobs') }}" method="POST">
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
                    
                    </div>
                        <div class="card-style mb-30">
                        @if (Session::has('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert">
                                <i class="fa fa-times"></i>
                            </button>
                            {{ session('success') }}
                        @endif
                            <h6 class="mb-10">Print Jobs</h6>
                            {{-- <div class="table-wrapper table-responsive"> --}}
                               <div class="table-container">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Shop Name</th>
                                            <th>Phone</th>
                                            <th>Code</th>
                                            <th>Color</th>
                                            <th>Double Sided</th>
                                            <th>Page Start</th>
                                            <th>Page End</th>
                                            <th>Page Size</th>
                                            <th>Copy</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="shopTable">
                                        @if (count($data['print_jobs']) > 0)
                                            @foreach ($data['print_jobs'] as $key => $print)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $print->printshop?$print->printshop->name:'Main' }}</td>
                                                    <td>{{ $print->phone }}</td>
                                                    <td>{{ $print->code }}</td>
                                                    <td>{{ $print->color }}</td>
                                                    <td>{{ $print->double_sided }}</td>
                                                    <td>{{ $print->pages_start }}</td>
                                                    <td>{{ $print->page_end }}</td>
                                                    <td>{{ $print->page_size }}</td>
                                                    <td>{{ $print->copies }}</td>
                                                    <td>{{ $print->status}}</td>
                                                    <td>
                                                        
                                                        <a href="{{ url('rePrint/' . $print->id) }}"
                                                        class="main-btn active-btn square-btn btn-hover btn-sm">Reprint</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td>No Job found!</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                               <p> {{ $data['print_jobs']->links() }}</p>
                               </div>
                                <!-- end table -->
                            {{-- </div> --}}
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
