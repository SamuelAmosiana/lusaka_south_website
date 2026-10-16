<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApplicationController extends Controller
{
    public function undergraduate()
    {
        return view('apply.undergraduate');
    }

    public function submitUndergraduate(Request $request)
    {
        $data = $request->validate([
            'firstname'        => 'required|string|max:100',
            'lastname'         => 'required|string|max:100',
            'email'            => 'required|email|max:255',
            'phone'            => 'required|string|max:30',
            'dob'              => 'required|date',
            'gender'           => 'required|in:male,female,other',
            'address'          => 'required|string',
            'program'          => 'required|string',
            'intake'           => 'required|string',
            'mode_of_study'    => 'required|string',
            'qualification'    => 'required|string',
        ]);

        $applicationId = 'LSUC-UG-' . strtoupper(substr(uniqid(), -8));
        Log::info('Undergraduate Application', array_merge($data, ['application_id' => $applicationId]));

        return back()->with('success', "Application submitted! Your Application ID is: <strong>{$applicationId}</strong>. You will receive a confirmation email within 2-3 business days.");
    }

    public function shortCourses()
    {
        return view('apply.short-courses');
    }

    public function submitShortCourse(Request $request)
    {
        $data = $request->validate([
            'firstname' => 'required|string|max:100',
            'lastname'  => 'required|string|max:100',
            'email'     => 'required|email|max:255',
            'phone'     => 'required|string|max:30',
            'course'    => 'required|string',
            'startdate' => 'required|string',
            'schedule'  => 'required|string',
        ]);

        $applicationId = 'LSUC-SC-' . strtoupper(substr(uniqid(), -8));
        Log::info('Short Course Application', array_merge($data, ['application_id' => $applicationId]));

        return back()->with('success', "Enrollment request submitted! Your Application ID is: <strong>{$applicationId}</strong>.");
    }

    public function corporateTraining()
    {
        return view('apply.corporate-training');
    }

    public function submitCorporateTraining(Request $request)
    {
        $data = $request->validate([
            'company'       => 'required|string|max:255',
            'industry'      => 'required|string',
            'companysize'   => 'required|string',
            'address'       => 'required|string',
            'contactname'   => 'required|string|max:255',
            'position'      => 'required|string|max:255',
            'email'         => 'required|email|max:255',
            'phone'         => 'required|string|max:30',
            'trainingtype'  => 'required|string',
            'participants'  => 'required|integer|min:1',
            'duration'      => 'required|string',
            'location'      => 'required|string',
            'specificneeds' => 'required|string',
        ]);

        $requestId = 'LSUC-CT-' . strtoupper(substr(uniqid(), -8));
        Log::info('Corporate Training Request', array_merge($data, ['request_id' => $requestId]));

        return back()->with('success', "Training proposal request submitted! Your Reference ID is: <strong>{$requestId}</strong>. Our team will contact you within 2-3 business days.");
    }

    public function paymentInstructions()
    {
        return view('apply.payment');
    }

    public function extraCurricular()
    {
        return view('apply.extra-curricular');
    }
}
