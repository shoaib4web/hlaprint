@extends('admin.setup.master')

@section('title', 'Create Shop')

@section('content')
    <!-- ========== table components start ========== -->
    <section class="table-components">

        <div class="container-fluid">
            <!-- ========== title-wrapper start ========== -->
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>Create Shop</h2>
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
                                        Create Shop
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
                    @if($data['shops']  >= '5')
                    <div class="alert alert-danger" role="alert">
                     OOPS! Your Shop Limit Exceed
                    </div>
                    @endif
                        <!-- input style start -->
                        <div class="card-style mb-30">
                            <form action="{{ url('store_shop') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-style-1">
                                            <label for="">Name</label>
                                            <input type="text" name="name" class="form-control"
                                                placeholder="Enter Name" required />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-style-1">
                                            <label for="">Contact</label>
                                            <input type="number" name="contact" class="form-control"
                                                placeholder="Enter Number" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-style-1">
                                            <label for="">Address</label>
                                            <input type="text" name="address" class="form-control"
                                                placeholder="Enter Address" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="select-style-1">
                                            <label for="">Asign Shop</label>
                                            <div class="select-position">
                                                <select name="owner_id" class="form-control" required>
                                                    <option value="" selected disabled>Choose</option>
                                                    @isset($data['shop_owners'])
                                                        @foreach ($data['shop_owners'] as $shop)
                                                            <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                                                        @endforeach
                                                    @endisset
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="input-style-1">
                                        <button  @if ($data["shops"]  >= '5')  disabled @endif type="submit" class=" @if ($data["shops"]  >= '5') btn btn-danger rounded-full @else main-btn primary-btn   rounded-full btn-hover @endif ">@if ($data["shops"]  >= '5')  Create Shop @else Create Shop @endif</button>
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
