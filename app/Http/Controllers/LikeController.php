<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class LikeController extends Controller
{
    public function like(Request $request, Event $event)
    {
        $userId = $request->input('user_id'); // Obtener el ID del usuario desde la solicitud

        if (!$event->likes()->where('user_id', $userId)->exists()) {
            $event->likes()->attach($userId);
        }
        
        return response()->json(['message' => 'Event liked']);
    }

    public function unlike(Request $request, Event $event)
    {
        $userId = $request->input('user_id'); // Obtener el ID del usuario desde la solicitud

        if ($event->likes()->where('user_id', $userId)->exists()) {
            $event->likes()->detach($userId);
        }
        
        return response()->json(['message' => 'Event unliked']);
    }
}



