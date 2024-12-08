@extends('admin.setup.master')

@section('title', 'Create Color Size')

@section('content')
    <!-- ========== table components start ========== -->
    <section class="table-components">
    @if(!isset($color_size))
    
       {{ redirect('dashboard');}}
    @endif
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
                                        Edit Color Size
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
                                @if (Auth::user()->role == 'superadmin') 
                                    <input type="hidden" name="shop_id" value="{{isset($shop_id)?$shop_id:0}}">
                                @else
                                    <input type="hidden" name="shop_id" value="{{isset($color_size)?$color_size->shop_id:0}}">
                                 @endif
                                 <div class="row">
                                <label for="">Select Per Color Price</label>
                                </br></br></br>
                                <div class="col-md-6">
                                    <div class="input-style-1">
                                        <label for="">Color Copy Quantity</label>
                                        <input type="number" name="color_copies" value="{{$color_size->color_copies}}" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-style-1">
                                        <label for="">Color Copy Price</label>
                                        <input  name="color_copies_price" class="form-control" value="{{$color_size->color_copies_price}}"  required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-style-1">
                                        <label for="">BW Copy Quantity</label>
                                        <input type="number" name="bw_copies"  value="{{$color_size->bw_copies}}" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-style-1">
                                        <label for="">BW Copy Price</label>
                                        <input  name="bw_copies_price"  value="{{$color_size->bw_copies_price}}" class="form-control" required>
                                    </div>
                                </div>

   
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-style-1">
                                            <label for="">Color Page Amount</label>
                                            <input type="number" name="color_page_amount" class="form-control" required value="{{$color_size->color_page_amount}}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-style-1">
                                            <label for="">Black & White Amount</label>
                                            <input type="number" name="black_and_white_amount" class="form-control" required value="{{$color_size->black_and_white_amount}}">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="input-style-1">
                                            <label for="">A1</label>
                                            <input type="number" name="a1" class="form-control" required value="{{unserialize($color_size->size_amount)['A1']}}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-style-1">
                                            <label for="">A2</label>
                                            <input type="number" name="a2" class="form-control" required value="{{unserialize($color_size->size_amount)['A2']}}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-style-1">
                                            <label for="">A3</label>
                                            <input type="number" name="a3" class="form-control" required value="{{unserialize($color_size->size_amount)['A3']}}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-style-1">
                                            <label for="">A4</label>
                                            <input type="number" name="a4" class="form-control" required value="{{unserialize($color_size->size_amount)['A4']}}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-style-1">
                                            <label for="">A5</label>
                                            <input type="number" name="a5" class="form-control" required value="{{unserialize($color_size->size_amount)['A5']}}">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="input-style-1">
                                            <label for="">A6</label>
                                            <input type="number" name="a6" class="form-control" required value="{{unserialize($color_size->size_amount)['A6']}}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-style-1">
                                            <label for="">A7</label>
                                            <input type="number" name="a7" class="form-control" required value="{{unserialize($color_size->size_amount)['A7']}}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-style-1">
                                            <label for="">A8</label>
                                            <input type="number" name="a8" class="form-control" required value="{{unserialize($color_size->size_amount)['A8']}}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-style-1">
                                            <label for="">A9</label>
                                            <input type="number" name="a9" class="form-control" required value="{{unserialize($color_size->size_amount)['A9']}}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-style-1">
                                            <label for="">A10</label>
                                            <input type="number" name="a10" class="form-control" required value="{{unserialize($color_size->size_amount)['A10']}}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-style-1">
                                            <label for="">One Side</label>
                                            <input type="number" name="one_side" class="form-control" required value="{{$color_size->double_side}}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-style-1">
                                            <label for="">Double Side</label>
                                            <input type="number" name="double_side" class="form-control" required value="{{$color_size->one_side}}">
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
