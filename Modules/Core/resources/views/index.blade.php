@extends('core::layouts.master')

@section('content')
<div style="padding: 2rem; max-width: 1400px; margin: 0 auto;">
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 2rem; font-weight: bold; color: var(--primary-color); margin-bottom: 0.5rem;">
            Welcome back, {{ Auth::guard('staff')->user()->name }}!
        </h1>
        <p style="color: #666;">Here's what's happening with your platform today</p>
    </div>

    @php
        $userRole = Auth::guard('staff')->user()->role ?? 'staff';
        
        // Get statistics
        $totalUsers = DB::table('users')->count();
        $totalTransactions = DB::table('transactions')->count();
        $totalRevenue = DB::table('transactions')->where('trx_type', '+')->where('status', 'success')->sum('amount');
        $pendingTickets = DB::table('staff_support_tickets')->where('status', 'open')->count();
        $pendingDisputes = DB::table('staff_disputes')->where('status', 'pending')->count();
    @endphp

    <!-- KPI Overview Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <!-- Total Users -->
        <div style="background: linear-gradient(135deg, var(--primary-color), #1a1f38); border-radius: 12px; padding: 1.5rem; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                <div style="font-size: 2.5rem;">👥</div>
                <span style="background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">USERS</span>
            </div>
            <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.25rem;">{{ number_format($totalUsers) }}</div>
            <div style="font-size: 0.875rem; opacity: 0.9;">Total Platform Users</div>
        </div>

        <!-- Total Transactions -->
        <div style="background: linear-gradient(135deg, var(--secondary-color), #4a6633); border-radius: 12px; padding: 1.5rem; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                <div style="font-size: 2.5rem;">💳</div>
                <span style="background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">TRANSACTIONS</span>
            </div>
            <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.25rem;">{{ number_format($totalTransactions) }}</div>
            <div style="font-size: 0.875rem; opacity: 0.9;">Total Transactions</div>
        </div>

        <!-- Total Revenue -->
        <div style="background: linear-gradient(135deg, var(--accent-color), #c67316); border-radius: 12px; padding: 1.5rem; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                <div style="font-size: 2.5rem;">💰</div>
                <span style="background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">REVENUE</span>
            </div>
            <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.25rem;">₦{{ number_format($totalRevenue, 2) }}</div>
            <div style="font-size: 0.875rem; opacity: 0.9;">Total Revenue</div>
        </div>

        <!-- Pending Items -->
        <div style="background: linear-gradient(135deg, #4a90e2, #357abd); border-radius: 12px; padding: 1.5rem; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                <div style="font-size: 2.5rem;">⏳</div>
                <span style="background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">PENDING</span>
            </div>
            <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.25rem;">{{ $pendingTickets + $pendingDisputes }}</div>
            <div style="font-size: 0.875rem; opacity: 0.9;">Tickets & Disputes</div>
        </div>
    </div>

    <!-- Charts Section -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        @php
            // Get monthly transaction data for chart
            $monthlyData = DB::table('transactions')
                ->select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('COUNT(*) as count'),
                    DB::raw('SUM(CASE WHEN trx_type = "+" AND status = "success" THEN amount ELSE 0 END) as revenue')
                )
                ->where('created_at', '>=', now()->subMonths(6))
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        @endphp

        <!-- Transaction Trend Chart -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1.5rem;">Transaction Trend (Last 6 Months)</h3>
            <canvas id="transactionTrendChart" style="max-height: 300px;"></canvas>
        </div>

        <!-- Revenue Chart -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1.5rem;">Revenue Overview (Last 6 Months)</h3>
            <canvas id="revenueChart" style="max-height: 300px;"></canvas>
        </div>
    </div>

    <!-- Quick Actions Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
        @if($userRole === 'admin' || $userRole === 'customer_care' || empty($userRole))
        <!-- Customer Care -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1.5rem;">Customer Care</h3>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <a href="{{ route('staff.customers.index') }}" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f5f5f5; border-radius: 8px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#e8e8e8'" onmouseout="this.style.background='#f5f5f5'">
                    <div style="width: 48px; height: 48px; background: var(--primary-color); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">👥</div>
                    <div>
                        <div style="font-weight: 600; color: var(--primary-color);">Manage Customers</div>
                        <div style="font-size: 0.875rem; color: #666;">{{ number_format($totalUsers) }} users</div>
                    </div>
                </a>
                <a href="{{ route('staff.tickets.index') }}" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f5f5f5; border-radius: 8px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#e8e8e8'" onmouseout="this.style.background='#f5f5f5'">
                    <div style="width: 48px; height: 48px; background: var(--secondary-color); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">🎫</div>
                    <div>
                        <div style="font-weight: 600; color: var(--primary-color);">Support Tickets</div>
                        <div style="font-size: 0.875rem; color: #666;">{{ $pendingTickets }} pending</div>
                    </div>
                </a>
                <a href="{{ route('staff.disputes.index') }}" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f5f5f5; border-radius: 8px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#e8e8e8'" onmouseout="this.style.background='#f5f5f5'">
                    <div style="width: 48px; height: 48px; background: var(--accent-color); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">⚖️</div>
                    <div>
                        <div style="font-weight: 600; color: var(--primary-color);">Disputes</div>
                        <div style="font-size: 0.875rem; color: #666;">{{ $pendingDisputes }} pending</div>
                    </div>
                </a>
            </div>
        </div>
        @endif

        @if($userRole === 'admin' || $userRole === 'finance' || empty($userRole))
        <!-- Finance -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1.5rem;">Finance</h3>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <a href="{{ route('staff.finance.dashboard') }}" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f5f5f5; border-radius: 8px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#e8e8e8'" onmouseout="this.style.background='#f5f5f5'">
                    <div style="width: 48px; height: 48px; background: var(--secondary-color); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">📊</div>
                    <div>
                        <div style="font-weight: 600; color: var(--primary-color);">Finance Dashboard</div>
                        <div style="font-size: 0.875rem; color: #666;">View analytics</div>
                    </div>
                </a>
                <a href="{{ route('staff.finance.transactions') }}" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f5f5f5; border-radius: 8px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#e8e8e8'" onmouseout="this.style.background='#f5f5f5'">
                    <div style="width: 48px; height: 48px; background: var(--primary-color); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">💳</div>
                    <div>
                        <div style="font-weight: 600; color: var(--primary-color);">Transactions</div>
                        <div style="font-size: 0.875rem; color: #666;">{{ number_format($totalTransactions) }} total</div>
                    </div>
                </a>
                <a href="{{ route('staff.finance.reconciliation') }}" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f5f5f5; border-radius: 8px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#e8e8e8'" onmouseout="this.style.background='#f5f5f5'">
                    <div style="width: 48px; height: 48px; background: var(--accent-color); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">🔄</div>
                    <div>
                        <div style="font-weight: 600; color: var(--primary-color);">Reconciliation</div>
                        <div style="font-size: 0.875rem; color: #666;">Track balances</div>
                    </div>
                </a>
            </div>
        </div>
        @endif

        <!-- Communication -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1.5rem;">Communication</h3>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <a href="{{ route('staff.chat.index') }}" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f5f5f5; border-radius: 8px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#e8e8e8'" onmouseout="this.style.background='#f5f5f5'">
                    <div style="width: 48px; height: 48px; background: var(--primary-color); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">💬</div>
                    <div>
                        <div style="font-weight: 600; color: var(--primary-color);">Internal Chat</div>
                        <div style="font-size: 0.875rem; color: #666;">Message team members</div>
                    </div>
                </a>
                <div style="padding: 1rem; background: #f9f9f9; border-radius: 8px; border-left: 4px solid var(--accent-color);">
                    <div style="font-weight: 600; color: var(--primary-color); margin-bottom: 0.5rem;">Quick Stats</div>
                    <div style="font-size: 0.875rem; color: #666; line-height: 1.6;">
                        <div>• Active Users: {{ number_format($totalUsers) }}</div>
                        <div>• Total Revenue: ₦{{ number_format($totalRevenue, 0) }}</div>
                        <div>• Open Tickets: {{ $pendingTickets }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Transaction Trend Chart
    const trendCtx = document.getElementById('transactionTrendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthlyData->pluck('month')) !!},
            datasets: [{
                label: 'Transactions',
                data: {!! json_encode($monthlyData->pluck('count')) !!},
                backgroundColor: 'rgba(91, 128, 64, 0.8)',
                borderColor: '#5B8040',
                borderWidth: 2,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyData->pluck('month')) !!},
            datasets: [{
                label: 'Revenue (₦)',
                data: {!! json_encode($monthlyData->pluck('revenue')) !!},
                borderColor: '#DE811D',
                backgroundColor: 'rgba(222, 129, 29, 0.1)',
                tension: 0.4,
                fill: true,
                borderWidth: 3
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
                            return '₦' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
