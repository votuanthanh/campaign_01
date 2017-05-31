<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

class HomeController extends FrontendController
{
    public function index()
    {
        $this->view = 'home';

        return $this->viewRender();
    }
}
