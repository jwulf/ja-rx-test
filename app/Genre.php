<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Genre extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The movies in this genre
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function movies()
    {
        return $this->hasMany('App\Movie');
    }

    /**
     * The actors associated with the movies in this genre
     *
     * @return Collection
     */
    public function getActorsAttribute()
    {
        $actors = new Collection();

        $this->movies->load('actors');

        $this->movies->each(function ($movie) use ($actors) {
            $movie->actors->each(function ($actor) use ($actors, $movie) {
                // Prevent adding the same actor multiple times
                if ($actors->where('id', $actor->id)->count() === 0) {
                    unset($actor->pivot);
                    $actors->push($actor);
                }
            });
        });

        return $actors;
    }
}
