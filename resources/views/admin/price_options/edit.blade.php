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
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
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
                           
                            <form action="{{ url('update-price') }}" method="POST">
                                @csrf
                                
                                <input type="hidden" name="shop_id" value="{{Auth::user()->shop_id ?? 0}}">
                                <input type="hidden" name="id" value="{{$priceOptions->id}}">

                                <div class="row">
                                   
                                    
                                    <div class="col-md-4">
                                        <div class="input-style-1">
                                            <label for="page-size">Page Size</label>
                                            <select name='page_size' class="form-control">
                                                @foreach(['A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'A9', 'A10'] as $pageSize)
                                                    <option value="{{ $pageSize }}" {{ (old('page_size', $priceOptions->page_size) == $pageSize) ? 'selected' : '' }}>{{ $pageSize }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="input-style-1">
                                            <label for="">Colors</label>
                                            <select name='color_type' class="form-control">
                                                <option value="color" {{ (old('color_type', $priceOptions->color_type) == 'color') ? 'selected' : '' }}>Color</option>
                                                <option value="mono" {{ (old('color_type', $priceOptions->color_type) == 'mono') ? 'selected' : '' }}>Black / White</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="input-style-1">
                                            <label for="">Sides</label>
                                            <Select name="sidedness" class="form-control">
                                                <option value="oneSide" {{ (old('sidedness', $priceOptions->sidedness) == 'oneSide') ? 'selected' : '' }}>One Sided</option>
                                                <option value="doubleSided" {{ (old('sidedness', $priceOptions->sidedness) == 'doubleSided') ? 'selected' : '' }}>Double Sided</option>
                                            </Select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 offset-md-3">
                                        <div class="input-style-1">
                                            <label for="">No of Pages</label>
                                            
                                            <input type="number" name="no_of_pages" class="form-control" value="{{ old('no_of_pages', $priceOptions->no_of_pages) }}" step="0.01" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-style-1">
                                            <label for="">Price (SR) </label>
                                            <input type="number" name="base_price" class="form-control" value="{{ old('base_price', $priceOptions->base_price) }}" step="0.01" required>
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
