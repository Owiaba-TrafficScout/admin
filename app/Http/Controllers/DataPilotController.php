<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DataPilotController extends Controller
{
    /**
     * Display the DataPilot index page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return Inertia::render('DataPilot/Index');
    }
}
