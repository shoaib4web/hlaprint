@extends('admin.setup.master')

@section('title', 'Select Computer')
@php use App\Models\Shops; @endphp 
@section('content')
    <!-- ========== table components start ========== -->
    <section class="table-components">

        <div class="container-fluid">
            <!-- ========== title-wrapper start ========== -->

            <div class="title-wrapper pt-30">
                @if (Session::has('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert">
                            <i class="fa fa-times"></i>
                        </button>
                        {{ session('success') }}
                    </div>
                @elseif (Session::has('error'))
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert">
                            <i class="fa fa-times"></i>
                        </button>
                        {{ session('error') }}
                    </div>
                @endif
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>Computer</h2>
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
                                        Select Computer
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
                        <form action="{{ url('store_shop_computer') }}" method="POST">
                            @csrf

                            <div class="row">
                            <div class="col-md-3">
                           
                               
                                <div class="input-style-1">
                                <select name="shop_id" class="form-control" required>
                                        <option value="" selected disabled>Choose shop</option>
                                        <option value="0">Admin</option>
                                        @isset($data['shops'])
                                            @foreach ($data['shops'] as $shop)
                                                <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                <!-- @if(Auth::user()->role != 'superadmin') -->
                               
                                <!-- @else
                                    <select disabled class="form-control">
                                        <option value="" selected disabled>Admin </option>
                                    </select>
                                    <input type="hidden" name="shop_id" value="{{isset($shop_id)?$shop_id:0}}"> -->
                                <!-- @endif -->
                                </div>
                            </div>
                           
                                  
                            <div class="col-md-3">
                               

                                <div class="wa d-flex">
                                    <select id="multiSelectBox" name="computer_id[]" class="form-control" multiple>
                                    <option value="" >Select Computer</option>
                                        @foreach ($computerArray as $computer)
                                        
                                            <option value="{{ $computer->id}}">{{ $computer->name}}</option>
                                        
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                           
                                <div class="col-md-2">
                                    <div class="input-style-1">
                                        <button type="submit"
                                            class="main-btn primary-btn rounded-full btn-hover" style="margin-top: 32px;">
                                            @if(Auth::user()->role == 'superadmin')
                                                @if (count($shopPrinters) > 0)
                                                    Update
                                                @else
                                                    Submit
                                                @endif
                                            @else
                                                 Submit
                                            @endif
                                        </button>
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
                            <h6 class="mb-10">Shop Printers</h6>
                            <div class="table-wrapper table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>shop </th>
                                            <th>Computer</th>
                                           
                                            
                                        </tr>
                                    </thead>
                                   
                                    <tbody id="shopTable">
                                        @if (count($shopPrinters) > 0)
                                            @foreach ($shopPrinters as $p)
                                                <tr>
                                                    <td>
                                                       @php $name = Shops::Where('id',$p->shop_id)->first();@endphp
                                                       @if($name){{ $name->name}}@else Admin @endif
                                                    </td>
                                                    <td>
                                                    @if($p->computer_id != 'null')
                                                        @php $shopComputer = json_decode($p->computer_id);@endphp
                                                        @if($shopComputer)
                                                            @foreach($shopComputer as $s)
                                                        
                                                                @foreach($computerArray as $c)
                                                                    @if($c->id== $s)
                                                                    {{$c->name}}<br>
                                                                    @endif
                                                                @endforeach
                                                            @endforeach
                                                        @endif
                                                        
                                                        @endif
                                                       
                                                    </td>
                                                    <td>
                                                   
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
            <!-- ========== tables-wrapper end ========== -->

        </div>
        <!-- end container -->
    </section>
    <!-- ========== table components end ========== -->

@endsection

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- CDN for Sweet Alert -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$('option').mousedown(function(e) {
    e.preventDefault();
    $(this).prop('selected', !$(this).prop('selected'));
    return false;
});
</script>