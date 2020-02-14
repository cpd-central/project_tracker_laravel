<!doctype html>
<html>
  <head>
    @include('includes.navbar')
  </head>
  <body>
    <div class="container">
      <div id="main">
      </br>
      </br>
      <div class="container">
<?php $vars= array(); ?>
@foreach ($projects as $project)
<?php echo "<br>"; ?>
<?php echo "Project: " . $project['projectname'] . "<br>"; ?>
<?php $billing_data = $project['bill_amount']; ?>
<?php $years = array_keys($billing_data); ?>

@foreach ($years as $year)
<?php $years_billing_data = $billing_data[$year]; ?>
<?php $months = array_keys($years_billing_data); ?>
@foreach ($months as $month)
<?php echo $year . ", " . $month . ": " . $years_billing_data[$month] . "<br>"; ?>
@endforeach
@endforeach
@endforeach
      </div>
      </div>
    </div>
  </body>
</html> 
