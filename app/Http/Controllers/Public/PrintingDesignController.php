<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class PrintingDesignController extends Controller
{
    public function __invoke()
    {
        return view('public.printing-design');
    }
}
