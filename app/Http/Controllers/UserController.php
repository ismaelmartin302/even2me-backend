<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $user = new User();

        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        if ($request->has('nickname')) {
            $user->nickname = $request->nickname;
        }
        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }
        if ($request->has('biography')) {
            $user->biography = $request->biography;
        }
        if ($request->has('location')) {
            $user->location = $request->location;
        }
        if ($request->has('website')) {
            $user->website = $request->website;
        }
        if ($request->has('birthday')) {
            $user->birthday = $request->birthday;
        }
        if ($request->has('avatar')) {
            $user->avatar = $request->avatar;
        }
        if ($request->has('banner')) {
            $user->banner = $request->banner;
        }
        if ($request->has('type')) {
            $user->type = $request->type;
        }

        $user->save();
        return response()->json($user, 201);
    }

    public function show($id)
    {
        $user = User::find($id);
        if ($user) {
            return response()->json($user);
        } else {
            return response()->json([
                "message" => "User not found"
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        if (User::where('id', $id)->exists()) {
            $user = User::find($id);

            $user->username = $request->has('username') ? $request->username : $user->username;
            $user->nickname = $request->has('nickname') ? $request->nickname : $user->nickname;
            $user->email = $request->has('email') ? $request->email : $user->email;
            $user->phone = $request->has('phone') ? $request->phone : $user->phone;
            $user->biography = $request->has('biography') ? $request->biography : $user->biography;
            $user->location = $request->has('location') ? $request->location : $user->location;
            $user->website = $request->has('website') ? $request->website : $user->website;
            $user->birthday = $request->has('birthday') ? $request->birthday : $user->birthday;
            $user->avatar = $request->has('avatar') ? $request->avatar : $user->avatar;
            $user->banner = $request->has('banner') ? $request->banner : $user->banner;
            $user->type = $request->has('type') ? $request->type : $user->type;

            if ($request->has('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();
            return response()->json([
                "message" => "User updated successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "User not found"
            ], 404);
        }
    }

    public function destroy($id)
    {
        if (User::where('id', $id)->exists()) {
            $user = User::find($id);
            $user->delete();
            return response()->json([
                "message" => "User deleted successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "User not found"
            ], 404);
        }
    }
    public function showByUsername($username)
    {
        $user = User::where('username', $username)->first();
        if ($user) {
            return response()->json($user);
        } else {
            return response()->json([
                "message" => "User not found"
            ], 404);
        }
    }

    public function getUserEventsByUsername($username)
    {
        $user = User::where('username', $username)->first();
        if ($user) {
            $events = $user->events()->with(['comments', 'likes', 'reposts'])->get();
            return response()->json($events);
        } else {
            return response()->json([
                "message" => "User not found"
            ], 404);
        }
    }
    public function getUserFollowers($username)
    {
        $user = User::where('username', $username)->first();
        if ($user) {
            $followers = $user->followers()->with('follower')->get();
            return response()->json($followers);
        } else {
            return response()->json([
                "message" => "User not found"
            ], 404);
        }
    }

    public function getUserFollowings($username)
    {
        $user = User::where('username', $username)->first();
        if ($user) {
            $followings = $user->followings()->with('following')->get();
            return response()->json($followings);
        } else {
            return response()->json([
                "message" => "User not found"
            ], 404);
        }
    }
}
