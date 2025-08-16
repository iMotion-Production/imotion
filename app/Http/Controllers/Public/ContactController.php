<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    public function __invoke()
    {
        $title = 'Contact';
        $script = 'contact';

        return view('public.contact', compact('title', 'script'));
    }
}
