<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __invoke()
    {
        $script = 'home';

        return view('public.home', compact('script'));
    }
}
