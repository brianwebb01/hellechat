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
        return view('thread.index');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\thread $thread
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $phoneNumber)
    {
        return view('thread.show');
    }
}
