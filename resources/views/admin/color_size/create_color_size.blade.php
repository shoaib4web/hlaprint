@extends('admin.setup.master')

@section('title', 'Create Color Size')

@section('content')
    <!-- ========== table components start ========== -->
    <section class="table-components">

        <div class="container-fluid">
            <!-- ========== title-wrapper start ========== -->
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>Create Color Size</h2>
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
                                        Create Color Size
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
                           
                            <form action="{{ url('store_color_size') }}" method="POST">
                                @csrf
                                @if(Auth::user()->role != 'superadmin')
                                <div class="col-md-12">
                                        <div class="input-style-1">
                                        <select name="shop_id" class="form-control">
                                                <option value="" selected disabled>Choose shop</option>
                                                @isset($data['shops'])
                                                    @foreach ($data['shops'] as $shop)
                                                        <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                                                    @endforeach
                                                @endisset
                                            </select>
                                        </div>
                                    </div>
                                    @else
                                    <input type="hidden" name="shop_id" value="{{isset($shop_id)?$shop_id:0}}">
                                    @endif
                            
                                <div class="row">
                                <label for="">Select Per Color Price</label>
</br></br></br>
                                <div class="col-md-6">
                                    <div class="input-style-1">
                                        <label for="">Color Copy Quantity</label>
                                        <input type="number" name="color_copies" class="form-control" step="0.01" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-style-1">
                                        <label for="">Color Copy Price</label>
                                        <input  name="color_copies_price" class="form-control"  required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-style-1">
                                        <label for="">BW Copy Quantity</label>
                                        <input type="number" name="bw_copies" class="form-control" step="0.01" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-style-1">
                                        <label for="">BW Copy Price</label>
                                        <input  name="bw_copies_price" class="form-control" required>
                                    </div>
                                </div>

   
                                </div>

                                <div class="row">
                                   
                                    
                                    <div class="col-md-6">
                                        <div class="input-style-1">
                                            <label for="">Color Page Amount</label>
                                            <input type="number" name="color_page_amount" class="form-control" step="0.01" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-style-1">
                                            <label for="">Black & White Amount</label>
                                            <input type="number" name="black_and_white_amount" class="form-control" step="0.01" required>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="input-style-1">
                                            <label for="">A1</label>
                                            <input type="number" name="a1" class="form-control" step="0.01" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-style-1">
                                            <label for="">A2</label>
                                            <input type="number" name="a2" class="form-control" step="0.01" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-style-1">
                                            <label for="">A3</label>
                                            <input type="number" name="a3" class="form-control" step="0.01" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-style-1">
                                            <label for="">A4</label>
                                            <input type="number" name="a4" class="form-control" step="0.01" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-style-1">
                                            <label for="">A5</label>
                                            <input type="number" name="a5" class="form-control" step="0.01" required>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="input-style-1">
                                            <label for="">A6</label>
                                            <input type="number" name="a6" class="form-control" step="0.01" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-style-1">
                                            <label for="">A7</label>
                                            <input type="number" name="a7" class="form-control" step="0.01" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-style-1">
                                            <label for="">A8</label>
                                            <input type="number" name="a8" class="form-control" step="0.01" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-style-1">
                                            <label for="">A9</label>
                                            <input type="number" name="a9" class="form-control" step="0.01" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-style-1">
                                            <label for="">A10</label>
                                            <input type="number" name="a10" class="form-control" step="0.01" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-style-1">
                                            <label for="">One Side</label>
                                            <input type="number" name="one_side" class="form-control" step="0.01" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-style-1">
                                            <label for="">Double Side</label>
                                            <input type="number" name="double_side" class="form-control" step="0.01" required>
                                        </div>
                                    </div>

                                    <div class="input-style-1">
                                        <button type="submit" class="main-btn primary-btn rounded-full btn-hover">Submit</button>
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
