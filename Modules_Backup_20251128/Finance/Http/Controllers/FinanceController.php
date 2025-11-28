<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Finance\Entities\Transaction;
use Modules\Finance\Entities\Reconciliation;
use Modules\Finance\Entities\BlusaltTransaction;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class FinanceController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_transactions' => Transaction::sum('amount'),
            'transaction_count' => Transaction::count(),
            'pending_reconciliations' => Reconciliation::where('status', 'pending')->count(),
            'monthly_volume' => Transaction::whereMonth('created_at', Carbon::now()->month)->sum('amount'),
            'today_volume' => Transaction::whereDate('created_at', Carbon::today())->sum('amount'),
            'pending_amount' => Transaction::where('status', 'pending')->sum('amount'),
            'completed_today' => Transaction::whereDate('created_at', Carbon::today())->where('status', 'completed')->count(),
            'failed_count' => Transaction::where('status', 'failed')->count(),
        ];

        $recent_transactions = Transaction::with('customer')
            ->latest()
            ->take(10)
            ->get();

        return view('finance::dashboard', compact('stats', 'recent_transactions'));
    }

    public function transactions(Request $request)
    {
        if ($request->ajax()) {
            return $this->getTransactionData($request);
        }

        return view('finance::transactions.index');
    }

    public function reports()
    {
        $daily_report = Transaction::whereDate('created_at', Carbon::today())
            ->selectRaw('status, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('status')
            ->get();

        $monthly_report = Transaction::whereMonth('created_at', Carbon::now()->month)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        $type_report = Transaction::selectRaw('type, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('type')
            ->get();

        return view('finance::reports.index', compact('daily_report', 'monthly_report', 'type_report'));
    }

    public function reconciliation(Request $request)
    {
        if ($request->ajax()) {
            return $this->getReconciliationData($request);
        }

        $stats = [
            'pending' => Reconciliation::where('status', 'pending')->count(),
            'completed' => Reconciliation::where('status', 'completed')->count(),
            'variance_amount' => Reconciliation::sum('variance'),
        ];

        return view('finance::reconciliation.index', compact('stats'));
    }

    public function blusalt()
    {
        $stats = [
            'total_otc' => BlusaltTransaction::count(),
            'pending' => BlusaltTransaction::where('status', 'pending')->count(),
            'completed' => BlusaltTransaction::where('status', 'completed')->count(),
            'failed' => BlusaltTransaction::where('status', 'failed')->count(),
        ];

        $recent_otc = BlusaltTransaction::with('transaction')
            ->latest()
            ->take(10)
            ->get();

        return view('finance::blusalt.index', compact('stats', 'recent_otc'));
    }

    private function getTransactionData(Request $request)
    {
        $query = Transaction::with('customer');

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return DataTables::of($query)
            ->addColumn('actions', function($transaction) {
                return '
                    <button onclick="viewTransaction(' . $transaction->id . ')" class="btn btn-primary btn-sm">View</button>
                ';
            })
            ->addColumn('status_badge', function($transaction) {
                $badgeClass = [
                    'pending' => 'badge-warning',
                    'completed' => 'badge-success',
                    'failed' => 'badge-danger',
                    'processing' => 'badge-info'
                ];
                return '<span class="badge ' . ($badgeClass[$transaction->status] ?? 'badge-secondary') . '">' . ucfirst($transaction->status) . '</span>';
            })
            ->addColumn('amount_formatted', function($transaction) {
                return $transaction->currency . ' ' . number_format($transaction->amount, 2);
            })
            ->rawColumns(['actions', 'status_badge'])
            ->make(true);
    }

    private function getReconciliationData(Request $request)
    {
        $query = Reconciliation::with('processedBy');

        return DataTables::of($query)
            ->addColumn('actions', function($reconciliation) {
                return '
                    <button onclick="viewReconciliation(' . $reconciliation->id . ')" class="btn btn-primary btn-sm">View</button>
                ';
            })
            ->addColumn('status_badge', function($reconciliation) {
                $badgeClass = [
                    'pending' => 'badge-warning',
                    'completed' => 'badge-success',
                    'reviewed' => 'badge-info'
                ];
                return '<span class="badge ' . ($badgeClass[$reconciliation->status] ?? 'badge-secondary') . '">' . ucfirst($reconciliation->status) . '</span>';
            })
            ->addColumn('variance_formatted', function($reconciliation) {
                $color = $reconciliation->variance == 0 ? 'green' : 'red';
                return '<span style="color: ' . $color . '">₦' . number_format($reconciliation->variance, 2) . '</span>';
            })
            ->rawColumns(['actions', 'status_badge', 'variance_formatted'])
            ->make(true);
    }

    public function showTransaction($id)
    {
        $transaction = Transaction::with('customer')->findOrFail($id);
        return response()->json([
            'success' => true,
            'transaction' => $transaction
        ]);
    }
}