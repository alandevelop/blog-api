<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('comments')->paginate(3);

        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PostRequest  $request
     */
    public function store(PostRequest $request)
    {
        $post = new Post();

        $post->title = $request->input('title');
        $post->content = $request->input('content');

        $post->save();

        return (new PostResource($post))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $post_id
     */
    public function show($post_id)
    {
        $post = Post::with('comments')->findOrFail($post_id);

        return (new PostResource($post))
            ->response()
            ->setStatusCode(Response::HTTP_OK);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\PostRequest  $request
     * @param  int  $post_id
     */
    public function update(PostRequest $request, $post_id)
    {
        $post = Post::findOrFail($post_id);

        $post->update($request->all());

        return (new PostResource($post))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $post_id
     */
    public function destroy($post_id)
    {
        Post::destroy($post_id);

        return response([], Response::HTTP_NO_CONTENT);
    }
}
