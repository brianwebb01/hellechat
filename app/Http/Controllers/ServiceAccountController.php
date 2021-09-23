<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiceAccountController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        session(['responsive-nav-heading' => 'Service Accounts']);
        return view('service_account.index');
    }
}
