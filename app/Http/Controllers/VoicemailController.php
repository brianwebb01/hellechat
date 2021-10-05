<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VoicemailController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        session(['responsive-nav-heading' => 'Voicemail']);
        return view('voicemail-manager.base');
    }
}
