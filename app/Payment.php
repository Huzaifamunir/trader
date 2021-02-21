<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'receiver_id', 
    	'payer_id',
        'sale_id',
        'date',
        'transaction_mode',
        'amount',
        'remarks',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
    	'receiver_id' => ['required','numeric'], 
        'client_id' => ['required','numeric'],
        //'date' => ['required','date'],
        'transaction_mode' => ['required','string'],
        'amount' => ['required','numeric'],
        'remarks' => ['nullable','string'],
	];

    /**
     * One to One Relationship.
     *
     * @var array
     */
    public function receiver(){
        return $this->belongsTo('App\User','receiver_id');
    }

    /**
     * One to One Relationship.
     *
     * @var array
     */
    public function payer(){
        return $this->belongsTo('App\User','payer_id');
    }
}
