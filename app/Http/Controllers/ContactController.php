<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:150'],
            'email'   => ['required', 'email', 'max:200'],
            'subject' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        ContactMessage::create(array_merge($validated, ['type' => 'inquiry']));

        return redirect()
            ->to(route('contact') . '#contact-form')
            ->with('contact_success', 'Thank you — your message has been received. We\'ll be in touch within 48 hours.');
    }

    public function join(Request $request)
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:150'],
            'email'   => ['required', 'email', 'max:200'],
            'phone'   => ['nullable', 'string', 'max:30'],
            'country' => ['required', 'string', 'max:100'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        ContactMessage::create(array_merge($validated, ['type' => 'join']));

        return redirect()
            ->to(route('contact') . '#join')
            ->with('join_success', 'Welcome to the movement. We\'ll be in touch with next steps soon.');
    }
}
