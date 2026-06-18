<?php

namespace App\Livewire\App\Screens;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Screen;
use App\Models\MediaAsset;
use App\Services\ScreenService;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
#[Title('Screen Control Center')]
class ScreenShow extends Component
{
    public Screen $screen;
    public $volume;
    public $is_playing;
    public $activeTab = 'control'; // control, media

    public function mount(Screen $screen)
    {
        $this->authorize('view', $screen);
        $this->screen = $screen;
        $this->volume = $screen->volume;
        $this->is_playing = $screen->is_playing;
    }

    public function togglePlay(ScreenService $screenService)
    {
        $this->authorize('update', $this->screen);
        
        $this->is_playing = !$this->is_playing;
        $screenService->updatePlaybackSettings($this->screen, [
            'is_playing' => $this->is_playing,
        ]);
        
        session()->flash('success', $this->is_playing ? 'Playback resumed.' : 'Playback paused.');
    }

    public function updatedVolume($value, ScreenService $screenService)
    {
        $this->authorize('update', $this->screen);
        
        $vol = max(0, min(100, intval($value)));
        $this->volume = $vol;
        
        $screenService->updatePlaybackSettings($this->screen, [
            'volume' => $this->volume,
        ]);
    }

    public function setDefaultMedia($mediaId, ScreenService $screenService)
    {
        $this->authorize('update', $this->screen);
        
        $screenService->updatePlaybackSettings($this->screen, [
            'default_media_id' => $mediaId,
        ]);
        
        $this->screen->load('defaultMedia');
        $this->dispatch('close-modal');
        session()->flash('success', 'Default media updated successfully.');
    }

    public function setCurrentMedia($mediaId, ScreenService $screenService)
    {
        $this->authorize('update', $this->screen);
        
        $screenService->updatePlaybackSettings($this->screen, [
            'current_media_id' => $mediaId,
        ]);
        
        $this->screen->load('currentMedia');
        $this->dispatch('close-modal');
        session()->flash('success', 'Screen override media set successfully.');
    }

    public function clearOverride(ScreenService $screenService)
    {
        $this->authorize('update', $this->screen);
        
        $screenService->updatePlaybackSettings($this->screen, [
            'current_media_id' => null,
        ]);
        
        $this->screen->load('currentMedia');
        session()->flash('success', 'Override cleared. Falling back to default playlist/media.');
    }

    public function setOrientation($orientation)
    {
        $this->authorize('update', $this->screen);
        
        if (in_array($orientation, ['landscape', 'portrait'])) {
            $this->screen->update(['orientation' => $orientation]);
            session()->flash('success', 'Screen orientation updated to ' . ucfirst($orientation) . '.');
        }
    }

    public function delete(ScreenService $screenService)
    {
        $this->authorize('delete', $this->screen);
        
        $screenService->deleteScreen($this->screen);
        
        session()->flash('success', 'Screen deleted successfully.');
        return $this->redirectRoute('app.screens.index', navigate: true);
    }

    public function render()
    {
        $organizationId = Auth::user()->organization_id;
        $media = MediaAsset::where('organization_id', $organizationId)->latest()->get();

        return view('livewire.app.screens.screen-show', [
            'media' => $media,
        ]);
    }
}
