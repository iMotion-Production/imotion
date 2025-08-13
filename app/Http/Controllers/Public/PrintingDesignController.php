<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class PrintingDesignController extends Controller
{
    public function __invoke()
    {
        $title = 'Printing Design';
        $script = 'printing-design';

        return view('public.printing-design', compact('title', 'script'));
    }
}
