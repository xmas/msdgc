<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventUserController extends Controller
{
    /**
     * Attach a user to an event with optional per-event attributes
     */
    public function attach(Request $request, Event $event)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'attrs' => 'nullable|string'
        ]);

        $user = User::findOrFail($request->user_id);

        // Check if user is already attached to this event
        if ($event->users()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'User is already attached to this event'], 400);
        }

        $attrs = null;
        if ($request->attrs) {
            $decoded = json_decode($request->attrs, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Invalid JSON format'], 400);
            }
            $attrs = json_encode($decoded);
        }

        $event->users()->attach($user->id, [
            'attrs' => $attrs
        ]);

        return response()->json(['message' => 'User attached to event successfully']);
    }

    /**
     * Detach a user from an event
     */
    public function detach(Event $event, User $user)
    {
        if (!$event->users()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'User is not attached to this event'], 400);
        }

        $event->users()->detach($user->id);

        return response()->json(['message' => 'User detached from event successfully']);
    }

    /**
     * Update per-event attributes for a user
     */
    public function updateAttributes(Request $request, Event $event, User $user)
    {
        $request->validate([
            'attrs' => 'nullable|string'
        ]);

        if (!$event->users()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'User is not attached to this event'], 400);
        }

        $attrs = null;
        if ($request->attrs) {
            $decoded = json_decode($request->attrs, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Invalid JSON format'], 400);
            }
            $attrs = json_encode($decoded);
        }

        $event->users()->updateExistingPivot($user->id, [
            'attrs' => $attrs
        ]);

        return response()->json(['message' => 'User attributes updated successfully']);
    }

    /**
     * Get all users for an event
     */
    public function getUsers(Event $event)
    {
        $users = $event->users()->withPivot('attrs', 'created_at', 'updated_at')->get();

        // Parse pivot attrs from JSON strings to objects
        $users->transform(function ($user) {
            if ($user->pivot->attrs) {
                $user->pivot->attrs = json_decode($user->pivot->attrs, true);
            }
            return $user;
        });

        return response()->json($users);
    }

    /**
     * Get all events for a user
     */
    public function getUserEvents(User $user)
    {
        $events = $user->events()->withPivot('attrs', 'created_at', 'updated_at')->get();

        // Parse pivot attrs from JSON strings to objects
        $events->transform(function ($event) {
            if ($event->pivot->attrs) {
                $event->pivot->attrs = json_decode($event->pivot->attrs, true);
            }
            return $event;
        });

        return response()->json($events);
    }
}
