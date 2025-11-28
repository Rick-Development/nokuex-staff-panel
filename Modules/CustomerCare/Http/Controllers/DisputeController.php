<?php

namespace Modules\CustomerCare\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\CustomerCare\Entities\Dispute;

class DisputeController extends Controller
{
    public function index(Request $request)
    {
        $query = Dispute::with(['user', 'assignedStaff']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('dispute_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $disputes = $query->latest()->paginate(15);

        return view('customercare::disputes.index', compact('disputes'));
    }

    public function show($id)
    {
        $dispute = Dispute::with(['user', 'assignedStaff'])->findOrFail($id);
        return view('customercare::disputes.show', compact('dispute'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,investigating,pending_user,resolved,closed',
            'priority' => 'required|in:low,medium,high,urgent',
            'resolution' => 'nullable|string',
        ]);

        $dispute = Dispute::findOrFail($id);

        $data = [
            'status' => $request->status,
            'priority' => $request->priority,
            'resolution' => $request->resolution,
        ];

        if ($request->status === 'resolved' && !$dispute->resolved_at) {
            $data['resolved_at'] = now();
        }

        $dispute->update($data);

        return back()->with('success', 'Dispute updated successfully.');
    }
}
