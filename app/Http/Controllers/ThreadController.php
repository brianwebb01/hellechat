<?php

namespace App\Http\Controllers;

use App\thread;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        session(['responsive-nav-heading' => 'Messages']);

        return view('thread-manager.base');
    }
}
