<?php

namespace App\Http\Controllers\Api\V1;

use DB;
use App\Genre;
use App\Movie;
use Illuminate\Http\Request;
use App\Http\Requests\GenreRequest;
use App\Http\Controllers\Controller;

class GenresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Genre::with('movies', 'movies.actors')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  GenreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GenreRequest $request)
    {
        $genre = Genre::create($request->all());

        return response()->json($genre, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $genre = Genre::findOrFail($id);

        return response()->json($genre, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  GenreRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GenreRequest $request, $id)
    {
        $genre = Genre::findOrFail($id);

        $genre->update($request->all());

        return response()->json($genre, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            Movie::where('genre_id', $id)->update(['genre_id' => null]);
            Genre::destroy($id);
        });

        return response('', 204);
    }
}
