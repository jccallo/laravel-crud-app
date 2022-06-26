<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\tag\StoreRequest;
use App\Http\Requests\tag\UpdateRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $perPage = request('perpage') ? intval(request('perpage')) : 50;
        $tags = Tag::paginate($perPage);
        return TagResource::collection($tags)
            ->additional([
                'success' => true,
            ]);
    }

    public function store(StoreRequest $request)
    {
        $tag = Tag::create($request->all());
        return (new TagResource($tag))
            ->additional([
                'success' => true,
            ]);
    }

    public function show(Tag $tag)
    {
        return (new TagResource($tag))
            ->additional([
                'success' => true,
            ]);
    }

    public function update(UpdateRequest $request, Tag $tag)
    {
        $tag->update($request->all());
        return (new TagResource($tag))
            ->additional([
                'success' => true,
            ]);
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return (new TagResource($tag))
            ->additional([
                'success' => true
            ]);
    }
}
