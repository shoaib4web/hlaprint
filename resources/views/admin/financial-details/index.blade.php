@extends('admin.setup.master')

@section('title', isset($financialDetails) ? 'Edit Financial Details' : 'Create Financial Details')

@section('content')
    <!-- ========== table components start ========== -->
    <section class="table-components">

        <div class="container-fluid">
            <!-- ========== title-wrapper start ========== -->
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>{{ isset($financialDetails) ? 'Edit Financial Details' : 'Create Financial Details' }}</h2>
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
                                        {{ isset($financialDetails) ? 'Edit Financial Details' : 'Create Financial Details' }}
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
                    @if(isset($success))
                    <div class="alert alert-success">{{ $success }}</div>
                    @endif
                    <div class="col-lg-12">
                        <!-- input style start -->
                        <div class="card-style mb-30">
                            <form action="{{ isset($financialDetails) ? url('update_financial_details', $financialDetails->id) : url('store_financial_details') }}" method="POST">
                                @csrf
                                @if (isset($financialDetails))
                                    @method('PUT')
                                @endif
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-style-1">
                                            <label for="">Customer Name</label>
                                            <input type="text" name="name" class="form-control"
                                                placeholder="Enter Customer Name" value="{{ old('name', $financialDetails->name ?? '') }}" required />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-style-1">
                                            <label for="">Account Number</label>
                                            <input type="text" name="account_number" class="form-control"
                                                placeholder="Enter Account Number" value="{{ old('account_number', $financialDetails->account_number ?? '') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-style-1">
                                            <label for="">Country</label>
                                            <input type="text" name="country" class="form-control"
                                                placeholder="Enter Country" value="{{ old('country', $financialDetails->country ?? '') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-style-1">
                                            <label for="">Bank Name</label>
                                            <input type="text" name="bank" class="form-control"
                                                placeholder="Enter Bank Name" value="{{ old('bank', $financialDetails->bank ?? '') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-style-1">
                                            <label for="">Bank Branch</label>
                                            <input type="text" name="bank_branch" class="form-control"
                                                placeholder="Enter Bank Branch" value="{{ old('bank_branch', $financialDetails->bank_branch ?? '') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-style-1">
                                            <label for="">Email</label>
                                            <input type="email" name="email" class="form-control"
                                                placeholder="Enter Email" value="{{ old('email', $financialDetails->email ?? '') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-style-1">
                                            <label for="">Mobile Number</label>
                                            <input type="text" name="mobile_number" class="form-control"
                                                placeholder="Enter Mobile Number" value="{{ old('mobile_number', $financialDetails->mobile_number ?? '') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-style-1">
                                            <label for="">Address 1</label>
                                            <input type="text" name="address_1" class="form-control"
                                                placeholder="Enter Address 1" value="{{ old('address_1', $financialDetails->address_1 ?? '') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-style-1">
                                            <label for="">Address 2</label>
                                            <input type="text" name="address_2" class="form-control"
                                                placeholder="Enter Address 2" value="{{ old('address_2', $financialDetails->address_2 ?? '') }}">
                                        </div>
                                    </div>

                                    <div class="input-style-1">
                                        <button type="submit" class="main-btn primary-btn rounded-full btn-hover">
                                            {{ isset($financialDetails) ? 'Update Financial Details' : 'Create Financial Details' }}
                                        </button>
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
