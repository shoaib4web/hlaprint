@extends('admin.setup.master')

@section('title', 'Create Print Job')

@section('content')
    <!-- ========== table components start ========== -->
    <section class="table-components">

        <div class="container-fluid">
            <!-- ========== title-wrapper start ========== -->
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>Create Print Job</h2>
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
                                        Create Print Job
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
                    @if (!empty($success))
                        <h1>{{ session()->get('success') }}</h1>
                    @endif
                    @if (!empty($error))
                        <h1>{{ session()->get('error') }}</h1>
                    @endif
                        <!-- input style start -->
                        <div class="card-style mb-30">
                            <form action="{{ url('store_print_job') }}" method="POST">
                                @csrf

                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="select-style-1">
                                            <label for="">Shop</label>
                                            <div class="select-position">
                                                <select name="shop_id" class="form-control" required>
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

                                    <div class="col-md-6">
                                        <div class="select-style-1">
                                            <label for="">Number of Copies</label>
                                            <div class="select-position">
                                                <select name="copies" class="form-control" required>
                                                    <option value="" selected disabled>Choose copies</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                    <option value="9">9</option>
                                                    <option value="10">10</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="select-style-1">
                                            <label for="">Color</label>
                                            <div class="select-position">
                                                <select name="color" class="form-control" required>
                                                    <option value="" selected disabled>Choose color</option>
                                                    <option value="true">Colored</option>
                                                    <option value="false">Black & White</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="select-style-1">
                                            <label for="">Page Sides</label>
                                            <div class="select-position">
                                                <select name="page_sides" class="form-control" required>
                                                    <option value="" selected disabled>Choose color</option>
                                                    <option value="0">Single Side</option>
                                                    <option value="1">Double Side</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="input-style-1">
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
