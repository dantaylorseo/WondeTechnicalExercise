<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    /**
     * Employees relationship
     * 
     * @return BelongsToMany
     */
    public function employees() : BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'wonde_classes_employees');
    }
}