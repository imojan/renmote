<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $notifications = $user->notifications()
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('notifications.index', [
            'notifications' => $notifications,
            'unreadCount' => $user->unreadNotifications()->count(),
            'isFrontLayout' => $user->role === 'user',
        ]);
    }

    public function show(Request $request, DatabaseNotification $notification): View
    {
        $user = $request->user();
        $this->authorizeNotification($notification, $user->id);

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        return view('notifications.show', [
            'notification' => $notification,
            'isFrontLayout' => $user->role === 'user',
        ]);
    }

    public function markAsRead(Request $request, DatabaseNotification $notification): RedirectResponse
    {
        $this->authorizeNotification($notification, $request->user()->id);

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        return back();
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications()->update([
            'read_at' => now(),
        ]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    public function checkUnread(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['unreadCount' => 0, 'notifications' => []]);
        }

        $notifications = $user->notifications()->latest()->limit(5)->get()->map(function ($notification) {
            return [
                'id' => $notification->id,
                'data' => $notification->data,
                'created_at_human' => $notification->created_at->diffForHumans(),
                'read_at' => $notification->read_at,
                'url' => route('notifications.show', $notification->id),
            ];
        });

        return response()->json([
            'unreadCount' => $user->unreadNotifications()->count(),
            'notifications' => $notifications,
        ]);
    }

    private function authorizeNotification(DatabaseNotification $notification, int $userId): void
    {
        abort_unless(
            $notification->notifiable_type === 'App\\Models\\User'
            && (int) $notification->notifiable_id === $userId,
            403
        );
    }
}
