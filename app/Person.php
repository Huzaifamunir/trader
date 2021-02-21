<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'first_name', 
    	'last_name', 
    	'mobile_no', 
    	'land_line_no', 
    	'address', 
    	'city_id', 
    	'state_id', 
    	'country_id',
    	'postal_code',
    	'comment',
    	'status',
    	'email'
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
		'first_name' => 'required|string|max:30', 
    	'last_name' => 'required|string|max:30', 
    	'mobile_no' => 'required|string', 
    	'land_line_no' => 'nullable|string', 
    	'address' => 'required|string', 
    	'city_id' => 'required|integer', 
    	'state_id' => 'nullable|integer', 
    	'country_id' => 'nullable|integer',
    	'postal_code' => 'nullable|string',
    	'comment' => 'nullable|string',
    	'status' => 'required|string',
    	'email' => 'required|email|unique:people'
    	//'email' => 'required|unique:person|email'.$id
	];

    /**
     * Many to One Relationship.
     *
     * @var array
     */
    public function city(){
        return $this->belongsTo('App\City');
    }

    /**
     * Many to One Relationship.
     *
     * @var array
     */
    public function state(){
        return $this->belongsTo('App\State');
    }

    /**
     * Many to One Relationship.
     *
     * @var array
     */
    public function country(){
        return $this->belongsTo('App\Country');
    }

    /**
     * Get the user record associated with the person.
     */
    public function user()
    {
        return $this->hasOne('App\User');
    }

    /**
     * Get the client record associated with the person.
     */
    public function client()
    {
        return $this->hasOne('App\Client');
    }

    /**
     * Get the provider record associated with the person.
     */
    public function provider()
    {
        return $this->hasOne('App\Provider');
    }

    /**
     * Get the salesman record associated with the person.
     */
    public function salesman()
    {
        return $this->hasOne('App\Salesman');
    }
}
