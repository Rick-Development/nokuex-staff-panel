<?php

namespace Modules\Chat\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Chat\Entities\ChatChannel;
use Modules\Chat\Entities\ChatMessage;
use Modules\Chat\Entities\ChannelMember;
use Modules\Core\Entities\Staff;

class ChatController extends Controller
{
    public function index()
    {
        $channels = ChatChannel::where('is_active', true)->get();
        return view('chat::index', compact('channels'));
    }

    public function showChannel($channelId)
    {
        $channel = ChatChannel::with(['messages.staff', 'members.staff'])->findOrFail($channelId);
        $messages = $channel->messages()->orderBy('created_at', 'asc')->get();
        return view('chat::channel', compact('channel', 'messages'));
    }

    public function sendMessage(Request $request, $channelId)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $message = ChatMessage::create([
            'channel_id' => $channelId,
            'staff_id' => auth()->id(),
            'message' => $request->message,
        ]);

        // Broadcast the message event (placeholder for Pusher)
        // broadcast(new NewChatMessage($message))->toOthers();

        return response()->json($message->load('staff'));
    }

    public function createChannel(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'is_private' => 'boolean',
        ]);

        $channel = ChatChannel::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_private' => $request->is_private ?? false,
            'created_by' => auth()->id(),
        ]);

        // Add the creator as an admin member
        ChannelMember::create([
            'channel_id' => $channel->id,
            'staff_id' => auth()->id(),
            'is_admin' => true,
            'joined_at' => now(),
        ]);

        return redirect()->route('chat.channel.show', $channel->id);
    }

    public function addMember(Request $request, $channelId)
    {
        $request->validate([
            'staff_id' => 'required|exists:staffs,id',
        ]);

        $member = ChannelMember::create([
            'channel_id' => $channelId,
            'staff_id' => $request->staff_id,
            'joined_at' => now(),
        ]);

        return response()->json($member->load('staff'));
    }
}