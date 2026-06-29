<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Page;

class WebController extends Controller
{
    public function home()
    {

        return view('web.home');
    }

    public function pricing()
    {
        $plans = Plan::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        return view('web.pricing', compact('plans'));
    }

    public function features()
    {
        return view('web.features');
    }

    public function page($slug)
    {
        $page = Page::where('slug', $slug)->where('is_published', true)->first();

        if (!$page) {
            // Fallback to static views if they exist
            if (view()->exists('web.' . $slug)) {
                return view('web.' . $slug);
            }
            abort(404);
        }

        return view('web.page', compact('page'));
    }

    public function blogs()
    {
        $posts = \App\Models\BlogPost::where('is_published', true)
            ->with(['authors', 'categories', 'tags'])
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('web.blogs', compact('posts'));
    }

    public function post($slug)
    {
        $post = \App\Models\BlogPost::where('slug', $slug)
            ->where('is_published', true)
            ->with(['authors', 'categories', 'tags'])
            ->firstOrFail();

        return view('web.post', compact('post'));
    }
}
