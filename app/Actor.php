<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'dob',
        'biography'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'dob',
        'created_at',
        'updated_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['age'];

    /**
     * Get the actors age from their date of birth
     *
     * @return integer|null
     */
    public function getAgeAttribute()
    {
        if (empty($this->dob)) {
            return null;
        }

        return $this->dob->age;
    }

    /**
     * The movies this actor is associated with
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function movies()
    {
        return $this->belongsToMany('App\Movie')->withPivot('character');
    }
}
