@extends('admin.setup.master')

@section('title', 'Invoice')

@section('content')
    <!-- ========== section start ========== -->
    <section>
        <div class="container-fluid">
            <!-- ========== title-wrapper start ========== -->
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title d-flex align-items-center flex-wrap">
                            <h2 class="mr-40">Invoice</h2>
                            <button class="main-btn primary-btn btn-hover btn-sm" id="printInvoice"> Print Invoice</button>
                        </div>
                    </div>
                    <!-- end col -->
                    <div class="col-md-6">
                        <div class="breadcrumb-wrapper">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="#0">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Invoice
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

            <!-- Invoice Wrapper Start -->
            <div class="invoice-wrapper">
                <div class="row" id="invoiceTable">
                    <div class="col-12">
                        <div class="invoice-card card-style mb-30">
                            <div class="invoice-header">
                                <div class="invoice-for">
                                    <h2 class="mb-10">Invoice</h2>
                                </div>
                                <div class="invoice-logo">
                                    <img src="assets/images/invoice/uideck-logo.svg" alt="" />
                                </div>
                                <div class="invoice-date">
                                    <p><span>Date :</span> {{ $data['generate_invoice']->date ?? '' }}</p>
                                    <p> # {{ $data['generate_invoice']->invoice_number ?? '' }}</p>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="invoice-table table">
                                    <thead>
                                        <tr>
                                            <th class="service">
                                                <h6 class="text-sm text-medium">Shop</h6>
                                            </th>
                                            <th>
                                                <h6 class="text-sm text-medium">Type</h6>
                                            </th>
                                            <th>
                                                <h6 class="text-sm text-medium">Page Side</h6>
                                            </th>
                                            <th class="qty">
                                                <h6 class="text-sm text-medium">Copy</h6>
                                            </th>
                                            <th class="amount">
                                                <h6 class="text-sm text-medium">Amounts</h6>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <p class="text-sm">
                                                    {{ $data['generate_invoice']->transaction->printjob->shops->name ?? 'Cash ' }}
                                                </p>
                                            </td>
                                            <td>
                                                <p class="text-sm">
                                                    {{ $data['generate_invoice']->transaction->printjob->type ?? '' }}
                                                </p>
                                            </td>
                                            
                                            <td>
                                                <p class="text-sm">
                                                    @if (($data['generate_invoice']->transaction->printjob->double_sided == '' || $data['generate_invoice']->transaction->printjob->double_sided == '0'))
                                                        <p class="text-sm">Single Side</p>
                                                    @elseif (($data['generate_invoice']->transaction->printjob->double_sided == 'long-edge' || $data['generate_invoice']->transaction->printjob->double_sided == '1'))
                                                        <p class="text-sm">Double Side</p>
                                                    @else
                                                        <p></p>
                                                    @endif
                                                </p>
                                            </td>
                                            <td>
                                                <p class="text-sm">
                                                    {{ $data['generate_invoice']->transaction->printjob->copies ?? '' }}
                                                </p>
                                            </td>
                                            <td>
                                                <p class="text-sm">{{ $data['generate_invoice']->amount ?? '' }}<small> SAR</small> </p>
                                            </td>
                                        </tr>


                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td>
                                            </td>
                                            <td>
                                                <h4>Total</h4>
                                            </td>
                                            <td>
                                                <h4>{{ $data['generate_invoice']->amount ?? '' }} SAR </h4>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <!-- End Card -->
                    </div>
                    <!-- ENd Col -->
                </div>
                <!-- End Row -->
            </div>
            <!-- Invoice Wrapper End -->
        </div>
        <!-- end container -->
    </section>
    <!-- ========== section end ========== -->

@endsection

@section('script')
    <script>
        $(document).ready(function() {

            $('#printInvoice').on('click', function() {
                $.print("#invoiceTable");
            });

        });
    </script>
@endsection
