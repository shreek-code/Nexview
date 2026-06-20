<?php

namespace App\Livewire\Shared;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationBell extends Component
{
    public function markAsRead($notificationId)
    {
        $user = Auth::guard('platform')->check() ? Auth::guard('platform')->user() : Auth::user();
        
        $notification = $user->notifications()->find($notificationId);
        
        if ($notification) {
            $notification->markAsRead();
            
            // Redirect based on the ticket_id in the data
            if (isset($notification->data['ticket_id'])) {
                $ticket = \App\Models\Ticket::find($notification->data['ticket_id']);
                
                if ($ticket) {
                    if (Auth::guard('platform')->check()) {
                        $this->redirect(route('admin.tickets.show', $ticket), navigate: true);
                    } else {
                        $this->redirect(route('app.support.show', $ticket), navigate: true);
                    }
                }
            }
        }
    }

    public function markAllAsRead()
    {
        $user = Auth::guard('platform')->check() ? Auth::guard('platform')->user() : Auth::user();
        $user->unreadNotifications->markAsRead();
    }

    public function render()
    {
        $user = Auth::guard('platform')->check() ? Auth::guard('platform')->user() : Auth::user();
        $notifications = $user->unreadNotifications()->take(10)->get();

        return view('livewire.shared.notification-bell', [
            'notifications' => $notifications,
            'unreadCount' => $user->unreadNotifications()->count(),
        ]);
    }
}
