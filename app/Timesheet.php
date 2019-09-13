<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Timesheet extends Eloquent
{
    protected $connection = 'mongodb';
 	protected $collection = 'timesheets';
	protected $guarded = [];	
}
