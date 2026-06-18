<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Organization;
use App\Models\Screen;
use App\Models\MediaAsset;
use App\Models\PlatformAuditLog;

class Dashboard extends Component
{
    public function render()
    {
        $totalOrganizations = Organization::count();
        $activeScreens = Screen::where('status', 'online')->count();
        $offlineScreens = Screen::where('status', 'offline')->count();
        
        $storageBytes = MediaAsset::sum('size');
        $storageUsage = $this->formatBytes($storageBytes);

        $recentRegistrations = Screen::with('location.organization')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($screen) {
                return [
                    'id' => $screen->id,
                    'name' => $screen->name,
                    'organization' => $screen->location && $screen->location->organization ? $screen->location->organization->name : 'Unknown',
                    'date' => $screen->created_at->diffForHumans(),
                ];
            });

        $recentAuditEvents = PlatformAuditLog::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'action' => $log->action,
                    'user' => $log->user ? $log->user->name : 'System',
                    'date' => $log->created_at->diffForHumans(),
                ];
            });

        return view('livewire.admin.dashboard', [
            'stats' => [
                'total_organizations' => $totalOrganizations,
                'active_screens' => $activeScreens,
                'offline_screens' => $offlineScreens,
                'storage_usage' => $storageUsage,
            ],
            'recent_registrations' => $recentRegistrations,
            'recent_audit_events' => $recentAuditEvents,
        ]);
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
