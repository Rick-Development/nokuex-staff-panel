<?php

namespace Modules\Compliance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Compliance\Entities\KycReview;
use Modules\Compliance\Entities\ComplianceFlag;
use Modules\Compliance\Entities\AccountAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ComplianceController extends Controller
{
    public function dashboard()
    {
        // KYC Stats
        $pendingKyc = KycReview::where('status', 'pending')->count();
        $inReviewKyc = KycReview::where('status', 'in_review')->count();
        $approvedKyc = KycReview::where('status', 'approved')->count();
        $rejectedKyc = KycReview::where('status', 'rejected')->count();

        // Compliance Flags
        $criticalFlags = ComplianceFlag::where('severity', 'critical')->where('status', 'pending')->count();
        $highFlags = ComplianceFlag::where('severity', 'high')->where('status', 'pending')->count();
        $totalPendingFlags = ComplianceFlag::where('status', 'pending')->count();

        // Recent KYC Reviews
        $recentKycReviews = KycReview::with('user', 'reviewer')
            ->latest()
            ->take(5)
            ->get();

        // Recent Flags
        $recentFlags = ComplianceFlag::with('user', 'reviewer')
            ->latest()
            ->take(5)
            ->get();

        // Flag Distribution
        $flagDistribution = ComplianceFlag::select('severity', DB::raw('count(*) as count'))
            ->groupBy('severity')
            ->pluck('count', 'severity')
            ->toArray();

        return view('compliance::dashboard', compact(
            'pendingKyc', 'inReviewKyc', 'approvedKyc', 'rejectedKyc',
            'criticalFlags', 'highFlags', 'totalPendingFlags',
            'recentKycReviews', 'recentFlags', 'flagDistribution'
        ));
    }

    public function kycIndex(Request $request)
    {
        $query = KycReview::with('user', 'reviewer');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('review_type', $request->type);
        }

        $reviews = $query->latest()->paginate(15);

        return view('compliance::kyc.index', compact('reviews'));
    }

    public function kycShow($id)
    {
        $review = KycReview::with('user', 'reviewer')->findOrFail($id);
        
        // Get user's account actions history
        $accountActions = AccountAction::where('user_id', $review->user_id)
            ->with('staff')
            ->latest()
            ->take(5)
            ->get();

        return view('compliance::kyc.show', compact('review', 'accountActions'));
    }

    public function kycUpdate(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_review,approved,rejected,resubmit_required',
            'rejection_reason' => 'required_if:status,rejected',
            'notes' => 'nullable|string',
        ]);

        $review = KycReview::findOrFail($id);
        $review->status = $request->status;
        $review->rejection_reason = $request->rejection_reason;
        $review->notes = $request->notes;
        $review->reviewed_by = Auth::guard('staff')->id();
        $review->reviewed_at = now();
        $review->save();

        return back()->with('success', 'KYC review updated successfully');
    }

    public function flagsIndex(Request $request)
    {
        $query = ComplianceFlag::with('user', 'reviewer');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        $flags = $query->latest()->paginate(15);

        return view('compliance::flags.index', compact('flags'));
    }

    public function flagUpdate(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,under_review,cleared,escalated',
            'resolution_notes' => 'nullable|string',
        ]);

        $flag = ComplianceFlag::findOrFail($id);
        $flag->status = $request->status;
        $flag->resolution_notes = $request->resolution_notes;
        $flag->reviewed_by = Auth::guard('staff')->id();
        $flag->reviewed_at = now();
        $flag->save();

        return back()->with('success', 'Compliance flag updated successfully');
    }
}
