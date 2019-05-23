<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Project extends Eloquent 
{
	protected $connection = 'mongodb';
 	protected $collection = 'projects';
	protected $guarded = [];	
	/*protected $fillable = [
		'Project Name',
		'Client Contact Name',
		'Client Company',
		'CEG Proposal Author',
		'MW Size (AC)',
		'Voltage',
		'Dollar Value',
		'Date Sent (YYYY-MM-DD)',
		'Start Date (YYYY-MM-DD)',
		'End Date (YYYY-MM-DD)',
		'Project Status',
		'Project Type',
		'EPC Type',
	];	
	*/
}
