<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class DigitalMarketingController extends Controller
{
    public function __invoke()
    {
        return view('public.digital-marketing');
    }
}
