<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'state_id', 
    	'name'
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
    	'state_id' => ['required','numeric'],
		  'name' => ['required','string','unique:cities']
	  ];

	/**
   	 * Remove Default Timestamps.
   	 *
   	 */
	public $timestamps = false;

  	/**
     * Many to One Relationship.
     *
     * @var array
     */
  	public function state(){
    	return $this->belongsTo('App\State');
  	}
}
