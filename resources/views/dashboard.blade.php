@extends('main_dashboard')
@section('content')
    

<div class="container d-flex justify-content-center">
  <div class="card p-3" style="background-color: #ffffff; max-width: 400px;">
    <form action="{{route('print_request')}}" method="POST">@csrf
      <div class="form-group">
        <label for="copies">Number of copies:</label>
        <input type="number" id="copies" name="copies" class="form-control" required>
      </div>

      <div class="form-group">
        <label for="pages">Number of pages:</label>
        <input type="number" id="pages" name="pages" class="form-control" required>
      </div>

      <div class="form-group">
        <label for="sided">Single or double sided:</label>
        <select id="sided" name="sided" class="form-control" required>
          <option value="single">Single</option>
          <option value="double">Double</option>
        </select>
      </div>

      <div class="form-group">
        <label for="color">Color or black and white:</label>
        <select id="color" name="color" class="form-control" required>
          <option value="color">Color</option>
          <option value="bw">Black and white</option>
        </select>
      </div>

      <div class="text-center">
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>

    </form>
  </div>
</div>


@endsection