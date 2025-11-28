<?php

namespace Modules\CustomerCare\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Compliance\Entities\AccountAction;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('account_status', $request->status);
        }

        $users = $query->latest()->paginate(15);

        return view('customercare::customers.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        
        // Fetch Wallet Balances
        $wallets = \Illuminate\Support\Facades\DB::table('user_wallets')->where('user_id', $id)->get();
        
        // Fetch Transaction Stats
        $totalTransactions = \Illuminate\Support\Facades\DB::table('transactions')->where('user_id', $id)->count();
        $totalVolume = \Illuminate\Support\Facades\DB::table('transactions')
            ->where('user_id', $id)
            ->where('status', 'success')
            ->sum('amount');
        
        // Recent Transactions
        $recentTransactions = \Illuminate\Support\Facades\DB::table('transactions')
            ->where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Chart Data (Last 6 months volume)
        $monthlyVolume = \Illuminate\Support\Facades\DB::table('transactions')
            ->where('user_id', $id)
            ->where('status', 'success')
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Tickets & Disputes
        $ticketsCount = \Modules\CustomerCare\Entities\SupportTicket::where('user_id', $id)->count();
        $activeTickets = \Modules\CustomerCare\Entities\SupportTicket::where('user_id', $id)->whereIn('status', ['open', 'in_progress'])->count();
        $disputesCount = \Modules\CustomerCare\Entities\Dispute::where('user_id', $id)->count();
        
        return view('customercare::customers.show', compact(
            'user', 'wallets', 'totalTransactions', 'totalVolume', 
            'recentTransactions', 'monthlyVolume', 'ticketsCount', 
            'activeTickets', 'disputesCount'
        ));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:freeze,unfreeze,suspend,activate',
            'reason' => 'required|string|max:255',
        ]);

        $user = User::findOrFail($id);
        $action = $request->action;

        // Map action to account status
        $statusMap = [
            'freeze' => 'frozen',
            'unfreeze' => 'active',
            'suspend' => 'suspended',
            'activate' => 'active',
        ];

        $user->account_status = $statusMap[$action];
        $user->save();

        // Log the action
        AccountAction::create([
            'user_id' => $user->id,
            'staff_id' => Auth::guard('staff')->id(),
            'action_type' => $action,
            'reason' => $request->reason,
            'is_active' => true,
        ]);

        return back()->with('success', "User account has been {$statusMap[$action]}.");
    }
}
