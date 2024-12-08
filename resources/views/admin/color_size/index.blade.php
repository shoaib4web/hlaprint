@extends('admin.setup.master')

@section('title', 'Color Size')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
        .long-data {
            max-width: 200px;
            /* Set the maximum width for the table cell */
            white-space: nowrap;
            /* Prevent text from wrapping */
            overflow: hidden;
            /* Hide the overflow */
            text-overflow: ellipsis;
            /* Display an ellipsis (...) for truncated text */
            cursor: pointer;
            /* Set cursor to indicate it's clickable */
        }

        .long-data.expanded {
            white-space: normal;
            /* Allow text to wrap */
            overflow: visible;
            /* Show full content */
            text-overflow: inherit;
            /* Reset text-overflow */
        }
    </style>

    <!-- ========== table components start ========== -->
    <section class="table-components">

        <div class="container-fluid">
            <!-- ========== title-wrapper start ========== -->

            <div class="title-wrapper pt-30">
                @if (Session::has('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert">
                            <i class="fa fa-times"></i>
                        </button>
                        {{ session('success') }}
                    </div>
                @elseif (Session::has('error'))
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert">
                            <i class="fa fa-times"></i>
                        </button>
                        {{ session('error') }}
                    </div>
                @endif
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>Color Size</h2>
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
                                        Color Size
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
                            <h6 class="mb-10">Color Size</h6>
                            <div class="table-wrapper table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Color Page Amount</th>
                                            <th>Black & White Amount</th>
                                            <th>Size Amount</th>
                                            <th>One Sided</th>
                                            <th>Double Sided</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($data['color_size']) > 0)
                                            @foreach ($data['color_size'] as $key => $size)
                                                @php
                                                    $metaData = unserialize($size->size_amount);
                                                @endphp

                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $size->color_page_amount }}</td>
                                                    <td>{{ $size->black_and_white_amount }}</td>
                                                    <td class="long-data">
                                                        @foreach ($metaData as $key => $value)
                                                            <strong>{{ $key }}:</strong> {{ $value }} &nbsp;
                                                        @endforeach
                                                    </td>
                                                    <td class="text-center">{{ $size->one_side }}</td>
                                                    <td>{{ $size->double_side }}</td>
                                                    <td><a href="{{route('editShopColor',[$size->shop_id])}}" class="btn btn-primary"><i class="fa fa-edit"></i></a></td>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $(".long-data").on("click", function() {
            if ($(this).hasClass("expanded")) {
                $(this).removeClass("expanded");
            } else {
                $(this).addClass("expanded");
            }
        });
    });
</script>
