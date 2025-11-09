@extends('core::layouts.app')

@section('title', 'Financial Reports')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="margin: 0;">Financial Reports</h2>
        <button onclick="window.print()" class="btn btn-primary">
            <i>🖨️</i> Print Report
        </button>
    </div>

    <!-- Daily Report -->
    <div class="card" style="margin-bottom: 2rem;">
        <h3 style="margin-bottom: 1rem; color: var(--primary-color);">Today's Summary</h3>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Count</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($daily_report as $report)
                    <tr>
                        <td>
                            <span class="badge badge-{{ $report->status === 'completed' ? 'success' : ($report->status === 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($report->status) }}
                            </span>
                        </td>
                        <td>{{ $report->count }}</td>
                        <td>₦{{ number_format($report->total, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: #666;">No transactions today</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Transaction Type Report -->
    <div class="card" style="margin-bottom: 2rem;">
        <h3 style="margin-bottom: 1rem; color: var(--primary-color);">Transaction Types</h3>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Count</th>
                        <th>Total Amount</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_transactions = $type_report->sum('count');
                        $total_amount = $type_report->sum('total');
                    @endphp
                    @foreach($type_report as $report)
                    <tr>
                        <td><strong>{{ ucfirst($report->type) }}</strong></td>
                        <td>{{ $report->count }}</td>
                        <td>₦{{ number_format($report->total, 2) }}</td>
                        <td>
                            @if($total_transactions > 0)
                                {{ number_format(($report->count / $total_transactions) * 100, 1) }}%
                            @else
                                0%
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    <tr style="background: #f8f9fa; font-weight: 600;">
                        <td>Total</td>
                        <td>{{ $total_transactions }}</td>
                        <td>₦{{ number_format($total_amount, 2) }}</td>
                        <td>100%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Monthly Trends -->
    <div class="card">
        <h3 style="margin-bottom: 1rem; color: var(--primary-color);">This Month's Activity</h3>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Transactions</th>
                        <th>Total Volume</th>
                        <th>Average</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($monthly_report->take(15) as $report)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($report->date)->format('M d, Y') }}</td>
                        <td>{{ $report->count }}</td>
                        <td>₦{{ number_format($report->total, 2) }}</td>
                        <td>₦{{ number_format($report->count > 0 ? $report->total / $report->count : 0, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #666;">No data for this month</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, nav, .sidebar { display: none !important; }
    .card { box-shadow: none !important; border: 1px solid #ddd; }
}
</style>
@endsection