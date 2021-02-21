<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'user_id', 
    	'provider_id',
    	'total_sets',
    	'total_amount'
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [ 
    	'total_sets' => ['required'],
    	'total_amount' => ['required']
	];

  	/**
     * Many to One Relationship.
     *
     * @var array
     */
  	public function user(){
    	return $this->belongsTo('App\User');
  	}

  	/**
     * Many to One Relationship.
     *
     * @var array
     */
  	public function provider(){
    	return $this->belongsTo('App\Provider');
  	}

    /**
     * One to Many Relationship.
     *
     * @var array
     */
    public function items(){
        return $this->hasMany('App\StockItems');
    }
}
