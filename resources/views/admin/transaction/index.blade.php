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
                @if (\Session::has('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert">
                            <i class="fa fa-times"></i>
                        </button>
                        {{!! \Session::get('success') !!}}
                    </div>
                @elseif (\Session::has('error'))
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert">
                            <i class="fa fa-times"></i>
                        </button>
                        {{!! \Session::get('error') !!}}
                    </div>
                @endif
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>Transactions</h2>
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
                                        Transactions
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
            <div class="row">
                <div class="col-lg-12">
                    <div class="card-style mb-30">
                        <form action="{{ url('transaction') }}" method="POST">
                            @csrf

                            <div class="row">
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

                                <div class="col-md-3">
                                    <div class="select-style-1">
                                        <label for="">Type</label>
                                        <div class="select-position">
                                            <select name="type" class="form-control">
                                            <option value="cash">Cash</option>
                                            <option value="online">Online</option>
                                            <option value="both">Both</option>
                                            </select>
                                        </div>
                                       
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="input-style-1">
                                        <label for="">Date</label>
                                        <input type="date" name="date" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="input-style-1">
                                        <button type="submit" class="main-btn primary-btn rounded-full btn-hover"
                                            style="margin-top: 32px;">Search</button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            {{-- search filter --}}
           
            <!-- ========== tables-wrapper start ========== -->
            <div class="tables-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-style mb-30">
                            <h6 class="mb-10">Transactions</h6>
                            <div class="table-wrapper table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Transaction</th>
                                            <th>Print Job</th>
                                            <th>Amount</th>
                                            <th>Currency</th>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($data['transactions']) > 0)
                                            @foreach ($data['transactions'] as $key => $transaction)
                                                <tr>
                                                    <td>{{ $transaction->id }}</td>
                                                    @if($transaction->type=='cash')
                                                    <td>Cash payment</td>
                                                    @else
                                                    <td>{{ $transaction->trans_id }}</td>
                                                    @endif
                                                    <td>{{ $transaction->print_job_id }}</td>
                                                    <td>{{ $transaction->amount }}</td>
                                                    <td>{{ $transaction->currency }}</td>
                                                    <td>{{ConvertDate( $transaction->created_at ) }}</td>
                                                    <td>{{ $transaction->type }}</td>
                                                    <td>{{ $transaction->status}}</td>
                                                    <td>
                                                        @if($transaction->status == 'Refunded')
                                                        <a href="#"
                                                                class="main-btn disabled-btn square-btn btn-hover btn-sm disabled">Refunded</a>
                                                        @elseif($transaction->type == 'cash')
                                                        <a href="#"
                                                                class="main-btn disabled-btn square-btn btn-hover btn-sm disabled">CASH</a>
                                                        @else
                                                        <a href="{{ url('refund/' . $transaction->id) }}"
                                                                class="main-btn active-btn square-btn btn-hover btn-sm">Refund</a>
                                                        @endif
                                                    </td>
                                                   
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td>No Transaction found!</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                {{ $data['transactions']->links() }}
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
