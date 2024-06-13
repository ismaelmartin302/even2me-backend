<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with(["comments", "user", "likes"])->withCount(["comments", "likes"])->get();

        // Registro de un solo evento en los logs para depuración
        if ($events->isNotEmpty()) {
            $firstEvent = $events->first();
            error_log('Event ID: ' . $firstEvent->id);
            error_log('Event Name: ' . $firstEvent->name);
            // Agrega cualquier otra información que quieras registrar
        }

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $event = new Event();

        $event->user_id = $request->user_id;
        $event->name = $request->name;
        $event->description = $request->description;
        $event->location = $request->location;
        $event->price = $request->price;
        $event->capacity = $request->capacity;
        $event->current_attendees = $request->current_attendees;
        $event->category = $request->category;
        $event->picture = $request->picture;
        $event->website = $request->website;
        $event->starts_at = $request->starts_at;
        $event->finish_in = $request->finish_in;

        $event->save();
        return response()->json($event, 201);
    }

    public function show($id)
    {
        $event = Event::with(["comments.user", "user", "likes"])->withCount(["comments", "likes"])->find($id);
        if ($event) {
            return response()->json($event);
        } else {
            return response()->json([
                "message" => "Event not found"
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        if (Event::where('id', $id)->exists()) {
            $event = Event::find($id);

            $event->user_id = $request->has('user_id') ? $request->user_id : $event->user_id;
            $event->name = $request->has('name') ? $request->name : $event->name;
            $event->description = $request->has('description') ? $request->description : $event->description;
            $event->location = $request->has('location') ? $request->location : $event->location;
            $event->price = $request->has('price') ? $request->price : $event->price;
            $event->capacity = $request->has('capacity') ? $request->capacity : $event->capacity;
            $event->current_attendees = $request->has('current_attendees') ? $request->current_attendees : $event->current_attendees;
            $event->category = $request->has('category') ? $request->category : $event->category;
            $event->picture = $request->has('picture') ? $request->picture : $event->picture;
            $event->website = $request->has('website') ? $request->website : $event->website;
            $event->starts_at = $request->has('starts_at') ? $request->starts_at : $event->starts_at;
            $event->finish_in = $request->has('finish_in') ? $request->finish_in : $event->finish_in;

            $event->save();
            return response()->json([
                "message" => "Event updated successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "Event not found"
            ], 404);
        }
    }

    public function destroy($id)
    {
        $event = Event::find($id);
        error_log($event);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
    
        try {
            $event->delete();
            return response()->json(['message' => 'Event deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting event' . $e], 500);
        }
    }
    public function getEventComments($id)
    {
        $event = Event::with('comments.user')->find($id);
        if ($event) {
            return response()->json($event->comments);
        } else {
            return response()->json([
                "message" => "Event not found"
            ], 404);
        }
    }
}
