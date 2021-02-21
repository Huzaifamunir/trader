<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'person_id', 'user_type', 'title', 'user_group', 'api_token', 'username', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'api_token', 'username', 'password', 'remember_token',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'user_type' => 'required|string|max:191',
        'title' => 'nullable|string|max:191',
        'user_group' => 'nullable|string|max:191',
        'username' => 'required|string|max:191|unique:users',
        'password' => 'required|string|min:6|confirmed',
    ];

    /**
     * Permissions Entities.
     *
     * @var array
     */
    public static $entities=[
        'user','salesman','client','provider','reseller',
        'stock','bag','sale','country','state','city',
        'permission','report','Client_Discount','salesman_discount',
        'main_category','sub_category','product','product_return',
        'payment','user_group','bag_return'
    ];

    /**
    * Load Relationship with user.
    *
    */       
    protected $with = array('person');

    /**
     * Many to One relationship.
     *
     * @var array
     */
    public function person(){
      return $this->belongsTo('App\Person');
    }

    /**
     * Get the provider record associated with the user.
     */
    public function provider()
    {
        return $this->hasOne('App\Provider');
    }

    /**
     * Get the client record associated with the user.
     */
    public function client()
    {
        return $this->hasOne('App\Client');
    }

    /**
     * One to Many relationship.
     *
     * @var array
     */
    public function sales(){
      return $this->hasMany('App\Sale','seller_id');
    }
}
