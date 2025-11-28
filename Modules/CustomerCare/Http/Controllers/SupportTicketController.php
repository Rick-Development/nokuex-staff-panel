<?php

namespace Modules\CustomerCare\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\CustomerCare\Entities\SupportTicket;
use Modules\CustomerCare\Entities\TicketReply;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $query = SupportTicket::with(['user', 'assignedStaff']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $tickets = $query->latest()->paginate(15);

        return view('customercare::tickets.index', compact('tickets'));
    }

    public function show($id)
    {
        $ticket = SupportTicket::with(['user', 'replies.user', 'replies.staff'])->findOrFail($id);
        return view('customercare::tickets.show', compact('ticket'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
            'is_internal_note' => 'boolean',
        ]);

        $ticket = SupportTicket::findOrFail($id);

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'staff_id' => Auth::guard('staff')->id(),
            'message' => $request->message,
            'is_internal_note' => $request->boolean('is_internal_note'),
        ]);

        // Update ticket status if it was open
        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        if (!$ticket->first_response_at) {
            $ticket->update(['first_response_at' => now()]);
        }

        return back()->with('success', 'Reply added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,pending,resolved,closed',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $ticket = SupportTicket::findOrFail($id);
        
        $data = [
            'status' => $request->status,
            'priority' => $request->priority,
        ];

        if ($request->status === 'resolved' && !$ticket->resolved_at) {
            $data['resolved_at'] = now();
        }

        if ($request->status === 'closed' && !$ticket->closed_at) {
            $data['closed_at'] = now();
        }

        $ticket->update($data);

        return back()->with('success', 'Ticket updated successfully.');
    }
}
