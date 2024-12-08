
@extends('admin.setup.master')

@section('title', 'Print Jobs')

@section('content')
    <!-- ========== table components start ========== -->
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

            <!-- ========== tables-wrapper start ========== -->
            <div class="tables-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-style mb-30">
                            <h6 class="mb-10">Print Jobs</h6>
                            <div class="table-wrapper table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Shop Name</th>
                                            <th>Phone</th>
                                            <th>File</th>
                                            <th>Code</th>
                                            <th>Color</th>
                                            <th>Double Sided</th>
                                            <th>Page Start</th>
                                            <th>Page End</th>
                                            <th>Page Size</th>
                                            <th>Copy</th>
                                        </tr>
                                    </thead>
                                    <tbody id="shopTable">
                                        @if (count($data['print_jobs']) > 0)
                                            @foreach ($data['print_jobs'] as $key => $print)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $print->printshop->name }}</td>
                                                    <td>{{ $print->phone }}</td>
                                                    <td>{{ $print->filename }}</td>
                                                    <td>{{ $print->code }}</td>
                                                    <td>{{ $print->color }}</td>
                                                    <td>{{ $print->double_sided }}</td>
                                                    <td>{{ $print->pages_start }}</td>
                                                    <td>{{ $print->page_end }}</td>
                                                    <td>{{ $print->page_size }}</td>
                                                    <td>{{ $print->copies }}</td>
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





