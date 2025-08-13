<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    //

    public function send(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email',
            'phone'      => 'nullable|string|max:20',
            'message'    => 'required|string',
        ]);

        // Send email
        Mail::to('help@graysonhopeinitiative.com')
            ->send(new ContactMessage($validated));

        return response()->json(['message' => 'Message sent successfully!']);
    }
}
