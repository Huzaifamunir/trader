<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockItems extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'stock_id', 
      'product_id',
    	'price_per_unit',
      'quantity',
      'sub_total',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
    	'product_id'    => 'required|numeric',
    	'price_per_unit' => 'required|numeric',
      'quantity' => 'required|numeric',
      'sub_total' => 'required|numeric',
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
  	public function stock(){
    	return $this->belongsTo('App\Stock');
  	}

  	public function product(){
      return $this->belongsTo('App\Product','product_id');
    }

    /**
     * Return updated stock_items.
     *
     * @var array
     */
    // public function item(){
    //   $StockItems = StockItems::with('item')->whereHas('item', function($query){
    //       $query->where('mobile_id', '=', '%'.$keyword.'%');

    //       // unique mobile id and state === stock
    //   });
    // }
}
