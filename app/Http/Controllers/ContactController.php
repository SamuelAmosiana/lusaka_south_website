<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        // Both the home page and /contact post here; send people back to the form they used
        $back = fn() => redirect()->to(url()->previous())->withFragment('contact-form');

        // Honeypot: real visitors never see or fill this field
        if ($request->filled('website')) {
            return $back()->with('success', 'Thank you! Your message has been received.');
        }

        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:30',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:5000',
        ]);

        if ($validator->fails()) {
            return $back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        try {
            Mail::to(config('site.contact.email'))->send(new ContactMessage($data));
        } catch (\Throwable $e) {
            Log::error('Contact form email failed', ['error' => $e->getMessage(), 'from' => $data['email']]);

            return $back()->withInput()->with('contact_error', 'Sorry, we could not send your message right now. Please try again later or call us on ' . config('site.contact.phone') . '.');
        }

        return $back()->with('success', 'Thank you, ' . $data['name'] . '! Your message has been sent. We will get back to you within 2-3 business days.');
    }
}
