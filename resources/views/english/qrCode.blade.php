@extends('english.main')
@section('content')
@section('page', 'code')
@section('title', 'QRCODE')

<body>
    <section class="w-100 m-auto code">
        <div class="container m-auto">
            @include('english.layouts.top')
            @if(!isset($shop))
            <form method="POST" action="{{ route('englishQrCode') }}" enctype="multipart/form-data" style="
    width: 7em;
    margin: auto;
    margin-top: 3em;
    margin:auto;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    width: auto;
    margin-top: 10rem;
">
                @csrf
                <select class="shop" name="shop" >
                    @foreach($shops as $shop)
                        <option value="{{ $shop->id }}">{{ $shop->address }}</option>
                    @endforeach
                </select>
                <div class="bottom" style="margin-right:0;     margin: auto;
    display: flex;
    justify-content: center;">
<a href="" style="display:none;"></a>
                <button type="submit" style="
    font-size: 3rem;
    margin-top: 3em;
    padding: 0.3em;
    margin-left: 0;
    color: white;
">Submit</button>
                </div>

                </form>
                @elseif(isset($shop))
                {!! QrCode::size(300)->generate(url('/').'/uploadShop/'.$shop->id) !!}
                @endif;
            </div>
        </div>
    </section>
</body>
@endsection
