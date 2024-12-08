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
                            <h2>Create Color Size </h2>
                        </div>
                    </div>
                    @if(isset($test))
    <div class="alert alert-danger">
        {{ $test }}
    </div>
@endif
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
                           
                            <form action="{{ url('store-pricing') }}" method="POST">
                                @csrf
                                
                                <input type="hidden" name="shop_id" value="{{Auth::user()->shop_id??0}}">

                                <div class="row">
                                   
                                    
                                    <div class="col-md-4">
                                        <div class="input-style-1">
                                            <label for="page-size">Page Size</label>
                                            <select name='page_size' class="form-control">
                                                <option value="A1">A1</option>
                                                <option value="A2">A2</option>
                                                <option value="A3">A3</option>
                                                <option value="A4">A4</option>
                                                <option value="A5">A5</option>
                                                <option value="A6">A6</option>
                                                <option value="A7">A7</option>
                                                <option value="A8">A8</option>
                                                <option value="A9">A9</option>
                                                <option value="A10">A10</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="input-style-1">
                                            <label for="">Colors</label>
                                            <select name='color_type' class="form-control">
                                                <option value="color">Color</option>
                                                <option value="mono">Black / White </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="input-style-1">
                                            <label for="">Sides</label>
                                           <Select name="sidedness" class="form-control">
                                               <option value="oneSide">One Sided</option>
                                               <option value="doubleSided">Double Sided</option>
                                           </Select>
                                        </div>
                                    </div>
                                    
                          
                            <div class="row">
                                    <div class="col-md-3 offset-md-3">
                                        <div class="input-style-1">
                                            <label for="">No of Pages</label>
                                            <input type="number" name="no_of_pages" class="form-control" step="0.01" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-style-1">
                                            <label for="">Price (SR) </label>
                                            <input type="number" name="base_price" class="form-control" step="0.01" required>
                                        </div>
                                    </div>
                                   

                                    <div class="input-style-1">
                                        <button type="submit" class="main-btn primary-btn rounded-full btn-hover">Submit</button>
                                    </div>

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
