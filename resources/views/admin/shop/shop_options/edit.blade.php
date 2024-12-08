@extends('admin.setup.master')

@section('title', 'Shop Options')

@section('content')
    <!-- ========== table components start ========== -->
    <section class="table-components">

        <div class="container-fluid">
            <!-- ========== title-wrapper start ========== -->
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>Shop Options</h2>
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
                                        Settings
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
                    @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
                  
                        <!-- input style start -->
                        <div class="card-style mb-30">
                            <form action="{{ route('shop_options.update', $shopOption->id) }}" method="POST">
            @csrf
            @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-style-1">
                                        <label for="print_invoice">Print Invoice</label>
                <select name="print_invoice" id="print_invoice" class="form-control">
                    <option value="1" {{ $shopOption->print_invoice ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ !$shopOption->print_invoice ? 'selected' : '' }}>No</option>
                </select>
                                    </div>

                                    <div class="col-md-6">
                                    label for="print_separator">Print Separator</label>
                <select name="print_separator" id="print_separator" class="form-control">
                    <option value="1" {{ $shopOption->print_separator ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ !$shopOption->print_separator ? 'selected' : '' }}>No</option>
                </select>
                                    </div>

                                    

                                    <div class="input-style-1">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
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
