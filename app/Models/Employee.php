<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Employee extends Model
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

    /**
     * Classes relationship
     * 
     * @return BelongsToMany
     */
    public function classes() : BelongsToMany
    {
        return $this->belongsToMany(WondeClass::class, 'wonde_classes_employees');
    }
}
