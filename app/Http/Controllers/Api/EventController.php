<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::select(['id', 'name', 'event_group', 'attrs', 'user_attrs', 'created_at'])
                      ->orderBy('created_at', 'desc')
                      ->get();

        return response()->json($events);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'event_group' => ['required', 'string', 'max:255'],
            'attrs' => ['nullable', 'string'],
            'user_attrs' => ['nullable', 'string'],
        ]);

        // Parse attrs JSON if provided
        if ($validated['attrs']) {
            $decoded = json_decode($validated['attrs'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['message' => 'Invalid JSON format in attrs'], 400);
            }
            $validated['attrs'] = $decoded;
        }

        // Parse user_attrs JSON if provided
        if ($validated['user_attrs']) {
            $decoded = json_decode($validated['user_attrs'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['message' => 'Invalid JSON format in user_attrs'], 400);
            }
            $validated['user_attrs'] = $decoded;
        }

        $event = Event::create($validated);

        return response()->json($event, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return response()->json($event);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'event_group' => ['required', 'string', 'max:255'],
            'attrs' => ['nullable', 'string'],
            'user_attrs' => ['nullable', 'string'],
        ]);

        // Parse attrs JSON if provided
        if ($validated['attrs']) {
            $decoded = json_decode($validated['attrs'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['message' => 'Invalid JSON format in attrs'], 400);
            }
            $validated['attrs'] = $decoded;
        }

        // Parse user_attrs JSON if provided
        if ($validated['user_attrs']) {
            $decoded = json_decode($validated['user_attrs'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['message' => 'Invalid JSON format in user_attrs'], 400);
            }
            $validated['user_attrs'] = $decoded;
        }

        $event->update($validated);

        return response()->json($event);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return response()->json(['message' => 'Event deleted successfully']);
    }
}
