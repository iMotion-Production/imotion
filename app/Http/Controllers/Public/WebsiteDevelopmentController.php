<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class WebsiteDevelopmentController extends Controller
{
    public function __invoke()
    {
        $title = 'Website Design & Development';

        return view('public.website-development', compact('title'));
    }
}
