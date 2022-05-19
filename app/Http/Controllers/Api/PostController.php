<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Post\AddPostRequest;
use App\Http\Requests\Api\Post\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Traits\ApiTraits;
use Illuminate\Http\Request;

class PostController extends Controller
{
    use ApiTraits;

    public function index()
    {
        try {
            $posts = Post::with('tags')->latest()->paginate(20);
            return $this->responseJson(PostResource::collection($posts)->response()->getData(true));
        } catch (\Throwable $th) {
            return $this->responseJsonFailed();
        }
    }

    public function userPosts(Request $request)
    {
        try {
            $posts = $request->user()->posts()->with('tags')->latest()->paginate(20);
            return $this->responseJson(PostResource::collection($posts));
        } catch (\Throwable $th) {
            return $this->responseJsonFailed();
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $post = Post::with('tags')->find($id);
            return $this->responseJson(new PostResource($post));
        } catch (\Throwable $th) {
            return $this->responseJsonFailed();
        }
    }

    public function store(AddPostRequest $request)
    {
        try {
            $post = $request->user()->posts()->create($request->except('tags'));
            $post->tags()->attach($request->tags);
            return $this->responseJsonWithoutData();
        } catch (\Throwable $th) {
            return $this->responseJsonFailed();
        }
    }

    public function update(UpdatePostRequest $request, $id)
    {
        try {
            $post = Post::find($id);
            if($post->user_id == $request->user()->id){
                $post->update($request->except('tags'));
                if($request->filled('tags')){
                    $post->tags()->sync($request->tags);
                }
                return $this->responseJsonWithoutData();
            }else{
                return $this->responseJsonFailed('permission missing');
            }
        } catch (\Throwable $th) {
            return $this->responseJsonFailed();
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $post = Post::find($id);
            if($post->user_id == $request->user()->id){
                Post::find($id)->delete();
                return $this->responseJsonWithoutData();
            }else{
                return $this->responseJsonFailed('permission missing');
            }
        } catch (\Throwable $th) {
            return $this->responseJsonFailed();
        }
    }
}
