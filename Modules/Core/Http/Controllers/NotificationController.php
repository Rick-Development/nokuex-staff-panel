<?php

namespace Modules\Core\Http\Controllers;

use Modules\Core\Entities\Notification;
use Modules\Core\Entities\Staff;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('staff')->get();
        return view('core::notification.index', compact('notifications'));
    }

    public function create()
    {
        $staffs = Staff::where('is_active', true)->get();
        return view('core::notification.create', compact('staffs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'staff_id' => 'required|exists:staffs,id',
        ]);

        Notification::create([
            'type' => $request->type,
            'title' => $request->title,
            'message' => $request->message,
            'data' => $request->data ?? [],
            'staff_id' => $request->staff_id,
        ]);

        return redirect()->route('core.notification.index')->with('success', 'Notification created successfully.');
    }

    public function show(Notification $notification)
    {
        return view('core::notification.show', compact('notification'));
    }

    public function edit(Notification $notification)
    {
        $staffs = Staff::where('is_active', true)->get();
        return view('core::notification.edit', compact('notification', 'staffs'));
    }

    public function update(Request $request, Notification $notification)
    {
        $request->validate([
            'type' => 'required|string',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'staff_id' => 'required|exists:staffs,id',
        ]);

        $notification->update([
            'type' => $request->type,
            'title' => $request->title,
            'message' => $request->message,
            'data' => $request->data ?? [],
            'staff_id' => $request->staff_id,
            'read_at' => $request->has('mark_read') ? now() : null,
        ]);

        return redirect()->route('core.notification.index')->with('success', 'Notification updated successfully.');
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();
        return redirect()->route('core.notification.index')->with('success', 'Notification deleted successfully.');
    }

    public function markAsRead(Notification $notification)
    {
        $notification->update(['read_at' => now()]);
        return back()->with('success', 'Notification marked as read.');
    }
}