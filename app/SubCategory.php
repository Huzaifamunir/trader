<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
	/**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
  	'main_category_id',
  	'name', 
  ];

  /**
   * Validation rules.
   *
   * @var array
   */
  public static $rules = [
  	'main_category_id' => ['required','numeric'],
  	'name' => ['required','string'],
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
	public function main_category(){
  	return $this->belongsTo('App\MainCategory');
	}

	/**
   * One to Many Relationship.
   *
   * @var array
   */
	public function products(){
  	return $this->hasMany('App\Product');
	}
}
