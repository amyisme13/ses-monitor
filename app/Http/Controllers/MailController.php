<?php

namespace App\Http\Controllers;

use App\Models\Mail;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $request->all(['field', 'search']);
        $mails = Mail::latest()
            ->filter($filters)
            ->cursorPaginate(10)
            ->appends($filters);

        return Inertia::render('Mails/Index', compact('mails', 'filters'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Mail  $mail
     * @return \Illuminate\Http\Response
     */
    public function show(Mail $mail)
    {
        //
    }
}
