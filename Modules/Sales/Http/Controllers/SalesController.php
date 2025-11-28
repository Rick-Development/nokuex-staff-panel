<?php

namespace Modules\Sales\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Sales\Entities\CrmLead;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function dashboard()
    {
        // KPI Stats
        $totalLeads = CrmLead::count();
        $newLeads = CrmLead::where('status', 'new')->count();
        $convertedLeads = CrmLead::where('status', 'converted')->count();
        $conversionRate = $totalLeads > 0 ? ($convertedLeads / $totalLeads) * 100 : 0;

        // Pipeline Data (for Chart)
        $pipelineData = CrmLead::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Recent Leads
        $recentLeads = CrmLead::with('assignedStaff')
            ->latest()
            ->take(5)
            ->get();

        // Top Sources
        $topSources = CrmLead::select('source', DB::raw('count(*) as count'))
            ->groupBy('source')
            ->orderByDesc('count')
            ->take(4)
            ->get();

        return view('sales::dashboard', compact(
            'totalLeads', 'newLeads', 'convertedLeads', 'conversionRate',
            'pipelineData', 'recentLeads', 'topSources'
        ));
    }

    public function index(Request $request)
    {
        $query = CrmLead::query()->with('assignedStaff');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leads = $query->latest()->paginate(15);

        return view('sales::leads.index', compact('leads'));
    }

    public function create()
    {
        $staff = \Modules\Core\Entities\Staff::all();
        return view('sales::leads.create', compact('staff'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'source' => 'required|in:website,referral,social_media,direct,other',
            'assigned_to' => 'nullable|exists:staff,id',
            'notes' => 'nullable|string',
        ]);

        CrmLead::create($request->all());

        return redirect()->route('staff.sales.leads.index')->with('success', 'Lead created successfully');
    }

    public function show($id)
    {
        $lead = CrmLead::with('assignedStaff', 'user')->findOrFail($id);
        return view('sales::leads.show', compact('lead'));
    }

    public function edit($id)
    {
        $lead = CrmLead::findOrFail($id);
        $staff = \Modules\Core\Entities\Staff::all();
        return view('sales::leads.edit', compact('lead', 'staff'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'status' => 'required|in:new,contacted,qualified,converted,lost',
            'source' => 'required|in:website,referral,social_media,direct,other',
            'assigned_to' => 'nullable|exists:staff,id',
            'notes' => 'nullable|string',
            'next_follow_up_at' => 'nullable|date',
        ]);

        $lead = CrmLead::findOrFail($id);
        $lead->update($request->all());

        if ($request->status !== $lead->getOriginal('status')) {
            $lead->last_contact_at = now();
            $lead->save();
        }

        return back()->with('success', 'Lead updated successfully');
    }

    public function destroy($id)
    {
        $lead = CrmLead::findOrFail($id);
        $lead->delete();

        return redirect()->route('staff.sales.leads.index')->with('success', 'Lead deleted successfully');
    }
}
