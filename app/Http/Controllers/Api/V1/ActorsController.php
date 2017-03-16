<?php

namespace App\Http\Controllers\Api\V1;

use DB;
use App\Actor;
use Illuminate\Http\Request;
use App\Http\Requests\ActorRequest;
use App\Http\Controllers\Controller;

class ActorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Actor::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ActorRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ActorRequest $request)
    {
        $actor = Actor::create($request->all());

        return response()->json($actor, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $actor = Actor::findOrFail($id);

        return response()->json($actor, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ActorRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ActorRequest $request, $id)
    {
        $actor = Actor::findOrFail($id);

        $actor->update($request->all());

        return response()->json($actor, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $actor = Actor::findOrFail($id);

        DB::transaction(function () use ($actor) {
            $actor->movies()->detach();
            $actor->delete();
        });

        return response('', 204);
    }
}
