<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    /**
     * Appendd attributes to model
     *
     * @var array
     */
    protected $appends = ['full_name'];

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
     * Set the keyType to string so that relationships work
     */
    protected $keyType = 'string';

    /**
     * Classes relationship
     * 
     * @return BelongsToMany
     */
    public function classes() : BelongsToMany
    {
        return $this->belongsToMany(WondeClass::class);
    }

    public function getFullNameAttribute()
    {
        return (!empty ($this->forename) ? $this->forename . ' ' : '') . $this->surname;
    }
}
