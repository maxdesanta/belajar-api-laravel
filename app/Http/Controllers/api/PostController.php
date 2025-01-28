<?php

namespace App\Http\Controllers\api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(){
        $posts = Post::latest()->paginate(5);

        return new PostResource(true, "Data Post", $posts);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('image');
        $image->storeAs('public/post', $image->hashName());

        $post = Post::create([
            'image' => $image->hashName(),
            'title' => $request->title,
            'content' => $request->content
        ]);

        return new PostResource(true, "Data Post Berhasil Diinput", $post);
    }

    public function show($id){
        $posts = Post::find($id);

        return new PostResource(true, "Detail Data Post", $posts);
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $post = Post::find($id);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/post', $image->hashName());

            Storage::delete('public/post/' . $post->image);

            $post->update([
                'image' => $image->hashName(),
                'title' => $request->title,
                'content' => $request->content
            ]);
        } else {
            $post->update([
                'title' => $request->title,
                'content' => $request->content
            ]);
        }

        return new PostResource(true, "Data Post Berhasil Diupdate", $post);
    }

    public function destroy($id){
        $post = Post::find($id);

        Storage::delete('public/post/' . $post->image);

        $post->delete();

        return new PostResource(true, "Data Post Berhasil Dihapus", null);
    }
}
