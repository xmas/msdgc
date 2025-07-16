<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::with(['users' => function ($query) {
            $query->select(['users.id', 'users.name', 'users.email']);
        }])->orderBy('created_at', 'desc')->paginate(15);

        return Inertia::render('Events/Index', [
            'events' => $events
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Events/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'event_group' => ['required', 'string', 'max:255'],
            'attrs' => ['nullable', 'json'],
        ]);

        // Parse attrs JSON if provided
        if ($validated['attrs']) {
            $validated['attrs'] = json_decode($validated['attrs'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors(['attrs' => 'Invalid JSON format.'])->withInput();
            }
        }

        Event::create($validated);

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load(['users' => function ($query) {
            $query->select(['users.id', 'users.name', 'users.email'])
                  ->withPivot('attrs', 'created_at');
        }]);

        // Get all users who are not already part of this event
        $availableUsers = \App\Models\User::select(['id', 'name', 'email'])
            ->whereNotIn('id', $event->users->pluck('id'))
            ->orderBy('name')
            ->get();

        return Inertia::render('Events/Show', [
            'event' => $event,
            'availableUsers' => $availableUsers
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        return Inertia::render('Events/Edit', [
            'event' => $event
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'event_group' => ['required', 'string', 'max:255'],
            'attrs' => ['nullable', 'json'],
        ]);

        // Parse attrs JSON if provided
        if ($validated['attrs']) {
            $validated['attrs'] = json_decode($validated['attrs'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors(['attrs' => 'Invalid JSON format.'])->withInput();
            }
        }

        $event->update($validated);

        return redirect()->route('events.index')
            ->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully.');
    }

    /**
     * Add a user to an event
     */
    public function addUser(Request $request, Event $event)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'attrs' => 'nullable|json'
        ]);

        $userId = $request->user_id;

        // Check if user is already part of this event
        if ($event->users()->where('user_id', $userId)->exists()) {
            return back()->withErrors(['user_id' => 'User is already part of this event.']);
        }

        $attrs = null;
        if ($request->attrs) {
            $attrs = json_decode($request->attrs, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors(['attrs' => 'Invalid JSON format.']);
            }
        }

        $event->users()->attach($userId, [
            'attrs' => $attrs
        ]);

        return redirect()->route('events.show', $event)
            ->with('success', 'User added to event successfully.');
    }

    /**
     * Remove a user from an event
     */
    public function removeUser(Event $event, $userId)
    {
        if (!$event->users()->where('user_id', $userId)->exists()) {
            return back()->withErrors(['error' => 'User is not part of this event.']);
        }

        $event->users()->detach($userId);

        return redirect()->route('events.show', $event)
            ->with('success', 'User removed from event successfully.');
    }
}
