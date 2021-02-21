<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  protected $fillable = [
    'sub_category_id',
    'name', 
  	'model',
    'purchase_price',
    'sale_price',
    'current_stock',
  	'min_stock_value',
  	'image',
  	'comment',
  ];

  public static $rules = [
    'name'            => ['required','string'], 
    'model'           => ['required','string'],
    'sale_price'      => ['nullable','numeric'],
    'min_stock_value' => ['nullable','numeric'],
    'image'           => ['nullable','image'],
    'comment'         => ['nullable','string'],
  ];

  public function sub_category(){
    return $this->belongsTo('App\SubCategory');
  }

  public function sales(){
    return $this->hasMany('App\SaleItems');
  }
}
