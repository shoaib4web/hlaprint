@extends('admin.setup.master')

@section('title', 'Financials')

@php

use App\Models\PrintJob;
use App\Models\Shops;

use App\Models\Transaction;


@endphp
@section('content')
    <!-- ========== table components start ========== -->

    <style>
        .table-container {
            overflow-x: auto;
            max-width: 100%;
        }
    </style>


    <section class="table-components">

        <div class="container-fluid">
            <!-- ========== title-wrapper start ========== -->

            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>Shop Financials</h2>
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
                                         Financials
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


            <!-- ========== tables-wrapper start ========== -->
            <div class="tables-wrapper">
                <div class="row">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-style mb-30">
                        <h6 class="mb-10">Filter</h6>
                        <form action="{{ route('financials') }}" method="GET">
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <label for="shop_id">Shop</label>
                                    <select name="shop_id" id="shop_id" class="form-control">
                                        <option value="all">All Shops</option>
                                        @foreach($data['shops'] as $shop)
                                            <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="end_date">End Date</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </div>
                        </form>

                        </div>
                    </div>

                </div>
                <div class="row">

                    <div class="col-lg-12">

                        <div class="card-style mb-30">

                            <h6 class="mb-10">Financials</h6>
                            {{-- <div class="table-wrapper table-responsive"> --}}
                               <div class="table-container">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Shop Name</th>
                                            <th>Revenue</th>
                                        </tr>
                                    </thead>
                                    <tbody id="shopTable">
                                        @if (count($data['shops']) > 0)
                                            @foreach ($data['shops'] as $key => $shop)

                                                <tr>
                                                    <td>{{ $key + 1 }}</td>

                                                    <td>{{ $shop->name }}</td>
                                                    <td>

                                                        Print Orders : {{$shop->online_revenue}}  SAR
                                                        <hr>
                                                        Cash Orders  : {{$shop->cash_revenue}}   SAR
                                                        <hr>
                                                        Total : {{$shop->online_revenue + $shop->cash_revenue}} SAR

                                                    </td>


                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td>No Job found!</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                               </div>
                                <!-- end table -->
                            {{-- </div> --}}
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

@endsection
