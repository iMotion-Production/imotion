<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class AboutController extends Controller
{
    public function __invoke()
    {
        $title = 'About';

        return view('public.about', compact('title'));
    }
}
