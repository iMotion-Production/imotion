<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class MobileApplicationController extends Controller
{
    public function __invoke()
    {
        $title = 'Mobile Application';

        return view('public.mobile-application', compact('title'));
    }
}
