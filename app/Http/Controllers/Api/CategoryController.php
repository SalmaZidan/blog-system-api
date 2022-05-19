<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Category\AddCategoryRequest;
use App\Http\Requests\Api\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\ApiTraits;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiTraits;

    public function index(){
        try {
            $categories = Category::with('posts')->paginate(30);
            return $this->responseJson(CategoryResource::collection($categories)->response()->getData(true));
        } catch (\Throwable $th) {
            return $this->responseJsonFailed();
        }
    }

    public function show($id){
        try {
            $category = Category::with('posts')->find($id);
            return $this->responseJson(new CategoryResource($category));
        } catch (\Throwable $th) {
            return $this->responseJsonFailed();
        }
    }

    public function store(AddCategoryRequest $request){
        try {
            Category::create($request->all());
            return $this->responseJsonWithoutData();
        } catch (\Throwable $th) {
            return $this->responseJsonFailed();
        }
    }

    public function update(UpdateCategoryRequest $request,  $id){
        try {
            $tag = Category::find($id);
            $tag->update($request->all());
            return $this->responseJsonWithoutData();
        } catch (\Throwable $th) {
            return $this->responseJsonFailed();
        }
    }

    public function destroy($id){
        try {
            Category::find($id)->delete();
            return $this->responseJsonWithoutData();
        } catch (\Throwable $th) {
            return $this->responseJsonFailed();
        }
    }
}
