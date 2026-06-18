<?php

namespace App\Livewire\Admin\Blogs;

use Livewire\Component;
use App\Models\BlogPost;

class PostIndex extends Component
{
    public function deletePost($id)
    {
        BlogPost::find($id)->delete();
        session()->flash('success', 'Blog post deleted successfully.');
    }

    public function render()
    {
        $posts = BlogPost::with('authors', 'categories')->orderBy('created_at', 'desc')->get();
        return view('livewire.admin.blogs.post-index', compact('posts'));
    }
}
