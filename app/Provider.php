<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'user_id',
        'company_name'
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
    	'user_id'      => 'required|numeric',
        'company_name' => 'required|string'
	];

    /**
    * Load Relationship with user.
    *
    */       
    protected $with = array('user');

  	/**
     * One to One Relationship.
     *
     * @var array
     */
  	public function user(){
    	return $this->belongsTo('App\User');
  	}

  	/**
     * One to One Relationship.
     *
     * @var array
     */
  	public function mobile(){
    	return $this->belongsTo('App\Mobile');
  	}
}
