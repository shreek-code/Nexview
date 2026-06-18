<?php

namespace App\Livewire\App\Media;

use App\Models\MediaAsset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
#[Title('Media Library')]
class MediaIndex extends Component
{
    use WithFileUploads;

    #[Validate('required|file|mimes:jpeg,png,jpg,gif,mp4,mov|max:102400')]
    public $file;

    public $isUploading = false;

    public $showUploadModal = false;

    public function updatedFile()
    {
        // Auto-validate when file is selected
        $this->validateOnly('file');
    }

    public function save(\App\Services\MediaService $mediaService, \App\Services\BillingService $billingService)
    {
        $this->validate();

        $this->authorize('create', MediaAsset::class);

        $mediaService->uploadMedia(Auth::user()->organization, $this->file, $billingService);

        $this->reset('file', 'showUploadModal');
        $this->dispatch('close-modal', 'upload-media-modal');
        session()->flash('success', 'Media uploaded successfully.');
    }

    public function delete(MediaAsset $media, \App\Services\MediaService $mediaService)
    {
        $organizationId = Auth::user()->organization_id;

        if ($media->organization_id !== $organizationId) {
            abort(403);
        }

        $mediaService->deleteMedia($media);

        session()->flash('success', 'Media deleted successfully.');
    }

    public function render()
    {
        $organizationId = Auth::user()->organization_id;
        $media = MediaAsset::where('organization_id', $organizationId)->latest()->get();

        return view('livewire.app.media.media-index', [
            'media' => $media,
        ]);
    }
}
