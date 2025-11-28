<?php

namespace Modules\Chat\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Chat\Entities\ChatMessage;
use Modules\Core\Entities\Staff;

class ChatController extends Controller
{
    private function getConversations($currentStaffId)
    {
        return Staff::where('id', '!=', $currentStaffId)
            ->where('is_active', true)
            ->get()
            ->map(function ($staff) use ($currentStaffId) {
                $lastMessage = ChatMessage::where(function($q) use ($currentStaffId, $staff) {
                    $q->where('sender_id', $currentStaffId)->where('receiver_id', $staff->id);
                })->orWhere(function($q) use ($currentStaffId, $staff) {
                    $q->where('sender_id', $staff->id)->where('receiver_id', $currentStaffId);
                })->latest()->first();
                
                $unreadCount = ChatMessage::where('sender_id', $staff->id)
                    ->where('receiver_id', $currentStaffId)
                    ->where('is_read', false)
                    ->count();
                
                return [
                    'staff' => $staff,
                    'last_message' => $lastMessage,
                    'unread_count' => $unreadCount,
                ];
            })
            ->sortByDesc(function ($conversation) {
                return $conversation['last_message'] ? $conversation['last_message']->created_at : null;
            });
    }

    public function index()
    {
        $currentStaffId = Auth::guard('staff')->id();
        
        // Get general channel messages (visible to all)
        $generalMessages = ChatMessage::whereNotNull('channel')
            ->where('channel', 'general')
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get()
            ->reverse();
        
        // Get 1-to-1 conversations
        $conversations = $this->getConversations($currentStaffId);
        
        return view('chat::index', compact('conversations', 'generalMessages'));
    }

    public function show($staffId)
    {
        $currentStaffId = Auth::guard('staff')->id();
        $otherStaff = Staff::findOrFail($staffId);
        
        // Get all conversations for the sidebar
        $conversations = $this->getConversations($currentStaffId);
        
        // Mark messages as read
        ChatMessage::where('sender_id', $staffId)
            ->where('receiver_id', $currentStaffId)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
        
        // Get messages
        $messages = ChatMessage::where(function($q) use ($currentStaffId, $staffId) {
            $q->where('sender_id', $currentStaffId)->where('receiver_id', $staffId);
        })->orWhere(function($q) use ($currentStaffId, $staffId) {
            $q->where('sender_id', $staffId)->where('receiver_id', $currentStaffId);
        })->with(['sender', 'receiver'])
          ->orderBy('created_at', 'asc')
          ->get();
        
        return view('chat::show', compact('otherStaff', 'messages', 'conversations'));
    }

    public function send(Request $request, $staffId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        ChatMessage::create([
            'sender_id' => Auth::guard('staff')->id(),
            'receiver_id' => $staffId,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Message sent!');
    }

    public function sendToGeneral(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        ChatMessage::create([
            'sender_id' => Auth::guard('staff')->id(),
            'receiver_id' => null,
            'channel' => 'general',
            'message' => $request->message,
        ]);

        return back()->with('success', 'Message sent to General channel!');
    }

    public function getChatView($staffId)
    {
        $currentStaffId = Auth::guard('staff')->id();
        $otherStaff = Staff::findOrFail($staffId);
        
        // Mark messages as read
        ChatMessage::where('sender_id', $staffId)
            ->where('receiver_id', $currentStaffId)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
        
        // Get messages
        $messages = ChatMessage::where(function($q) use ($currentStaffId, $staffId) {
            $q->where('sender_id', $currentStaffId)->where('receiver_id', $staffId);
        })->orWhere(function($q) use ($currentStaffId, $staffId) {
            $q->where('sender_id', $staffId)->where('receiver_id', $currentStaffId);
        })->with(['sender', 'receiver'])
          ->orderBy('created_at', 'asc')
          ->get();
        
        return view('chat::partials.chat-area', compact('otherStaff', 'messages'));
    }

    public function getMessages(Request $request, $staffId)
    {
        $currentStaffId = Auth::guard('staff')->id();
        $afterId = $request->query('after_id');
        
        $query = ChatMessage::where(function($q) use ($currentStaffId, $staffId) {
            $q->where('sender_id', $currentStaffId)->where('receiver_id', $staffId);
        })->orWhere(function($q) use ($currentStaffId, $staffId) {
            $q->where('sender_id', $staffId)->where('receiver_id', $currentStaffId);
        });

        if ($afterId) {
            $query->where('id', '>', $afterId);
        }

        $messages = $query->with(['sender', 'receiver'])
          ->orderBy('created_at', 'asc')
          ->get();
        
        // Mark as read
        if ($messages->isNotEmpty()) {
            ChatMessage::where('sender_id', $staffId)
                ->where('receiver_id', $currentStaffId)
                ->where('is_read', false)
                ->whereIn('id', $messages->pluck('id'))
                ->update(['is_read' => true, 'read_at' => now()]);
        }
        
        return response()->json($messages);
    }

    public function getGeneralMessages(Request $request)
    {
        $afterId = $request->query('after_id');
        
        $query = ChatMessage::whereNotNull('channel')
            ->where('channel', 'general');

        if ($afterId) {
            $query->where('id', '>', $afterId);
        }

        $messages = $query->with('sender')
          ->orderBy('created_at', 'asc')
          ->get();
        
        return response()->json($messages);
    }
}
