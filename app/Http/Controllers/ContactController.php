<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        // Validation of data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ], [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be valid',
            'subject.required' => 'Subject is required',
            'message.required' => 'Message is required',
        ]);

        try {
            // Sending email
            Mail::to(config('mail.to_address'))
                ->send(new ContactMail($validated));

            return redirect()->back()->with('success', 'Your message has been sent successfully! We will reply to you as soon as possible.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while sending the message. Please try again later.');
        }
    }
}