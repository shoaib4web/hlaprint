@extends('admin.setup.master')

@section('title', 'Create Invoice Details')

@section('content')
@php use App\Models\Shops;@endphp
    <!-- ========== table components start ========== -->
    <section class="table-components">

        <div class="container-fluid">
            <!-- ========== title-wrapper start ========== -->
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>Create Display Invoice Details</h2>
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
                                        Create Display Invoice Details
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
                           
                            <form action="{{ url('store_invoice_details') }}" method="POST">
                                @csrf
                               
                                <div class="col-md-12">
                                        <div class="input-style-1">
                                        <select name="shop_id" class="form-control">
                                                <option value="" selected disabled>Choose shop</option>
                                                @isset($data['shops']) 
                                                 @if (Auth::user()->role == 'superadmin')
                                                    <option value="0">Admin</option>
                                                @endif
                                                    @foreach ($data['shops'] as $shop)
                                                        
                                                        <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                                                    @endforeach
                                                @endisset
                                            </select>
                                        </div>
                                    </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-style-1">
                                            <label for="">Display Name</label>
                                            <input  name="display_name" placeholder="Enter Name" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-style-1">
                                            <label for="">Phone Number</label>
                                            <input name="tax_number" placeholder="Enter Taxation Number"  class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-style-1">
                                            <label for="">Commercial Registration Number</label>
                                            <input  name="license_number" class="form-control" placeholder="Enter License Number"  >
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="input-style-1">
                                            <label for="">VAT Number</label>
                                            <input  name="vat_number" class="form-control" placeholder="Enter License Number"  >
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="input-style-1">
                                            <label for="">Address</label>
                                            <textarea name="address" placeholder="Enter Address" class="form-control" required></textarea>
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
             <!-- ========== tables-wrapper start ========== -->
             <div class="tables-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-style mb-30">
                            <h6 class="mb-10">Details</h6>
                            <div class="table-wrapper table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Shop</th>
                                            <th>Display Name</th>
                                            <th>Phone</th>
                                            <th>CR Number</th>
                                            <th>Address</th>
                                            
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($data['invoice_data']) > 0)
                                            @foreach ($data['invoice_data'] as $key => $size)
                                               
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>
                                                        @php
                                                            $name = Shops::where('id',$size->shop_id)->first();
                                                        @endphp
                                                       @if($name) {{ $name->name }} @else Admin @endif
                                                    </td>
                                                    <td>{{ $size->name }}</td>
                                                    <td>{{ $size->tax_number}}</td>
                                                   
                                                    <td class="text-center">{{ $size->license_number }}</td>
                                                    <td>{{ $size->address }}</td>
                                                    <td>
                                                        <div>
                                                            <input type="hidden" value="{{$size->shop_id}}" name="shop_id">
                                                            <button type = "button"><a  href="{{url('/editInvoiceDetail/'.$size->shop_id)}}"class="btn btn-primary"><i class="fa fa-edit"></i>edit</a></button>
</div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td>No user found!</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                <!-- end table -->
                            </div>
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->

            </div>
        </div>
        <!-- end container -->
    </section>
    <!-- ========== table components end ========== -->

@endsection
