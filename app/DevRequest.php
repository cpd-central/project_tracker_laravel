<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class DevRequest extends Eloquent 
{
	protected $connection = 'mongodb';
 	protected $collection = 'dev_requests';
	protected $guarded = [];	
}