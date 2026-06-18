<?php

namespace App\Livewire\Admin\Blogs;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\User;
use Illuminate\Support\Str;

class PostForm extends Component
{
    use WithFileUploads;

    public ?BlogPost $post = null;
    public $title = '';
    public $slug = '';
    public $excerpt = '';
    public $content = '';
    public $is_published = false;
    public $published_at = null;
    
    public $featured_image;
    public $existing_image;

    public $selected_categories = [];
    public $selected_tags = [];
    public $selected_authors = [];

    public function mount(?BlogPost $post = null)
    {
        $this->post = $post;
        if ($this->post && $this->post->exists) {
            $this->title = $this->post->title;
            $this->slug = $this->post->slug;
            $this->excerpt = $this->post->excerpt;
            $this->content = $this->post->content;
            $this->is_published = $this->post->is_published;
            $this->published_at = $this->post->published_at ? $this->post->published_at->format('Y-m-d\TH:i') : null;
            $this->existing_image = $this->post->featured_image;

            $this->selected_categories = $this->post->categories->pluck('id')->toArray();
            $this->selected_tags = $this->post->tags->pluck('id')->toArray();
            $this->selected_authors = $this->post->authors->pluck('id')->toArray();
        } else {
            $this->selected_authors = [auth()->id()];
            $this->is_published = false;
        }
    }

    public function updatedTitle()
    {
        if (empty($this->slug)) {
            $this->slug = Str::slug($this->title);
        }
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_posts,slug,' . ($this->post ? $this->post->id : 'NULL'),
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'is_published' => $this->is_published,
            'published_at' => $this->published_at,
        ];

        if ($this->featured_image) {
            $path = $this->featured_image->store('blog_images', 'public');
            $data['featured_image'] = $path;
        }

        if ($this->post && $this->post->exists) {
            $this->post->update($data);
            $post = $this->post;
        } else {
            $post = BlogPost::create($data);
        }

        $post->categories()->sync($this->selected_categories);
        $post->tags()->sync($this->selected_tags);
        $post->authors()->sync($this->selected_authors);

        session()->flash('success', 'Blog post saved successfully.');
        return redirect()->route('admin.blogs.index');
    }

    public function render()
    {
        return view('livewire.admin.blogs.post-form', [
            'categories' => BlogCategory::orderBy('name')->get(),
            'tags' => BlogTag::orderBy('name')->get(),
            'authors' => User::orderBy('name')->get(),
        ]);
    }
}
