@extends('core::layouts.master')

@section('content')
<div style="padding: 2rem; max-width: 1400px; margin: 0 auto;">
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 2rem; font-weight: bold; color: var(--primary-color); margin-bottom: 0.5rem;">Finance Dashboard</h1>
        <p style="color: #666;">Monitor transactions, revenue, and reconciliation status</p>
    </div>

    <!-- KPI Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <!-- Total Revenue -->
        <div style="background: linear-gradient(135deg, var(--secondary-color), #4a6633); border-radius: 12px; padding: 1.5rem; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                <div style="font-size: 2.5rem;">💰</div>
                <span style="background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">REVENUE</span>
            </div>
            <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.25rem;">{{ number_format($stats['total_revenue'], 2) }}</div>
            <div style="font-size: 0.875rem; opacity: 0.9;">Total Revenue</div>
        </div>

        <!-- Total Expenses -->
        <div style="background: linear-gradient(135deg, var(--accent-color), #c67316); border-radius: 12px; padding: 1.5rem; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                <div style="font-size: 2.5rem;">💸</div>
                <span style="background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">EXPENSES</span>
            </div>
            <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.25rem;">{{ number_format($stats['total_expenses'], 2) }}</div>
            <div style="font-size: 0.875rem; opacity: 0.9;">Total Expenses</div>
        </div>

        <!-- Net Balance -->
        <div style="background: linear-gradient(135deg, var(--primary-color), #1a1f38); border-radius: 12px; padding: 1.5rem; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                <div style="font-size: 2.5rem;">📊</div>
                <span style="background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">NET</span>
            </div>
            <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.25rem;">{{ number_format($stats['total_revenue'] - $stats['total_expenses'], 2) }}</div>
            <div style="font-size: 0.875rem; opacity: 0.9;">Net Balance</div>
        </div>

        <!-- Pending Transactions -->
        <div style="background: linear-gradient(135deg, #4a90e2, #357abd); border-radius: 12px; padding: 1.5rem; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                <div style="font-size: 2.5rem;">⏳</div>
                <span style="background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">PENDING</span>
            </div>
            <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.25rem;">{{ number_format($stats['pending_transactions']) }}</div>
            <div style="font-size: 0.875rem; opacity: 0.9;">Pending Transactions</div>
        </div>
    </div>

    <!-- Charts Row -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(500px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <!-- Revenue vs Expenses Chart -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1.5rem;">Revenue vs Expenses (Last 6 Months)</h3>
            <canvas id="revenueExpenseChart" style="max-height: 300px;"></canvas>
        </div>

        <!-- Transaction Distribution -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1.5rem;">Transaction Distribution</h3>
            <canvas id="transactionPieChart" style="max-height: 300px;"></canvas>
        </div>
    </div>

    <!-- Quick Actions & Recent Reconciliations -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem;">
        <!-- Quick Actions -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1.5rem;">Quick Actions</h3>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <a href="{{ route('staff.finance.transactions') }}" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f5f5f5; border-radius: 8px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#e8e8e8'" onmouseout="this.style.background='#f5f5f5'">
                    <div style="width: 48px; height: 48px; background: var(--primary-color); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">📋</div>
                    <div>
                        <div style="font-weight: 600; color: var(--primary-color);">View All Transactions</div>
                        <div style="font-size: 0.875rem; color: #666;">Monitor transaction history</div>
                    </div>
                </a>
                <a href="{{ route('staff.finance.reconciliation') }}" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f5f5f5; border-radius: 8px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#e8e8e8'" onmouseout="this.style.background='#f5f5f5'">
                    <div style="width: 48px; height: 48px; background: var(--secondary-color); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">🔄</div>
                    <div>
                        <div style="font-weight: 600; color: var(--primary-color);">Reconciliation Dashboard</div>
                        <div style="font-size: 0.875rem; color: #666;">Track reconciliation status</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Reconciliations -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1.5rem;">Recent Reconciliations</h3>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @forelse($recentReconciliations as $recon)
                    <div style="padding: 0.75rem; border-left: 3px solid {{ $recon->status === 'matched' ? 'var(--secondary-color)' : ($recon->status === 'discrepancy' ? 'var(--accent-color)' : '#999') }}; background: #f9f9f9; border-radius: 4px;">
                        <div style="display: flex; justify-content: between; align-items: start; margin-bottom: 0.25rem;">
                            <div style="font-weight: 600; color: var(--primary-color); font-size: 0.875rem;">{{ ucfirst($recon->reconciliation_type) }}</div>
                            <span style="padding: 0.125rem 0.5rem; background: {{ $recon->status === 'matched' ? '#d4edda' : ($recon->status === 'discrepancy' ? '#f8d7da' : '#e0e0e0') }}; color: {{ $recon->status === 'matched' ? 'var(--secondary-color)' : ($recon->status === 'discrepancy' ? 'var(--accent-color)' : '#666') }}; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">{{ strtoupper($recon->status) }}</span>
                        </div>
                        <div style="font-size: 0.75rem; color: #666;">{{ $recon->reconciliation_date->format('M d, Y') }} • Diff: {{ number_format(abs($recon->difference), 2) }}</div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 2rem; color: #999;">No reconciliations yet</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue vs Expenses Chart
    const revenueExpenseCtx = document.getElementById('revenueExpenseChart').getContext('2d');
    new Chart(revenueExpenseCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyData->pluck('month')) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode($monthlyData->pluck('revenue')) !!},
                borderColor: '#5B8040',
                backgroundColor: 'rgba(91, 128, 64, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Expenses',
                data: {!! json_encode($monthlyData->pluck('expenses')) !!},
                borderColor: '#DE811D',
                backgroundColor: 'rgba(222, 129, 29, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Transaction Distribution Pie Chart
    const pieCtx = document.getElementById('transactionPieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: ['Completed', 'Pending', 'Failed'],
            datasets: [{
                data: [{{ $stats['total_transactions'] - $stats['pending_transactions'] }}, {{ $stats['pending_transactions'] }}, 0],
                backgroundColor: ['#5B8040', '#4a90e2', '#e74c3c']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endsection
