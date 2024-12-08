@extends('admin.setup.master')

@section('title', 'Dashboard')

@section('content')

    <!-- ========== section start ========== -->
    <section class="section">
        <div class="container-fluid">
            <!-- ========== title-wrapper start ========== -->
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>Welcome Dashboard</h2>
                        </div>
                    </div>
                    <!-- end col -->
                    <div class="col-md-6">
                        <div class="breadcrumb-wrapper">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="#0">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Dashboard
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



            <div class="row">
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="icon-card mb-30">
                        <div class="icon purple">
                            <i class="lni lni-cart-full"></i>
                        </div>
                        <div class="content">
                            <h6 class="mb-10">New Orders</h6>
                            <h3 class="text-bold mb-10"> {{$data['orders']}}</h3>
                            <p class="text-sm text-success">
                                <!-- <i class="lni lni-arrow-up"></i> +2.00%
                                <span class="text-gray">(30 days)</span> -->
                            </p>
                        </div>
                    </div>
                    <!-- End Icon Cart -->
                </div>
                <!-- End Col -->
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="icon-card mb-30">
                        <div class="icon success">
                            <i class="lni lni-dollar"></i>
                        </div>
                        <div class="content">
                            <h6 class="mb-10">Total Online</h6>
                            <h3 class="text-bold mb-10"> {{$data['revenu']}} SAR</h3>
                            <p class="text-sm text-success">
                                <!-- <i class="lni lni-arrow-up"></i> +5.45%
                                <span class="text-gray">Increased</span> -->
                            </p>
                        </div>
                    </div>
                    <!-- End Icon Cart -->
                </div>
                <!-- End Col -->
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="icon-card mb-30">
                        <div class="icon primary">
                            <i class="lni lni-credit-cards"></i>
                        </div>
                        <div class="content">
                            <h6 class="mb-10">Total Cash</h6>
                            <h3 class="text-bold mb-10">{{ $data['grossIncome']}} SAR</h3>
                            <p class="text-sm text-danger">
                                <!-- <i class="lni lni-arrow-down"></i> -2.00%
                                <span class="text-gray">Expense</span> -->
                            </p>
                        </div>
                    </div>
                    <!-- End Icon Cart -->
                </div>
                <!-- End Col -->
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="icon-card mb-30">
                        <div class="icon orange">
                            <i class="lni lni-user"></i>
                        </div>
                        <div class="content">
                            <h6 class="mb-10">New User</h6>
                            <h3 class="text-bold mb-10">{{$data['users']}}</h3>
                            <p class="text-sm text-danger">
                                <!-- <i class="lni lni-arrow-down"></i> -25.00%
                                <span class="text-gray"> Earning</span> -->
                            </p>
                        </div>
                    </div>
                    <!-- End Icon Cart -->
                </div>
                <!-- End Col -->
            </div>
            <!-- End Row -->

        </div>
        <!-- end container -->
    </section>
    <!-- ========== section end ========== -->

@endsection