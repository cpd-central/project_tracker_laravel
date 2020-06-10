<html>
  <title id="page-title">Edit Account</title>
  <head>
    @include('includes.navbar')
  </head>
  <br>
  <br>
  <body>
    <div class="container">
        <form method="post">
          @csrf
        <div id="main">
            <h2><b>Edit Account</b></h2>
            <div class="row">
                <div class="form-group col-md-4">
                <label for="state">Name:</label>
                <input type="text" class="form-control" name="name" value="@if(old('name')){{ old('name') }} @else<?= $user['name']?>@endif">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                <label for="state">Email:</label>
                <input type="text" class="form-control" name="email" value="@if(old('email')){{ old('email') }} @else<?= $user['email']?>@endif">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4">
              <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </div>
        </form>
    </div>