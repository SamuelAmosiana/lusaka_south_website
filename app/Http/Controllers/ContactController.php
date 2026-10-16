<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:30',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        // Log for development; switch MAIL_MAILER to smtp in .env for production
        Log::info('Contact Form Submission', [
            'from'    => $data['email'],
            'name'    => $data['name'],
            'subject' => $data['subject'],
            'message' => $data['message'],
        ]);

        // Mail::to(config('site.admissions_email'))->send(new \App\Mail\ContactMail($data));

        return back()->with('success', 'Thank you, ' . $data['name'] . '! Your message has been received. We will get back to you within 2-3 business days.');
    }
}
