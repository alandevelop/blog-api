<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Comment;
use App\Http\Resources\CommentResource;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  int  $post_id
     */
    public function index($post_id)
    {
        $comments = Comment::where(['post_id' => $post_id])->paginate(5);

        return CommentResource::collection($comments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CommentRequest  $request
     * @param  int  $post_id
     */
    public function store(CommentRequest $request, $post_id)
    {
        $comment = new Comment();

        $comment->post_id = $post_id;
        $comment->email = $request->input('email');
        $comment->content = $request->input('content');

        $comment->save();

        return (new CommentResource($comment))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $post_id
     * @param  int  $comment_id
     */
    public function show($post_id, $comment_id)
    {
        $comment = Comment::findOrFail($comment_id);

        return (new CommentResource($comment))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CommentRequest  $request
     * @param  int  $post_id
     * @param  int  $comment_id
     */
    public function update(CommentRequest $request, $post_id, $comment_id)
    {
        $comment = Comment::findOrFail($comment_id);

        $comment->update($request->all());

        return (new CommentResource($comment))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $post_id
     * @param  int  $comment_id
     */
    public function destroy($post_id, $comment_id)
    {
        Comment::destroy($comment_id);

        return response([], Response::HTTP_NO_CONTENT);
    }
}
