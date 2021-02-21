<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
  	'name', 
  ];

  /**
   * Validation rules.
   *
   * @var array
   */
  public static $rules = [
  	'name' => ['required','string'],
	];

  /**
 	 * Remove Default Timestamps.
 	 *
 	 */
	public $timestamps = false;

	/**
   * One to Many Relationship.
   *
   * @var array
   */
	public function sub_categories(){
  	return $this->hasMany('App\SubCategory');
	}
}
