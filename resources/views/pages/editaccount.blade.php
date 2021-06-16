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
                <label for="name">Name:</label>
                <input type="text" class="form-control" name="name" value="@if(old('name')){{old('name')}} @else<?= $user['name']?>@endif">
                </div>
                <div class="form-group col-md-4">
                  <label for="nickname">Nickname for Project Hours:</label>
                  <input type="text" class="form-control" name="nickname" value="@if(old('nickname')){{old('nickname')}} @else<?= $user['nickname']?>@endif">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                <label for="email">Email:</label>
                <input type="text" class="form-control" name="email" value="@if(old('email')){{old('email')}} @else<?= $user['email']?>@endif">
                </div>
              <div class="form-group col-md-4">
                <label for="jobclass">Job Class:</label>
                <th colspan="2"><select name='jobclass' class="form-control">
                  @if($user['jobclass'] == "senior")
                    <option value="">--SELECT--</option>
                    <option value="senior" selected>Senior</option>
                    <option value="project">Project</option>
                    <option value="SCADA">SCADA</option>
                    <option value="drafting">Drafting</option>
                    <option value="interns-admin">Interns-Admin</option>
                  @elseif($user['jobclass'] == "project")
                    <option value="">--SELECT--</option>
                    <option value="senior">Senior</option>
                    <option value="project" selected>Project</option>
                    <option value="SCADA">SCADA</option>
                    <option value="drafting">Drafting</option>
                    <option value="interns-admin">Interns-Admin</option>
                  @elseif($user['jobclass'] == "SCADA")
                    <option value="">--SELECT--</option>
                    <option value="senior">Senior</option>
                    <option value="project">Project</option>
                    <option value="SCADA" selected>SCADA</option>
                    <option value="drafting">Drafting</option>
                    <option value="interns-admin">Interns-Admin</option>
                  @elseif($user['jobclass'] == "drafting")
                    <option value="">--SELECT--</option>
                    <option value="senior">Senior</option>
                    <option value="project">Project</option>
                    <option value="SCADA">SCADA</option>
                    <option value="drafting" selected>Drafting</option>
                    <option value="interns-admin">Interns-Admin</option>
                  @elseif($user['jobclass'] == "interns-admin")
                    <option value="">--SELECT--</option>
                    <option value="senior">Senior</option>
                    <option value="project">Project</option>
                    <option value="SCADA">SCADA</option>
                    <option value="drafting">Drafting</option>
                    <option value="interns-admin" selected>Interns-Admin</option>
                  @else
                    <option value="" selected>--SELECT--</option>
                    <option value="senior">Senior</option>
                    <option value="project">Project</option>
                    <option value="SCADA">SCADA</option>
                    <option value="drafting">Drafting</option>
                    <option value="interns-admin">Interns-Admin</option>
                  @endif
                </select>
              </div>
            </div>
              <div class="row">
                <div class="form-group col-md-4">
                <label for="perhourdollar">Per hour dollar value for <?php echo date("Y"); ?>:</label>
                <input type="text" class="form-control" name="perhourdollar" value="@if(old('perhourdollar')){{old('perhourdollar')}} @else<?= $user['perhourdollar']?>@endif">
                </div>
              <div class="form-group col-md-4">
                <label for="role">CEG Tracker Role:</label><br>
                User <input type="radio" name="role" value="user" @if(isset($user['role']) && $user['role'] == 'user'){{'checked=checked'}}@endif/>
                Proposer <input type="radio" name="role" value="proposer" @if(isset($user['role']) && $user['role'] == 'proposer'){{'checked=checked'}}@endif/>
                Admin <input type="radio" name="role" value="admin" @if(isset($user['role']) && $user['role'] == 'admin'){{'checked=checked'}}@endif/>
              </div>
            </div>
            <div class="row">
            <div class="form-group col-md-4">
              <label class="checkbox-inline" for="owner">
                <input id="owner" name="owner" class="element checkbox" type="checkbox" @if($user['owner'] == true) checked @endif>Owner
              </label>
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