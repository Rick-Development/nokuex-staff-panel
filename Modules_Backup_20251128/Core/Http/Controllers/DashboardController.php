<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Core\Entities\Staff;
use Modules\Core\Entities\Role;
use Modules\Core\Entities\Notification;
use Modules\Chat\Entities\ChatChannel;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_staff' => Staff::count(),
            'total_roles' => Role::count(),
            'unread_notifications' => Notification::whereNull('read_at')->count(),
            'active_chats' => ChatChannel::where('is_active', true)->count(),
        ];

        $recent_staff = Staff::with('role')->latest()->take(5)->get();
        $recent_notifications = Notification::with('staff')->latest()->take(5)->get();

        return view('core::dashboard', compact('stats', 'recent_staff', 'recent_notifications'));
    }
}