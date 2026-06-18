<?php

namespace App\Livewire\Admin\Pages;

use Livewire\Component;
use App\Models\Page;
use Illuminate\Support\Str;

class PageForm extends Component
{
    public ?Page $page = null;

    public $title;
    public $slug;
    public $content;
    public $meta_description;
    public $is_published = false;

    public function mount(?Page $page = null)
    {
        if ($page && $page->exists) {
            $this->page = $page;
            $this->title = $page->title;
            $this->slug = $page->slug;
            $this->content = $page->content;
            $this->meta_description = $page->meta_description;
            $this->is_published = $page->is_published;
        }
    }

    public function updatedTitle($value)
    {
        if (!$this->page || !$this->page->exists) {
            $this->slug = Str::slug($value);
        }
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . ($this->page ? $this->page->id : 'NULL'),
            'content' => 'nullable|string',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ]);

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'meta_description' => $this->meta_description,
            'is_published' => $this->is_published,
        ];

        if ($this->page && $this->page->exists) {
            $this->page->update($data);
            session()->flash('success', 'Page updated successfully.');
        } else {
            Page::create($data);
            session()->flash('success', 'Page created successfully.');
        }

        return redirect()->route('admin.pages.index');
    }

    public function render()
    {
        return view('livewire.admin.pages.page-form');
    }
}
