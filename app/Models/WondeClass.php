<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WondeClass extends Model
{
    /** 
     * Make all attributes mass assignable
     * 
     * @var array
     */
    protected $guarded = [];

    /**
     * Make sure that Laravel doesn't try and increment the ID
     *
     * @var boolean
     */
    public $incrementing = false;
}
