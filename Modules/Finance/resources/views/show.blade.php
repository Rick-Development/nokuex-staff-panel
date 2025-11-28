@extends('core::layouts.master')

@section('content')
<div style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
    <div style="margin-bottom: 1.5rem;">
        <a href="{{ route('staff.finance.transactions') }}" style="color: var(--primary-color); text-decoration: none;">&larr; Back to Transactions</a>
    </div>

    <!-- Transaction Header -->
    <div style="background: linear-gradient(135deg, {{ $transaction->trx_type === '+' ? 'var(--secondary-color), #4a6633' : 'var(--accent-color), #c67316' }}); border-radius: 12px; padding: 2rem; color: white; margin-bottom: 2rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 1rem;">
            <div>
                <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.5rem;">Transaction {{ $transaction->trx_type === '+' ? 'Credit' : 'Debit' }}</div>
                <div style="font-size: 3rem; font-weight: bold; margin-bottom: 0.5rem;">{{ $transaction->trx_type }}{{ $transaction->currency == 'NGN' ? '₦' : ($transaction->currency == 'USD' ? '$' : ($transaction->currency == 'EUR' ? '€' : ($transaction->currency == 'GBP' ? '£' : ''))) }}{{ number_format($transaction->amount, 2) }}</div>
                <div style="font-size: 1.125rem; opacity: 0.9;">{{ $transaction->currency }}</div>
            </div>
            <div style="text-align: right;">
                <span style="padding: 0.75rem 1.5rem; background: rgba(255,255,255,0.2); border-radius: 24px; font-size: 1rem; font-weight: 600; text-transform: uppercase; display: inline-block;">
                    {{ $transaction->status === 'success' ? '✓ Completed' : '⏳ ' . strtoupper($transaction->status) }}
                </span>
                <div style="margin-top: 1rem; font-size: 0.875rem; opacity: 0.9;">
                    {{ \Carbon\Carbon::parse($transaction->created_at)->format('F d, Y • H:i:s') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Details Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <!-- User Information -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <span style="font-size: 1.5rem;">👤</span> User Information
            </h3>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div>
                    <div style="font-size: 0.875rem; color: #666; margin-bottom: 0.25rem;">Full Name</div>
                    <div style="font-weight: 600; color: var(--primary-color);">{{ $transaction->firstname }} {{ $transaction->lastname }}</div>
                </div>
                <div>
                    <div style="font-size: 0.875rem; color: #666; margin-bottom: 0.25rem;">Email</div>
                    <div style="font-weight: 600; color: var(--primary-color);">{{ $transaction->email }}</div>
                </div>
                @if($transaction->phone)
                <div>
                    <div style="font-size: 0.875rem; color: #666; margin-bottom: 0.25rem;">Phone</div>
                    <div style="font-weight: 600; color: var(--primary-color);">{{ $transaction->phone }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Transaction Details -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <span style="font-size: 1.5rem;">📋</span> Transaction Details
            </h3>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div>
                    <div style="font-size: 0.875rem; color: #666; margin-bottom: 0.25rem;">Transaction ID</div>
                    <div style="font-weight: 600; color: var(--primary-color); font-family: monospace;">{{ $transaction->trx_id ?? $transaction->trx }}</div>
                </div>
                <div>
                    <div style="font-size: 0.875rem; color: #666; margin-bottom: 0.25rem;">Transaction Type</div>
                    <div style="font-weight: 600; color: var(--primary-color); text-transform: capitalize;">
                        {{ str_replace('_', ' ', $transaction->transactional_type) }}
                    </div>
                </div>
                <div>
                    <div style="font-size: 0.875rem; color: #666; margin-bottom: 0.25rem;">Direction</div>
                    <div style="font-weight: 600; color: {{ $transaction->trx_type === '+' ? 'var(--secondary-color)' : 'var(--accent-color)' }};">
                        {{ $transaction->trx_type === '+' ? '↓ Credit (Incoming)' : '↑ Debit (Outgoing)' }}
                    </div>
                </div>
                <div>
                    <div style="font-size: 0.875rem; color: #666; margin-bottom: 0.25rem;">Base Amount</div>
                    <div style="font-weight: 600; color: var(--primary-color);">{{ $transaction->currency == 'NGN' ? '₦' : ($transaction->currency == 'USD' ? '$' : ($transaction->currency == 'EUR' ? '€' : ($transaction->currency == 'GBP' ? '£' : ''))) }}{{ number_format($transaction->base_amount, 2) }}</div>
                </div>
                @if($transaction->charge > 0)
                <div>
                    <div style="font-size: 0.875rem; color: #666; margin-bottom: 0.25rem;">Charge</div>
                    <div style="font-weight: 600; color: var(--accent-color);">{{ $transaction->currency == 'NGN' ? '₦' : ($transaction->currency == 'USD' ? '$' : ($transaction->currency == 'EUR' ? '€' : ($transaction->currency == 'GBP' ? '£' : ''))) }}{{ number_format($transaction->charge, 2) }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 2rem;">
        <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <span style="font-size: 1.5rem;">ℹ️</span> Additional Information
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
            @if($transaction->remarks)
            <div>
                <div style="font-size: 0.875rem; color: #666; margin-bottom: 0.25rem;">Remarks</div>
                <div style="font-weight: 600; color: var(--primary-color);">{{ $transaction->remarks }}</div>
            </div>
            @endif
            @if($transaction->balance)
            <div>
                <div style="font-size: 0.875rem; color: #666; margin-bottom: 0.25rem;">Balance After</div>
                <div style="font-weight: 600; color: var(--primary-color);">{{ $transaction->currency == 'NGN' ? '₦' : ($transaction->currency == 'USD' ? '$' : ($transaction->currency == 'EUR' ? '€' : ($transaction->currency == 'GBP' ? '£' : ''))) }}{{ number_format($transaction->balance, 2) }}</div>
            </div>
            @endif
            @if($transaction->sender_account_name)
            <div>
                <div style="font-size: 0.875rem; color: #666; margin-bottom: 0.25rem;">Sender Account</div>
                <div style="font-weight: 600; color: var(--primary-color);">{{ $transaction->sender_account_name }}</div>
                @if($transaction->sender_account_number)
                    <div style="font-size: 0.875rem; color: #999; font-family: monospace;">{{ $transaction->sender_account_number }}</div>
                @endif
            </div>
            @endif
            @if($transaction->sessionId)
            <div>
                <div style="font-size: 0.875rem; color: #666; margin-bottom: 0.25rem;">Session ID</div>
                <div style="font-weight: 600; color: var(--primary-color); font-family: monospace; font-size: 0.875rem;">{{ $transaction->sessionId }}</div>
            </div>
            @endif
        </div>
        @if($transaction->note)
        <div style="margin-top: 1.5rem; padding: 1rem; background: #f9f9f9; border-radius: 8px; border-left: 4px solid var(--accent-color);">
            <div style="font-size: 0.875rem; color: #666; margin-bottom: 0.5rem; font-weight: 600;">Note</div>
            <div style="color: var(--primary-color);">{{ $transaction->note }}</div>
        </div>
        @endif
        @if($transaction->sender_narration)
        <div style="margin-top: 1rem; padding: 1rem; background: #f9f9f9; border-radius: 8px; border-left: 4px solid var(--secondary-color);">
            <div style="font-size: 0.875rem; color: #666; margin-bottom: 0.5rem; font-weight: 600;">Narration</div>
            <div style="color: var(--primary-color);">{{ $transaction->sender_narration }}</div>
        </div>
        @endif
    </div>

    <!-- Action Buttons -->
    <div style="display: flex; gap: 1rem; justify-content: center;">
        <a href="{{ route('staff.finance.transactions') }}" style="padding: 0.75rem 2rem; background: var(--primary-color); color: white; text-decoration: none; border-radius: 8px; font-weight: 600;">
            View All Transactions
        </a>
        <button onclick="window.print()" style="padding: 0.75rem 2rem; background: var(--secondary-color); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
            🖨️ Print Receipt
        </button>
    </div>
</div>

<style>
    @media print {
        .sidebar, .mobile-menu-btn, button, a[href="{{ route('staff.finance.transactions') }}"] {
            display: none !important;
        }
        .main-content {
            margin-left: 0 !important;
        }
    }
</style>
@endsection
