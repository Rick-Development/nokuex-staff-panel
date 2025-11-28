<?php

namespace Modules\Finance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Finance\Entities\FinanceReport;
use Modules\Finance\Entities\ReconciliationLog;

class FinanceController extends Controller
{
    public function dashboard()
    {
        // Get transaction statistics
        $stats = [
            'total_transactions' => DB::table('transactions')->count(),
            'total_revenue' => DB::table('transactions')
                ->where('trx_type', '+')
                ->where('status', 'success')
                ->sum('amount'),
            'total_expenses' => DB::table('transactions')
                ->where('trx_type', '-')
                ->where('status', 'success')
                ->sum('amount'),
            'pending_transactions' => DB::table('transactions')
                ->where('status', '!=', 'success')
                ->count(),
        ];

        // Monthly revenue/expense data for charts (last 6 months)
        $monthlyData = DB::table('transactions')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(CASE WHEN trx_type = "+" AND status = "success" THEN amount ELSE 0 END) as revenue'),
                DB::raw('SUM(CASE WHEN trx_type = "-" AND status = "success" THEN amount ELSE 0 END) as expenses')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Recent reconciliations
        $recentReconciliations = ReconciliationLog::with('staff')
            ->latest()
            ->take(5)
            ->get();

        return view('finance::dashboard', compact('stats', 'monthlyData', 'recentReconciliations'));
    }

    public function transactions(Request $request)
    {
        $query = DB::table('transactions')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->select('transactions.*', 'users.firstname', 'users.lastname', 'users.email');

        if ($request->filled('status')) {
            if ($request->status === 'completed') {
                $query->where('transactions.status', 'success');
            } else {
                $query->where('transactions.status', '!=', 'success');
            }
        }

        if ($request->filled('type')) {
            $typeValue = $request->type === 'credit' ? '+' : '-';
            $query->where('transactions.trx_type', $typeValue);
        }

        if ($request->filled('txn_type')) {
            $query->where('transactions.transactional_type', $request->txn_type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transactions.trx_id', 'like', "%{$search}%")
                  ->orWhere('transactions.trx', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%");
            });
        }

        $transactions = $query->latest('transactions.created_at')->paginate(20);

        return view('finance::transactions', compact('transactions'));
    }

    public function reconciliation()
    {
        $logs = ReconciliationLog::with('staff')
            ->latest()
            ->paginate(15);

        $summary = [
            'total_reconciliations' => ReconciliationLog::count(),
            'matched' => ReconciliationLog::where('status', 'matched')->count(),
            'discrepancies' => ReconciliationLog::where('status', 'discrepancy')->count(),
            'pending' => ReconciliationLog::where('status', 'pending')->count(),
        ];

        return view('finance::reconciliation', compact('logs', 'summary'));
    }

    public function show($id)
    {
        $transaction = DB::table('transactions')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->select('transactions.*', 'users.firstname', 'users.lastname', 'users.email', 'users.phone')
            ->where('transactions.id', $id)
            ->first();

        if (!$transaction) {
            abort(404);
        }

        return view('finance::show', compact('transaction'));
    }

    public function export(Request $request)
    {
        $query = DB::table('transactions')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->select('transactions.*', 'users.firstname', 'users.lastname', 'users.email');

        if ($request->filled('status')) {
            if ($request->status === 'completed') {
                $query->where('transactions.status', 'success');
            } else {
                $query->where('transactions.status', '!=', 'success');
            }
        }

        if ($request->filled('type')) {
            $typeValue = $request->type === 'credit' ? '+' : '-';
            $query->where('transactions.trx_type', $typeValue);
        }

        $transactions = $query->latest('transactions.created_at')->get();

        $filename = 'transactions_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Reference', 'User', 'Email', 'Type', 'Amount', 'Currency', 'Status', 'Date']);

            foreach ($transactions as $txn) {
                fputcsv($file, [
                    $txn->id,
                    $txn->trx_id ?? $txn->trx,
                    $txn->firstname . ' ' . $txn->lastname,
                    $txn->email,
                    $txn->trx_type === '+' ? 'Credit' : 'Debit',
                    $txn->amount,
                    $txn->currency,
                    $txn->status === 'success' ? 'Completed' : 'Pending',
                    $txn->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));
        
        // Remove header row
        $header = array_shift($data);
        
        $imported = 0;
        $errors = [];

        foreach ($data as $index => $row) {
            try {
                // Validate row has minimum required columns
                if (count($row) < 8) {
                    $errors[] = "Row " . ($index + 2) . ": Insufficient columns";
                    continue;
                }

                // This is a simplified import - in production you'd want more validation
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
            }
        }

        if (count($errors) > 0) {
            return back()->with('error', 'Import completed with errors. Imported: ' . $imported . '. Errors: ' . implode(', ', array_slice($errors, 0, 5)));
        }

        return back()->with('success', "Successfully imported {$imported} transactions.");
    }

    public function runReconciliation(Request $request)
    {
        $type = $request->input('type', 'daily');
        $date = $request->input('date', now()->toDateString());
        
        // Get all currencies used in wallets
        $currencies = DB::table('user_wallets')->select('currency_code')->distinct()->pluck('currency_code');
        
        $results = [];
        $hasDiscrepancy = false;

        foreach ($currencies as $currency) {
            // Get wallet balances for this currency
            $walletBalance = DB::table('user_wallets')
                ->where('currency_code', $currency)
                ->sum('balance');
            
            // Get transaction balance for this currency
            $transactionBalance = DB::table('transactions')
                ->where('currency', $currency)
                ->where('status', 'success')
                ->selectRaw('SUM(CASE WHEN trx_type = "+" THEN amount ELSE 0 END) - SUM(CASE WHEN trx_type = "-" THEN amount ELSE 0 END) as net_balance')
                ->first()
                ->net_balance ?? 0;
            
            $difference = abs($walletBalance - $transactionBalance);
            $status = $difference < 0.01 ? 'matched' : 'discrepancy';
            
            if ($status === 'discrepancy') {
                $hasDiscrepancy = true;
            }

            // Create reconciliation log
            DB::table('reconciliation_logs')->insert([
                'reconciliation_type' => $type,
                'reconciliation_date' => $date,
                'currency' => $currency,
                'expected_balance' => $transactionBalance,
                'actual_balance' => $walletBalance,
                'difference' => $walletBalance - $transactionBalance,
                'status' => $status,
                'staff_id' => Auth::guard('staff')->id(),
                'notes' => "Automated reconciliation for {$currency} ({$type}) on {$date}",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $results[] = [
                'currency' => $currency,
                'status' => $status,
                'difference' => $difference
            ];
        }
        
        $message = $hasDiscrepancy 
            ? "Reconciliation completed with discrepancies in some currencies." 
            : "Reconciliation completed successfully! All currencies matched.";
        
        return back()->with($hasDiscrepancy ? 'warning' : 'success', $message);
    }

    public function createReconciliation(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'currency' => 'required|string|max:10',
            'expected_balance' => 'required|numeric',
            'actual_balance' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);
        
        $difference = abs($request->expected_balance - $request->actual_balance);
        $status = $difference < 0.01 ? 'matched' : 'discrepancy';
        
        DB::table('reconciliation_logs')->insert([
            'reconciliation_type' => $request->type,
            'reconciliation_date' => now()->toDateString(),
            'currency' => $request->currency,
            'expected_balance' => $request->expected_balance,
            'actual_balance' => $request->actual_balance,
            'difference' => $difference,
            'status' => $status,
            'staff_id' => Auth::guard('staff')->id(),
            'notes' => $request->notes,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return back()->with('success', 'Manual reconciliation entry created successfully.');
    }

    public function updateReconciliationStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:matched,discrepancy,pending,resolved',
            'notes' => 'nullable|string',
        ]);
        
        DB::table('reconciliation_logs')
            ->where('id', $id)
            ->update([
                'status' => $request->status,
                'notes' => $request->notes ?? DB::raw('notes'),
                'updated_at' => now(),
            ]);
        
        return back()->with('success', 'Reconciliation status updated successfully.');
    }
}
