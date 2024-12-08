@extends('admin.setup.master')

@section('title', 'Print Jobs')
@php
     use Illuminate\Support\Facades\DB; 
     use App\Models\PrintJob;
     use App\Models\Shops;


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
                            <h2>Print Jobs</h2>
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
                                        Print Jobs
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
                    <div class="col-lg-12">
                    
                    </div>
                        <div class="card-style mb-30">
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
                            <h6 class="mb-10">Print Jobs</h6>
                            {{-- <div class="table-wrapper table-responsive"> --}}
                               <div class="table-container">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <!-- <th>#</th> -->
                                            <th>Shop Name</th>
                                            <th>ID</th>
                                            <th>Phone</th>
                                            <th>Code</th>
                                            <th>Color</th>
                                            <th>Double Sided</th>
                                            <th>Page Start</th>
                                            <th>Page End</th>
                                            <th>Page Size</th>
                                            <th>Copy</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="shopTable">
                                        @if (count($data['trans']) > 0)
                                           
                                            @foreach ($data['trans'] as $print)
                                            
                                                @php 
                                                   $codeArrays=[];
                                                    $jobsIds = json_decode($print->print_job_id); 
                                                   
                
                                                @endphp
                                              
                                               
                                                @foreach($jobsIds as $j)
                                                    @php
                                                     $fetchJobs =  PrintJob::where('id',$j)->get();
                                                     
                                                    if(count($fetchJobs)>0){
                                                        array_push($codeArrays,$fetchJobs);
                                                    }
                                                     
                                                   
                                                     @endphp
                                                   
                                                     
                                                   
                                                @endforeach
                                             
                                              @if($jobsIds)
                                               @foreach($jobsIds  as $jobs)
                                            
                                                @php
                                                
                                                    $fetchJobs =  PrintJob::where('id',$j)->get();
                                                @endphp
                                                        
                                                    @if(count($fetchJobs)>0)
                                                    @php $count=0; @endphp 
                                                        @foreach($fetchJobs as $item)
                                                            <tr>
                                                                
                                                            <td>@php
                                                                if((!$item['shop_id']) || ($item['shop_id'] =='0' ) )
                                                                {
                                                                    echo'Main';

                                                                }else{
                                                                    $shopName= Shops::where('id',$item['shop_id'])->first();
                                                                    echo  $shopName->name;
                                                                }
                                                                @endphp
                                                            </td>
                                                            <td>{{$item['id'] }}</td>
                                                             <td>{{$item['phone'] }}</td>
                                                            <td>{{ $item['code'] }}</td>
                                                            <td>{{ $item['color'] }}</td>
                                                            <td>{{ $item['double_sided'] }}</td>
                                                            <td>{{ $item['pages_start'] }}</td>
                                                            <td>{{ $item['page_end'] }}</td>
                                                            <td>{{ $item['page_size'] }}</td>
                                                            <td>{{ $item['copies'] }}</td> 
                                                            <td>{{ $item['status'] }}</td>
                                                            <td>
                                                                
                                                                <!-- <a href="{{ url('generate-new-invoice/' . $item->code) }}"
                                                                    class="main-btn active-btn square-btn btn-hover btn-sm">Generate
                                                                    Invoice</a> -->
                                                                   
                                                                <a href="{{ url('generate-new-print/' . $item->code) }}"
                                                                class="main-btn active-btn square-btn btn-hover btn-sm">Reprint</a>
                                                            </td>
                                                            </tr>
                                                        @endforeach
                                                    
                                                    @endif
                                                        
                                                    
                                             
                                               
                                               @endforeach
                                            @endif
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            <tr>
                                                  
                                                    <!-- <td>{{ $print->printshop?$print->printshop->name:'Main' }}</td>
                                                    <td>{{ $print->phone }}</td>
                                                    <td>{{ $print->code }}</td>
                                                    <td>{{ $print->color }}</td>
                                                    <td>{{ $print->double_sided }}</td>
                                                    <td>{{ $print->pages_start }}</td>
                                                    <td>{{ $print->page_end }}</td>
                                                    <td>{{ $print->page_size }}</td>
                                                    <td>{{ $print->copies }}</td> -->
                                                    <td>
                                                        <!-- <a href="{{ url('generate-invoice/' . $print->id) }}"
                                                            class="main-btn active-btn square-btn btn-hover btn-sm">Generate
                                                            Invoice</a>
                                                        <a href="{{ url('rePrint/' . $print->id) }}"
                                                        class="main-btn active-btn square-btn btn-hover btn-sm">Reprint</a> -->
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
