<?php

namespace App\Livewire\Admin\Pages;

use Livewire\Component;
use App\Models\Page;

class PageIndex extends Component
{
    public function render()
    {
        $pages = Page::orderBy('title', 'asc')->get();
        return view('livewire.admin.pages.page-index', compact('pages'));
    }
}
