<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Tag\AddTagRequest;
use App\Http\Requests\Api\Tag\UpdateTagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use App\Traits\ApiTraits;
use Illuminate\Http\Request;

class TagController extends Controller
{
    use ApiTraits;

    public function index(){
        try {
            $tags = Tag::with('posts')->paginate(30);
            return $this->responseJson(TagResource::collection($tags)->response()->getData(true));
        } catch (\Throwable $th) {
            return $this->responseJsonFailed();
        }
    }

    public function show($id){
        try {
            $tag = Tag::with('posts')->find($id);
            return $this->responseJson(new TagResource($tag));
        } catch (\Throwable $th) {
            return $this->responseJsonFailed();
        }
    }

    public function store(AddTagRequest $request){
        try {
            Tag::create($request->all());
            return $this->responseJsonWithoutData();
        } catch (\Throwable $th) {
            return $this->responseJsonFailed();
        }
    }

    public function update(UpdateTagRequest $request,  $id){
        try {
            $tag = Tag::find($id);
            $tag->update($request->all());
            return $this->responseJsonWithoutData();
        } catch (\Throwable $th) {
            return $this->responseJsonFailed();
        }
    }

    public function destroy($id){
        try {
            Tag::find($id)->delete();
            return $this->responseJsonWithoutData();
        } catch (\Throwable $th) {
            return $this->responseJsonFailed();
        }
    }
}
