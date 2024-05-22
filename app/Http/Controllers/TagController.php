<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::all();
        return response()->json($tags);
    }

    public function store(Request $request)
    {
        $tag = new Tag();
        $tag->name = $request->name;

        $tag->save();
        return response()->json($tag, 201);
    }

    public function show($id)
    {
        $tag = Tag::find($id);
        if ($tag) {
            return response()->json($tag);
        } else {
            return response()->json([
                "message" => "Tag not found"
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        if (Tag::where('id', $id)->exists()) {
            $tag = Tag::find($id);
            $tag->name = $request->has('name') ? $request->name : $tag->name;

            $tag->save();
            return response()->json([
                "message" => "Tag updated successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "Tag not found"
            ], 404);
        }
    }

    public function destroy($id)
    {
        if (Tag::where('id', $id)->exists()) {
            $tag = Tag::find($id);
            $tag->delete();
            return response()->json([
                "message" => "Tag deleted successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "Tag not found"
            ], 404);
        }
    }
}
