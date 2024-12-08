@extends('admin.setup.master')

@section('title', 'Transactionss')

@section('content')


<style>
    .Pagination.Navigation div span,
    .Pagination.Navigation div a{
        background-color: #365CF5 !important;
    color: white;
    }
</style>
    <!-- ========== table components start ========== -->
    <section class="table-components">
        
        <div class="container-fluid">
            <!-- ========== title-wrapper start ========== -->

            <div class="title-wrapper pt-30">
            @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>Price options</h2>
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
                                        Pricing 
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
            {{-- search filter --}}
            <!--<div class="row">-->
            <!--    <div class="col-lg-12">-->
            <!--        <div class="card-style mb-30">-->
            <!--            <form action="{{ url('transaction') }}" method="POST">-->
            <!--                @csrf-->

            <!--                <div class="row">-->
                                <!-- <div class="col-md-3">-->
                                <!--    <div class="select-style-1">-->
                                <!--        <label for="">Shop</label>-->
                                <!--        <div class="select-position">-->
                                <!--            <select name="shop_id" class="form-control">-->
                                <!--                <option value="" selected disabled>Choose shop</option>-->
                                <!--                @isset($data['shops'])-->
                                <!--                    @foreach ($data['shops'] as $shop)-->
                                <!--                        <option value="{{ $shop->id }}">{{ $shop->name }}</option>-->
                                <!--                    @endforeach-->
                                <!--                @endisset-->
                                <!--            </select>-->
                                <!--        </div>-->
                                <!--    </div>-->
                                <!--</div> -->

            <!--                    <div class="col-md-3">-->
            <!--                        <div class="select-style-1">-->
            <!--                            <label for="">Type</label>-->
            <!--                            <div class="select-position">-->
            <!--                                <select name="type" class="form-control">-->
            <!--                                <option value="cash">Cash</option>-->
            <!--                                <option value="online">Online</option>-->
            <!--                                <option value="both">Both</option>-->
            <!--                                </select>-->
            <!--                            </div>-->
                                       
            <!--                        </div>-->
            <!--                    </div>-->

            <!--                    <div class="col-md-3">-->
            <!--                        <div class="input-style-1">-->
            <!--                            <label for="">Date</label>-->
            <!--                            <input type="date" name="date" class="form-control">-->
            <!--                        </div>-->
            <!--                    </div>-->

            <!--                    <div class="col-md-3">-->
            <!--                        <div class="input-style-1">-->
            <!--                            <button type="submit" class="main-btn primary-btn rounded-full btn-hover"-->
            <!--                                style="margin-top: 32px;">Search</button>-->
            <!--                        </div>-->
            <!--                    </div>-->
            <!--                </div>-->

            <!--            </form>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->
            {{-- search filter --}}
           
            <!-- ========== tables-wrapper start ========== -->
            <div class="tables-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-style mb-30">
                            <h6 class="mb-10">Pricing</h6>
                            <a class="main-btn square-btn btn-hover btn-sm" href="{{url('create-price-options')}}">Create New</a>
                            <div class="table-wrapper table-responsive">
                                 @if (count($data['priceOptions']) > 0)
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Page</th>
                                            <th>Color</th>
                                            <th>Duplex</th>
                                            <th>Num of Pages</th>
                                            <th>Price (SAR)</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                            @foreach ($data['priceOptions'] as $key => $priceOption)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    
                                                    <td>{{ $priceOption->page_size }}</td>
                                                    
                                                    <td>{{ $priceOption->color_type }}</td>
                                                    <td>{{ $priceOption->sidedness }}</td>
                                                    <td>{{ $priceOption->no_of_pages }}</td>
                                                    <td>{{$priceOption->base_price }}</td>
                                                  
                                                    <td>
                                                        <a href="/edit-price/{{$priceOption->id}}"
                                                                class="main-btn disabled-btn square-btn btn-hover btn-sm disabled">Edit</a>
                                                    </td>
                                                   
                                                </tr>
                                            @endforeach
                                        
                                    </tbody>
                                </table>
                                @else
                                            <h2>No Pricing Defined, Please Create New!</h2>
                                        @endif
                                <!-- end table -->
                            </div>
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->

            </div>
            <!-- ========== tables-wrapper end ========== -->
        </div>
        <!-- end container -->
    </section>
    <!-- ========== table components end ========== -->
    
    <script>
        setTimeout(function(){
   window.location.reload();
}, 10000);
    </script>

@endsection
