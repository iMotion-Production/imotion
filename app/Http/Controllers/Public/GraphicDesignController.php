<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class GraphicDesignController extends Controller
{
    public function __invoke()
    {
        $title = 'Graphic Design';

        return view('public.graphic-design', compact('title'));
    }
}
