<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use Illuminate\Http\Request;

class FollowerController extends Controller
{
    public function index()
    {
        $followers = Follower::all();
        return response()->json($followers);
    }

    public function store(Request $request)
    {
        $follower = new Follower();

        $follower->follower_id = $request->follower_id;
        $follower->following_id = $request->following_id;

        $follower->save();
        return response()->json($follower, 201);
    }

    public function show($id)
    {
        $follower = Follower::find($id);
        if ($follower) {
            return response()->json($follower);
        } else {
            return response()->json([
                "message" => "Follower not found"
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        if (Follower::where('id', $id)->exists()) {
            $follower = Follower::find($id);

            $follower->follower_id = $request->has('follower_id') ? $request->follower_id : $follower->follower_id;
            $follower->following_id = $request->has('following_id') ? $request->following_id : $follower->following_id;

            $follower->save();
            return response()->json([
                "message" => "Follower updated successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "Follower not found"
            ], 404);
        }
    }

    public function destroy($id)
    {
        if (Follower::where('id', $id)->exists()) {
            $follower = Follower::find($id);
            $follower->delete();
            return response()->json([
                "message" => "Follower deleted successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "Follower not found"
            ], 404);
        }
    }
}
