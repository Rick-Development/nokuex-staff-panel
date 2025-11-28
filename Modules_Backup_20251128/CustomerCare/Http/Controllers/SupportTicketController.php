<?php

namespace Modules\CustomerCare\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CustomerCare\Entities\SupportTicket;
use Modules\CustomerCare\Entities\Customer;
use Modules\Core\Entities\Staff;
use Yajra\DataTables\Facades\DataTables;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getTicketData($request);
        }

        $customers = Customer::where('status', 'active')->get();
        $staff = Staff::where('is_active', true)->get();
        
        return view('customercare::tickets.index', compact('customers', 'staff'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $ticket = SupportTicket::create([
            'ticket_number' => 'TKT-' . date('Ymd') . '-' . rand(1000, 9999),
            'customer_id' => $request->customer_id,
            'subject' => $request->subject,
            'description' => $request->description,
            'priority' => $request->priority,
            'assigned_to' => $request->assigned_to,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Support ticket created successfully',
            'redirect' => route('customercare.tickets')
        ]);
    }

    public function update(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'assigned_to' => 'nullable|exists:staffs,id',
        ]);

        $ticket->update($request->all());

        if ($request->status === 'resolved' && !$ticket->resolved_at) {
            $ticket->update(['resolved_at' => now()]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ticket updated successfully'
        ]);
    }

    public function show(SupportTicket $ticket)
    {
        $ticket->load('customer', 'assignedTo');
        return response()->json([
            'success' => true,
            'ticket' => $ticket
        ]);
    }

    private function getTicketData(Request $request)
    {
        $query = SupportTicket::with(['customer', 'assignedTo']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->has('priority') && $request->priority) {
            $query->where('priority', $request->priority);
        }

        return DataTables::of($query)
            ->addColumn('actions', function($ticket) {
                return '
                    <button onclick="viewTicket(' . $ticket->id . ')" class="btn btn-primary btn-sm">View</button>
                    <button onclick="editTicket(' . $ticket->id . ')" class="btn btn-warning btn-sm">Edit</button>
                ';
            })
            ->addColumn('priority_badge', function($ticket) {
                $badgeClass = [
                    'low' => 'badge-success',
                    'medium' => 'badge-info',
                    'high' => 'badge-warning',
                    'urgent' => 'badge-danger'
                ];
                return '<span class="badge ' . ($badgeClass[$ticket->priority] ?? 'badge-secondary') . '">' . ucfirst($ticket->priority) . '</span>';
            })
            ->addColumn('status_badge', function($ticket) {
                $badgeClass = [
                    'open' => 'badge-primary',
                    'in_progress' => 'badge-warning',
                    'resolved' => 'badge-success',
                    'closed' => 'badge-secondary'
                ];
                return '<span class="badge ' . ($badgeClass[$ticket->status] ?? 'badge-secondary') . '">' . ucfirst(str_replace('_', ' ', $ticket->status)) . '</span>';
            })
            ->rawColumns(['actions', 'priority_badge', 'status_badge'])
            ->make(true);
    }
}