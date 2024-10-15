<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\UpdateMediaRequest;
use App\Http\Requests\StoreMediaRequest;

use App\Models\Media;
use App\Models\User;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return Media::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMediaRequest $request)
    {
        //
        $media = Media::create($request->validated());
        return response()->json($media, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(/*string $id*/ Media $media)
    {
        //
        return $media;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMediaRequest $request, /*string $id*/ Media $media)
    {
        //
        $media->update($request->validated());
        return response()->json($media);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(/*string $id*/ Media $media)
    {
        //
        $media->delete();
        return response()->json(null, 204);

    }
}
