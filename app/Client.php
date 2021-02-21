<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
  	'user_id', 	
    'parent_id',
  	'company_name',
  	'tax_no',
  	'hrb_no',
 		'start_bal',
 		'current_bal'
  ];

  /**
   * Validation rules.
   *
   * @var array
   */
  public static $rules = [
  	'first_name' => ['required','string'],
  	'last_name' => ['required','string'],
    'mobile_no' => ['required','string'],
    'city_id' => ['required','string'],
 		'current_bal' => ['nullable','string'],
	];

	/**
   * One to One Relationship.
   *
   * @var array
   */
	public function user(){
  	return $this->belongsTo('App\User','user_id');
	}

  /**
   * One to One Relationship.
   *
   * @var array
   */
  public function parent(){
    return $this->belongsTo('App\User','parent_id');
  }
}
