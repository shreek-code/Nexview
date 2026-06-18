<?php

namespace App\Livewire\App\Playlists;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Playlist;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
#[Title('Playlists')]
class PlaylistIndex extends Component
{
    public function delete(Playlist $playlist)
    {
        $organizationId = Auth::user()->organization_id;
        
        if ($playlist->organization_id !== $organizationId) {
            abort(403);
        }

        $playlist->delete();

        session()->flash('success', 'Playlist deleted successfully.');
    }

    public function render()
    {
        $organizationId = Auth::user()->organization_id;
        $playlists = Playlist::with('items.mediaAsset')->where('organization_id', $organizationId)->latest()->get();

        return view('livewire.app.playlists.playlist-index', [
            'playlists' => $playlists,
        ]);
    }
}
