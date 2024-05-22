<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::all();
        return response()->json($comments);
    }

    public function store(Request $request)
    {
        $comment = new Comment();

        $comment->user_id = $request->user_id;
        $comment->event_id = $request->event_id;
        $comment->parent_comment_id = $request->parent_comment_id;
        $comment->content = $request->content;

        $comment->save();
        return response()->json($comment, 201);
    }

    public function show($id)
    {
        $comment = Comment::find($id);
        if ($comment) {
            return response()->json($comment);
        } else {
            return response()->json([
                "message" => "Comment not found"
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        if (Comment::where('id', $id)->exists()) {
            $comment = Comment::find($id);

            $comment->user_id = $request->has('user_id') ? $request->user_id : $comment->user_id;
            $comment->event_id = $request->has('event_id') ? $request->event_id : $comment->event_id;
            $comment->parent_comment_id = $request->has('parent_comment_id') ? $request->parent_comment_id : $comment->parent_comment_id;
            $comment->content = $request->has('content') ? $request->content : $comment->content;

            $comment->save();
            return response()->json([
                "message" => "Comment updated successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "Comment not found"
            ], 404);
        }
    }

    public function destroy($id)
    {
        if (Comment::where('id', $id)->exists()) {
            $comment = Comment::find($id);
            $comment->delete();
            return response()->json([
                "message" => "Comment deleted successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "Comment not found"
            ], 404);
        }
    }
}
