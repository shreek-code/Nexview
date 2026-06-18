<?php

namespace App\Livewire\Admin\AuditLogs;

use Livewire\Component;

use Livewire\WithPagination;
use App\Models\PlatformAuditLog;

class AuditLogIndex extends Component
{
    use WithPagination;

    public function render()
    {
        $logs = PlatformAuditLog::with(['platformUser', 'organization'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.admin.audit-logs.audit-log-index', compact('logs'));
    }
}
