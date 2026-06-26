<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationBell extends Component
{
    public $lastNotificationId = null;

    public function mount()
    {
        if (Auth::check()) {
            $latest = Auth::user()->unreadNotifications()->first();
            if ($latest) {
                $this->lastNotificationId = $latest->id;
            }
        }
    }

    public function checkNew()
    {
        if (!Auth::check()) {
            return;
        }

        $latest = Auth::user()->unreadNotifications()->first();
        if ($latest && $latest->id !== $this->lastNotificationId) {
            $this->lastNotificationId = $latest->id;
            
            // Enviamos un evento al navegador para la notificación nativa
            $this->dispatch('new-notification', [
                'title' => 'TaskCollab - ' . ($latest->data['sender_name'] ?? 'Notificación'),
                'body' => ($latest->data['message'] ?? '') . ' "' . ($latest->data['task_title'] ?? '') . '" en ' . ($latest->data['pagina_title'] ?? ''),
                'url' => $latest->data['pagina_id'] ? route('paginas.show', $latest->data['pagina_id']) : null
            ]);
        }
    }

    public function markAsRead($id)
    {
        if (!Auth::check()) {
            return;
        }

        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
    }

    public function markAllAsRead()
    {
        if (!Auth::check()) {
            return;
        }

        Auth::user()->unreadNotifications->markAsRead();
    }

    public function clearAll()
    {
        if (!Auth::check()) {
            return;
        }

        Auth::user()->notifications()->delete();
    }

    public function render()
    {
        $notifications = Auth::check() ? Auth::user()->notifications()->take(10)->get() : collect();
        $unreadCount = Auth::check() ? Auth::user()->unreadNotifications()->count() : 0;

        return view('livewire.notification-bell', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }
}
