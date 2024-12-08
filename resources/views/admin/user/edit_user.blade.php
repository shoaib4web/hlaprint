@extends('admin.setup.master')
@section('content')
    <!-- ========== table components start ========== -->
    <section class="table-components">

        <div class="container-fluid">
            <!-- ========== title-wrapper start ========== -->
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>Edit User</h2>
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
                                        Edit User
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

            <!-- ========== form-elements-wrapper start ========== -->
            <div class="form-elements-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- input style start -->
                        <div class="card-style mb-30">
                            <form action="{{ url('update_user') }}" method="POST">
                                @csrf
                                <input type="hidden" name="edit_user_id" value="{{ $user->id }}">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-style-1">
                                            <label for="">Name</label>
                                            <input type="text" name="name" value="{{ $user->name }}"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-style-1">
                                            <label for="">Email</label>
                                            <input type="text" name="email" value="{{ $user->email }}"
                                                class="form-control">
                                        </div>
                                    </div>

                                    @if ($user->role == 'superadmin')
                                        <div class="col-md-12">
                                            <div class="select-style-1">
                                                <label for="">Role</label>
                                                <div class="select-position">
                                                <select name="role" class="form-control">
                                                    <option value="" selected disabled>Choose role</option>
                                                    <option value="superadmin"
                                                        {{ $user->role == 'superadmin' ? 'selected' : '' }}>
                                                        Super Admin
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-md-12">
                                            <div class="select-style-1">
                                                <label for="">Role</label>
                                                <div class="select-position">
                                                    <select name="role" class="form-control">
                                                        <option value="" selected disabled>Choose role</option>
                                                        <option value="shopowner"
                                                            {{ $user->role == 'shopowner' ? 'selected' : '' }}>
                                                            shopOwner
                                                        </option>
                                                        <option value="shopmanager"
                                                            {{ $user->role == 'shopmanager' ? 'selected' : '' }}>
                                                            shopManager</option>
                                                            <option value="cashier"
                                                            {{ $user->role == 'cashier' ? 'selected' : '' }}>
                                                            Cashier</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="input-style-1">
                                        <button type="submit" class="main-btn primary-btn rounded-full btn-hover">Save Changes</button>
                                    </div>

                                </div>

                            </form>
                        </div>
                        <!-- end card -->
                        <!-- ======= input style end ======= -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- ========== form-elements-wrapper end ========== -->
        </div>
        <!-- end container -->
    </section>
    <!-- ========== table components end ========== -->
@endsection
