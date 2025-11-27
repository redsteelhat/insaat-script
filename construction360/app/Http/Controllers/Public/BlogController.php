<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of blog posts
     */
    public function index(Request $request)
    {
        // TODO: Get blog posts from database when blog_posts table is created
        return view('public.blog.index');
    }

    /**
     * Display the specified blog post
     */
    public function show(string $slug)
    {
        // TODO: Get blog post from database by slug
        return view('public.blog.show', ['slug' => $slug]);
    }
}