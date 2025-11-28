@extends('core::layouts.master')

@section('content')
<div style="padding: 2rem; max-width: 1600px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <a href="{{ route('staff.finance.dashboard') }}" style="color: var(--primary-color); text-decoration: none;">&larr; Back to Finance Dashboard</a>
            <h1 style="font-size: 2rem; font-weight: bold; color: var(--primary-color); margin: 0.5rem 0 0 0;">Transaction Management</h1>
        </div>
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <a href="{{ route('staff.finance.transactions.export', request()->all()) }}" style="padding: 0.75rem 1.5rem; background: var(--secondary-color); color: white; text-decoration: none; border-radius: 8px; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                📥 Export CSV
            </a>
            <button onclick="document.getElementById('importModal').style.display='flex'" style="padding: 0.75rem 1.5rem; background: var(--accent-color); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
                📤 Import CSV
            </button>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 1.5rem; margin-bottom: 1.5rem;">
        <form action="{{ route('staff.finance.transactions') }}" method="GET">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-weight: 600; color: var(--primary-color); margin-bottom: 0.5rem; font-size: 0.875rem;">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Reference, email..." style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px; outline: none;" onfocus="this.style.borderColor='var(--primary-color)'" onblur="this.style.borderColor='#e0e0e0'">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; color: var(--primary-color); margin-bottom: 0.5rem; font-size: 0.875rem;">Transaction Type</label>
                    <select name="txn_type" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px; outline: none;">
                        <option value="">All Types</option>
                        <option value="banktransfer" {{ request('txn_type') == 'banktransfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="withdraw" {{ request('txn_type') == 'withdraw' ? 'selected' : '' }}>Withdraw</option>
                        <option value="deposit" {{ request('txn_type') == 'deposit' ? 'selected' : '' }}>Deposit</option>
                        <option value="internal_transfer" {{ request('txn_type') == 'internal_transfer' ? 'selected' : '' }}>Internal Transfer</option>
                        <option value="buy" {{ request('txn_type') == 'buy' ? 'selected' : '' }}>Buy</option>
                        <option value="sell" {{ request('txn_type') == 'sell' ? 'selected' : '' }}>Sell</option>
                        <option value="swap" {{ request('txn_type') == 'swap' ? 'selected' : '' }}>Swap</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-weight: 600; color: var(--primary-color); margin-bottom: 0.5rem; font-size: 0.875rem;">Direction</label>
                    <select name="type" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px; outline: none;">
                        <option value="">All Directions</option>
                        <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Credit</option>
                        <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>Debit</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-weight: 600; color: var(--primary-color); margin-bottom: 0.5rem; font-size: 0.875rem;">Status</label>
                    <select name="status" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px; outline: none;">
                        <option value="">All Statuses</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                <div style="display: flex; align-items: end; gap: 0.5rem;">
                    <button type="submit" style="flex: 1; padding: 0.75rem; background: var(--primary-color); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        🔍 Filter
                    </button>
                    @if(request()->hasAny(['search', 'type', 'status', 'txn_type']))
                        <a href="{{ route('staff.finance.transactions') }}" style="padding: 0.75rem 1rem; background: #f5f5f5; color: #666; text-decoration: none; border-radius: 8px; font-weight: 600;">
                            ✕
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Transactions Grid -->
    <div style="display: grid; gap: 1rem;">
        @forelse($transactions as $txn)
            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.2s; cursor: pointer;" onclick="window.location='{{ route('staff.finance.transactions.show', $txn->id) }}'" onmouseover="this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'" onmouseout="this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
                <div style="display: grid; grid-template-columns: auto 1fr auto auto; gap: 1.5rem; align-items: center;">
                    <!-- Icon -->
                    <div style="width: 56px; height: 56px; border-radius: 12px; background: {{ $txn->trx_type === '+' ? 'linear-gradient(135deg, var(--secondary-color), #4a6633)' : 'linear-gradient(135deg, var(--accent-color), #c67316)' }}; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; color: white;">
                        {{ $txn->trx_type === '+' ? '↓' : '↑' }}
                    </div>

                    <!-- Transaction Info -->
                    <div>
                        <div style="font-weight: 600; color: var(--primary-color); font-size: 1.125rem; margin-bottom: 0.25rem;">
                            {{ $txn->firstname }} {{ $txn->lastname }}
                        </div>
                        <div style="font-size: 0.875rem; color: #666; margin-bottom: 0.25rem;">
                            <span style="padding: 0.25rem 0.5rem; background: #f0f0f0; border-radius: 4px; font-weight: 600; text-transform: capitalize;">
                                {{ str_replace('_', ' ', $txn->transactional_type) }}
                            </span>
                        </div>
                        <div style="font-size: 0.875rem; color: #999; font-family: monospace;">
                            {{ $txn->trx_id ?? $txn->trx }}
                        </div>
                        <div style="font-size: 0.875rem; color: #999; margin-top: 0.25rem;">
                            {{ \Carbon\Carbon::parse($txn->created_at)->format('M d, Y • H:i') }}
                        </div>
                    </div>

                    <!-- Amount -->
                    <div style="text-align: right;">
                        <div style="font-size: 1.5rem; font-weight: bold; color: {{ $txn->trx_type === '+' ? 'var(--secondary-color)' : 'var(--accent-color)' }};">
                            {{ $txn->trx_type }}{{ $txn->currency == 'NGN' ? '₦' : ($txn->currency == 'USD' ? '$' : ($txn->currency == 'EUR' ? '€' : ($txn->currency == 'GBP' ? '£' : ''))) }}{{ number_format($txn->amount, 2) }}
                        </div>
                        <div style="font-size: 0.875rem; color: #666;">{{ $txn->currency }}</div>
                    </div>

                    <!-- Status Badge -->
                    <div>
                        <span style="padding: 0.5rem 1rem; background: {{ $txn->status === 'success' ? '#d4edda' : '#f8d7da' }}; color: {{ $txn->status === 'success' ? 'var(--secondary-color)' : 'var(--accent-color)' }}; border-radius: 20px; font-size: 0.875rem; font-weight: 600; text-transform: uppercase;">
                            {{ $txn->status === 'success' ? '✓ Completed' : '⏳ ' . strtoupper($txn->status) }}
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div style="background: white; border-radius: 12px; padding: 4rem; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">📭</div>
                <h3 style="color: var(--primary-color); margin-bottom: 0.5rem;">No transactions found</h3>
                <p style="color: #666;">Try adjusting your filters or search criteria</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div style="margin-top: 2rem;">
        {{ $transactions->links() }}
    </div>
</div>

<!-- Import Modal -->
<div id="importModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 2rem; max-width: 500px; width: 90%;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.5rem; font-weight: bold; color: var(--primary-color); margin: 0;">Import Transactions</h2>
            <button onclick="document.getElementById('importModal').style.display='none'" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #999;">&times;</button>
        </div>
        <form action="{{ route('staff.finance.transactions.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; color: var(--primary-color); margin-bottom: 0.5rem;">CSV File</label>
                <input type="file" name="file" accept=".csv" required style="width: 100%; padding: 0.75rem; border: 2px dashed #e0e0e0; border-radius: 8px;">
                <p style="font-size: 0.875rem; color: #666; margin-top: 0.5rem;">
                    Format: ID, Reference, User, Email, Type, Amount, Currency, Status, Date
                </p>
            </div>
            <div style="display: flex; gap: 1rem;">
                <button type="button" onclick="document.getElementById('importModal').style.display='none'" style="flex: 1; padding: 0.75rem; background: #f5f5f5; color: #666; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    Cancel
                </button>
                <button type="submit" style="flex: 1; padding: 0.75rem; background: var(--accent-color); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    Import
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
