<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class GraphicDesignController extends Controller
{
    public function __invoke()
    {
        return view('public.graphic-design');
    }
}
