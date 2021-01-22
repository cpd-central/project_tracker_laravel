<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Page extends Eloquent 
{
	protected $connection = 'mongodb';
 	protected $collection = 'page_visits';
	protected $guarded = [];	
}