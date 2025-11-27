<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of services
     */
    public function index()
    {
        // TODO: Get services from database when content_services table is created
        return view('public.services.index');
    }

    /**
     * Display the specified service
     */
    public function show(string $slug)
    {
        // TODO: Get service from database by slug
        return view('public.services.show', ['slug' => $slug]);
    }
}