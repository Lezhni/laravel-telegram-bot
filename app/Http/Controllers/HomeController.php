<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function __invoke(string $alias = null)
    {
        return view('app');
    }
}