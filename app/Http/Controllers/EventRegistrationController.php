<?php

namespace App\Http\Controllers;

use App\Models\EventRegistration;
use Illuminate\Http\Request;

class EventRegistrationController extends Controller
{
    public function show(Request $request)
    {
        $preselect = $request->query('event');

        return view('events.register', compact('preselect'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:150'],
            'email'        => ['required', 'email', 'max:200'],
            'phone'        => ['nullable', 'string', 'max:30'],
            'event'        => ['nullable', 'string', 'max:200'],
            'guests'       => ['nullable', 'integer', 'min:1', 'max:10'],
            'requirements' => ['nullable', 'string', 'max:1000'],
            'message'      => ['nullable', 'string', 'max:1000'],
        ]);

        EventRegistration::create(array_merge($validated, [
            'event'  => $validated['event'] ?? 'General interest — future events',
            'guests' => $validated['guests'] ?? 1,
        ]));

        return redirect()
            ->route('events.register')
            ->with('success', "You're registered! We'll be in touch with event details soon.");
    }
}
