@extends('admin.setup.master')

@section('title', 'Create Users')

@section('content')
    <!-- ========== table components start ========== -->
    <section class="table-components">

        <div class="container-fluid">
            <!-- ========== title-wrapper start ========== -->
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>Create Users</h2>
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
                                        Create Users
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
                            <form action="{{ url('store_user') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-style-1">
                                            <label for="">Name</label>
                                            <input type="text" name="name" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-style-1">
                                            <label for="">Email</label>
                                            <input type="text" name="email" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-style-1">
                                            <label for="">Password</label>
                                            <input type="password" id="password" name="password" class="form-control" required>
                                        </div>
                                        <label for="show-password">
                                            <input type="checkbox" id="show-password" class="mb-5"> Show Password
                                        </label>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="select-style-1">
                                            <label for="">Role</label>
                                            <div class="select-position">
                                                <select name="role" class="form-control" required>
                                                    <option value="" selected disabled>Choose role</option>
                                                    @if(Auth::user()->role == 'superadmin')<option value="shopowner">shopOwner</option>@endif
                                                    <option value="shopmanager">shopManager</option>
                                                    <option value="cashier">Cashier</option>
                                                    
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="input-style-1">
                                        <input type="hidden" name="shop_id" value="{{Auth::user()->shop_id}}">
                                        <button type="submit"
                                            class="main-btn primary-btn rounded-full btn-hover">Submit</button>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $("#show-password").on("change", function() {
            var passwordField = $("#password");
            var isChecked = $(this).is(":checked");

            if (isChecked) {
                passwordField.attr("type", "text");
            } else {
                passwordField.attr("type", "password");
            }
        });
    });
</script>
