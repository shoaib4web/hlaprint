@extends('english.main')
@section('content')
@section('page', 'code')
@section('title', 'Code')

<body>
    <section class="w-100 m-auto code">
        <div class="container m-auto">
            @include('english.layouts.top')
            <form method="POST" action="{{ route('printer_select') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="file"
                    value="@if (isset($file)) @php echo $file; @endphp @endif">
                <input type="hidden" name="color"
                    value="@if (isset($color)) @php echo $color; @endphp @endif">
                <input type="hidden" name="sides"
                    value="@if (isset($sides)) @php echo $sides; @endphp @endif">
                <input type="hidden" name="copies"
                    value="@if (isset($copies)) @php echo $copies; @endphp @endif">
                <input type="hidden" name="range"
                    value="@if (isset($range)) @php echo $range; @endphp @endif">
                <div class="middle d-flex justify-content-center align-items-start">
                    <div class="center">
                        <h2>Select a Printer</h2>

                        <div class="wa d-flex">
                            <select name="printer">
                                @foreach ($printers as $printer)
                                    <option value="{{ $printer->id() }}">{{ $printer->name() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="bottom d-flex justify-content-between align-items-center">
                    <a href="{{ route('englishShare') }}"
                        class="backbtn d-flex justify-content-center align-items-center">
                        < </a>
                            @if (isset($shop))
                                <input type="hidden" name="shop" value="{{ $shop }}">
                            @endif
                            <button type="submit" class="text-white d-flex justify-content-center align-items-center">
                                Continue >
                            </button>
            </form>
        </div>
        </div>
    </section>
</body>
@endsection
