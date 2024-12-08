@extends('admin.setup.master')

@section('title', 'All Users')

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
                            <h2>All Users</h2>
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
                                        All Users
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
                        <form action="{{ url('users') }}" method="POST">
                            @csrf

                            <div class="row">

                                <div class="col-md-3">
                                    <div class="input-style-1">
                                        <label for="">Email</label>
                                        <input type="text" name="email" class="form-control"
                                            placeholder="Search by email...">
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
            {{-- search filter --}}

            <!-- ========== tables-wrapper start ========== -->
            <div class="tables-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-style mb-30">
                            <h6 class="mb-10">All Users</h6>
                            <div class="table-wrapper table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            @if (Auth::user()->role == 'superadmin')
                                                <th>Action</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($data['users']) > 0)
                                            @foreach ($data['users'] as $key => $user)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ $user->role }}</td>
                                                    <td>
                                                        @if (Auth::user()->role == 'superadmin')
                                                            <a href="{{ url('edit_user/' . $user->id) }}"
                                                                class="main-btn info-btn-outline btn-hover btn-sm"><span
                                                                    class="mdi mdi-pencil-box-outline"></span></a>
                                                                    <a href="{{ url('assignShop/' . $user->id) }}"
                                                                class="main-btn info-btn-outline btn-hover btn-sm"><span
                                                                    class="mdi mdi-pencil-box-outline"></span></a>
                                                                     <a href="{{ url('deleteUser/' . $user->id) }}"
                                                                class="main-btn danger-btn-outline btn-hover btn-sm"><span
                                                                    class="mdi mdi-delete"></span></a>

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
