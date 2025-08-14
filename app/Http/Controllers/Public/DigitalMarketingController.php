<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class DigitalMarketingController extends Controller
{
    public function __invoke()
    {
        $title = 'Digital Marketing';

        return view('public.digital-marketing', compact('title'));
    }
}
