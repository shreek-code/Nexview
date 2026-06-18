<?php

namespace App\Livewire\App\Playlists;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Playlist;
use App\Models\PlaylistItem;
use App\Models\MediaAsset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.app')]
#[Title('Create Playlist')]
class PlaylistCreate extends Component
{
    public $name = '';
    
    // We'll bind the Alpine selected items to this property
    public $selectedMedia = [];

    public function save(\App\Services\PlaylistService $playlistService)
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'selectedMedia' => 'required|array|min:1',
            'selectedMedia.*.id' => 'required|exists:media_assets,id',
            'selectedMedia.*.duration' => 'required|integer|min:1',
        ]);

        $this->authorize('create', \App\Models\Playlist::class);

        $playlistService->createPlaylist(Auth::user(), [
            'name' => $this->name,
            'selectedMedia' => $this->selectedMedia,
        ]);

        session()->flash('success', 'Playlist created successfully.');
        return $this->redirectRoute('app.playlists.index', navigate: true);
    }

    public function render()
    {
        $organizationId = Auth::user()->organization_id;
        $media = MediaAsset::where('organization_id', $organizationId)->latest()->get();

        return view('livewire.app.playlists.playlist-create', [
            'media' => $media,
        ]);
    }
}
