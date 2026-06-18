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
#[Title('Edit Playlist')]
class PlaylistEdit extends Component
{
    public Playlist $playlist;
    public $name = '';
    public $selectedMedia = [];

    public function mount(Playlist $playlist)
    {
        $organizationId = Auth::user()->organization_id;

        if ($playlist->organization_id !== $organizationId) {
            abort(403);
        }

        $playlist->load('items.mediaAsset');

        $this->playlist = $playlist;
        $this->name = $playlist->name;
        
        $this->selectedMedia = $playlist->items->map(function($item) {
            return [
                'uid' => uniqid(),
                'id' => $item->media_asset_id,
                'name' => optional($item->mediaAsset)->name ?? 'Unknown',
                'type' => optional($item->mediaAsset)->type ?? 'unknown',
                'file_path' => optional($item->mediaAsset)->file_path ?? '',
                'duration' => $item->custom_duration
            ];
        })->toArray();
    }

    public function save(\App\Services\PlaylistService $playlistService)
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'selectedMedia' => 'required|array|min:1',
            'selectedMedia.*.id' => 'required|exists:media_assets,id',
            'selectedMedia.*.duration' => 'required|integer|min:1',
        ]);

        $this->authorize('update', $this->playlist);

        $playlistService->updatePlaylist($this->playlist, Auth::user(), [
            'name' => $this->name,
            'selectedMedia' => $this->selectedMedia,
        ]);

        session()->flash('success', 'Playlist updated successfully.');
        return $this->redirectRoute('app.playlists.index', navigate: true);
    }

    public function render()
    {
        $organizationId = Auth::user()->organization_id;
        $media = MediaAsset::where('organization_id', $organizationId)->latest()->get();

        return view('livewire.app.playlists.playlist-edit', [
            'media' => $media,
        ]);
    }
}
