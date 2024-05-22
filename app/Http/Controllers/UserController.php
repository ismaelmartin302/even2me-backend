<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
        $user->nickname = $request->nickname;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->biography = $request->biography;
        $user->location = $request->location;
        $user->website = $request->website;
        $user->birthday = $request->birthday;
        $user->avatar = $request->avatar;
        $user->banner = $request->banner;
        $user->type = $request->type;
        $user->password = $request->password;
        $user->save();
        return response()->json();
    }

    public function show($id) 
    {
        $user = User::find($id);
        if(!empty($user))
        {
            return response()->json($user);
        }
        else
        {
            return response()->json([
                "message" => "User not found"
            ], 404);
        }
    }

    public function update(Request $request, $id) 
    {
        if (User::where('id', $id)->exists()) {
            $user = User::find($id);
            $user->username = is_null($request->username) ? $user->username : $request->username;
            $user->nickname = is_null($request->nickname) ? $user->nickname : $request->nickname;
            $user->email = is_null($request->email) ? $user->email : $request->email;
            $user->phone = is_null($request->phone) ? $user->phone : $request->phone;
            $user->biography = is_null($request->biography) ? $user->biography : $request->biography;
            $user->location = is_null($request->location) ? $user->location : $request->location;
            $user->website = is_null($request->website) ? $user->website : $request->website;
            $user->birthday = is_null($request->birthday) ? $user->birthday : $request->birthday;
            $user->avatar = is_null($request->avatar) ? $user->avatar : $request->avatar;
            $user->banner = is_null($request->banner) ? $user->banner : $request->banner;
            $user->type = is_null($request->type) ? $user->type : $request->type;
            $user->password = is_null($request->password) ? $user->password : $request->password;
            $user->save();
            return response()->json([
                "message" => "User updated successfully"
            ], 200);
        }
        else 
        {
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
        }
        else
        {
            return response()->json([
                "message" => "User not found"
            ], 404);
        }
                        
    }
}
