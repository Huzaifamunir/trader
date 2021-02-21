<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'name'
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
		'name' => ['required','string','unique:countries']
	];

	/**
   	 * Remove Default Timestamps.
   	 *
   	 */
	public $timestamps = false;

    /**
     * Get the states of country.
     */
    public function states()
    {
        return $this->hasMany('App\State');
    }
}
