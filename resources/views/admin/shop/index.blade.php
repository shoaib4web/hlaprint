@extends('admin.setup.master')

@section('title', 'Shops')

@section('content')
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
                            <h2>Shops</h2>
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
                                        Shops
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
            @if (Auth::user()->role == 'superadmin' ) 
              {{-- search filter --}}
              <div class="row">
                <div class="col-lg-12">
                    <div class="card-style mb-30">
                        <form action="{{ url('shops') }}" method="POST">
                            @csrf

                            <div class="row">

                                <div class="col-md-3">
                                    <div class="input-style-1">
                                        <label for="">Shop Name</label>
                                        <input type="text" name="name" class="form-control"
                                            placeholder="Search by shop name...">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="input-style-1">
                                        <label for="">Contact</label>
                                        <input type="text" name="contact" class="form-control"
                                            placeholder="Search by contact...">
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
                                        <button type="submit"
                                            class="main-btn primary-btn rounded-full btn-hover" style="margin-top: 32px;">Search</button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            @endif
            {{-- search filter --}}

            <!-- ========== tables-wrapper start ========== -->
            <div class="tables-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-style mb-30">
                            <h6 class="mb-10">Shops</h6>
                            <div class="table-wrapper table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Shop Name</th>
                                            <th>Contact</th>
                                            <th>Address</th>
                                            <th>Created By</th>
                                            <th>Asign To</th>
                                            <th colspan="2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="shopTable">
                                        @if (count($data['shops']) > 0)
                                            @foreach ($data['shops'] as $key => $shop)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $shop->name }}</td>
                                                    <td>{{ $shop->contact }}</td>
                                                    <td>{{ $shop->address }}</td>
                                                    <td>{{ $shop->createdUser->name }}</td>
                                                    <td>{{ $shop->user->name }}</td>
                                                    <td>
                                                        @if (Auth::user()->role == 'superadmin')
                                                            <a href="{{ url('edit_shop/' . $shop->id) }}"
                                                                class="main-btn info-btn-outline btn-hover btn-sm"><span
                                                                    class="mdi mdi-pencil-box-outline"></span></a>
                                                            <button type="button" data="{{ $shop->id }}"
                                                                class="main-btn danger-btn-outline square-btn btn-hover btn-sm btn_delete_shop"><span class="mdi mdi-delete-empty-outline"></span></button>
                                                        @endif
                                                        <a href="{{ route('shop_options.edit', ['id' => getShopOptionIdByShopId($shop->id)]) }}" class="main-btn primary-btn-outline btn-hover btn-sm"><span class="mdi mdi-cog"></span></a>
                                                        <a href="{{ url('edit-shop-color-size/' . $shop->id) }}"
                                                            class="main-btn primary-btn-outline btn-hover btn-sm">
                                                            <span class="mdi mdi-format-color-fill"></span>
                                                        </a>

                                                        <a href="{{ url('print_jobs/' . $shop->id) }}"
                                                            class="main-btn primary-btn-outline btn-hover btn-sm">
                                                            <span class="mdi mdi-eye-circle-outline"></span>
                                                        </a>

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

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- CDN for Sweet Alert -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {

        // script for delete data
        $('#shopTable').on('click', '.btn_delete_shop', function(e) {
            e.preventDefault();

            var id = $(this).attr('data');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to Delete this Data!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "GET",
                        url: "{{ url('delete_shop') }}",
                        data: {
                            id: id,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: "json",
                        success: function(response) {

                            if (response.success) {
                                window.location.reload();
                            }
                        }
                    });
                }
            })

        });

    });
</script>
