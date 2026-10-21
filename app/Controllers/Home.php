<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('home', ['title' => 'Sun Son Solar']);
    }

    public function privacy(): string
    {
        return view('privacy', ['title' => 'Privacy Notice']);
    }
}
