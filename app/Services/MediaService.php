<?php

namespace App\Services;

use App\Models\MediaAsset;
use App\Models\Organization;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class MediaService
{
    /**
     * @throws ValidationException
     */
    public function uploadMedia(Organization $organization, UploadedFile $file, \App\Services\BillingService $billingService): MediaAsset
    {
        $billingService->enforceStorageLimit($organization, $file->getSize());
        
        $dir = 'media/' . $organization->id;
        $path = $file->store($dir, 'public');
        $mimeType = $file->getClientMimeType();
        $type = str_starts_with($mimeType, 'video') ? 'video' : 'image';

        return MediaAsset::create([
            'organization_id' => $organization->id,
            'name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $mimeType,
            'size' => $file->getSize(),
            'type' => $type,
            'duration' => null,
        ]);
    }

    public function deleteMedia(MediaAsset $media): void
    {
        Storage::disk('public')->delete($media->file_path);
        $media->delete();
    }
}
