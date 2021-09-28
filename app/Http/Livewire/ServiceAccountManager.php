<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class ServiceAccountManager extends Component
{
    public function fetchAll()
    {
        //dd('test');
        // $response = Http::timeout(3)->get(route('service-accounts.index'));
        // dd($response->status());
    }

    public function render()
    {
        return view('livewire.service-account-manager.base');
    }
}
