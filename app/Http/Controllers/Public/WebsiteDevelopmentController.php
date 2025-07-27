<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class WebsiteDevelopmentController extends Controller
{
    public function __invoke()
    {
        return view('public.website-development');
    }
}
