@extends('admin.setup.master')

@section('title', 'Edit Invoice Details')

@section('content')
@php use App\Models\Shops;@endphp
    <section class="table-components">
        <div class="container-fluid">
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>Edit Invoice Details</h2>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="breadcrumb-wrapper">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ url('dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Edit Invoice Details
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-elements-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-style mb-30">
                            <form action="{{  url('store_invoice_details') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="col-md-12">
                                    <div class="input-style-1">
                                        <select name="shop_id" class="form-control">
                                            <option value="" selected disabled>Choose shop</option>
                                            @if (Auth::user()->role == 'superadmin')
                                                <option value="0">Admin</option>
                                            @endif
                                            @foreach ($data['shops'] as $shop)
                                                <option value="{{ $shop->id }}" {{ $shop->id == $data['shop']->id ? 'selected' : '' }}>{{ $shop->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-style-1">
                                            <label for="">Display Name</label>
                                            <input name="display_name" value="{{ $data['invoice_data']->name }}" placeholder="Enter Name" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-style-1">
                                            <label for="">Phone Number</label>
                                            <input name="tax_number" value="{{ $data['invoice_data']->tax_number }}" placeholder="Enter Taxation Number" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-style-1">
                                            <label for="">Commercial Registration Number</label>
                                            <input name="license_number" value="{{ $data['invoice_data']->license_number }}" class="form-control" placeholder="Enter License Number">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="input-style-1">
                                            <label for="">VAT Number</label>
                                            <input name="vat_number" value="{{ $data['invoice_data']->vat_number }}" class="form-control" placeholder="Enter VAT Number">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="input-style-1">
                                            <label for="">Address</label>
                                            <textarea name="address" placeholder="Enter Address" class="form-control" required>{{ $data['invoice_data']->address }}</textarea>
                                        </div>
                                    </div>

                                    <div class="input-style-1">
                                        <button type="submit" class="main-btn primary-btn rounded-full btn-hover">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

           
        </div>
    </section>
@endsection
