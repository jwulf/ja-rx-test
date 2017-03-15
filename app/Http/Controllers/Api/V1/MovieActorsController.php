<?php

namespace App\Http\Controllers\Api\V1;

use App\Actor;
use App\Movie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MovieActorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  int $movieId
     * @return \Illuminate\Http\Response
     */
    public function index($movieId)
    {
        return Movie::findOrFail($movieId)->actors;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * $param  int  $movieId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $movieId)
    {
        $this->validate($request, [
            'actor_id'  => 'required|integer',
            'character' => 'required|string',
        ]);

        $movie = Movie::with('actors')->findOrFail($movieId);
        $actor = Actor::findOrFail($request->actor_id);

        if (! $movie->actors->where('id', $actor->id)->isEmpty()) {
            return response()->json('Actor already assigned to movie', 409);
        }

        $movie->actors()->attach($actor->id, ['character' => $request->character]);

        return response()->json([
            'actor_id'  => $actor->id,
            'movie_id'  => $movie->id,
            'character' => $request->character,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $movieId
     * @param  int  $actorId
     * @return \Illuminate\Http\Response
     */
    public function destroy($movieId, $actorId)
    {
        $movie = Movie::findOrFail($movieId);

        $movie->actors()->detach($actorId);

        return response('', 204);
    }
}
