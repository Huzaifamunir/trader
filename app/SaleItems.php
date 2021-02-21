<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleItems extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	  'sale_id', 
        'product_id',
    	  'price_per_unit',
        'quantity',
        'sub_total',
        'profit',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
    	'product_id' => 'required|numeric',
      'price_per_unit' => 'required|numeric',
      'quantity' => 'required|numeric',
      'sub_total' => 'required|numeric',
      'profit' => 'nullable',
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
  	public function sale(){
    	return $this->belongsTo('App\Sale');
  	}

  	public function product(){
      return $this->belongsTo('App\Product','product_id');
    }
}
