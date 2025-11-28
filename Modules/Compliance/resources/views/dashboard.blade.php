@extends('core::layouts.master')

@section('content')
<div style="padding: 2rem; max-width: 1600px; margin: 0 auto;">
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 2rem; font-weight: bold; color: var(--primary-color); margin-bottom: 0.5rem;">Compliance Dashboard</h1>
        <p style="color: #666;">Monitor KYC reviews, compliance flags, and account actions</p>
    </div>

    <!-- KYC Stats -->
    <div style="margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.25rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1rem;">KYC/KYB Reviews</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem;">
            <div style="background: linear-gradient(135deg, #4a90e2, #357abd); border-radius: 12px; padding: 1.5rem; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <div style="font-size: 2.5rem;">⏳</div>
                    <span style="background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">PENDING</span>
                </div>
                <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.25rem;">{{ number_format($pendingKyc) }}</div>
                <div style="font-size: 0.875rem; opacity: 0.9;">Pending Review</div>
            </div>

            <div style="background: linear-gradient(135deg, #f39c12, #e67e22); border-radius: 12px; padding: 1.5rem; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <div style="font-size: 2.5rem;">🔍</div>
                    <span style="background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">IN REVIEW</span>
                </div>
                <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.25rem;">{{ number_format($inReviewKyc) }}</div>
                <div style="font-size: 0.875rem; opacity: 0.9;">Under Review</div>
            </div>

            <div style="background: linear-gradient(135deg, var(--secondary-color), #4a6633); border-radius: 12px; padding: 1.5rem; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <div style="font-size: 2.5rem;">✅</div>
                    <span style="background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">APPROVED</span>
                </div>
                <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.25rem;">{{ number_format($approvedKyc) }}</div>
                <div style="font-size: 0.875rem; opacity: 0.9;">Approved</div>
            </div>

            <div style="background: linear-gradient(135deg, #e74c3c, #c0392b); border-radius: 12px; padding: 1.5rem; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <div style="font-size: 2.5rem;">❌</div>
                    <span style="background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">REJECTED</span>
                </div>
                <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.25rem;">{{ number_format($rejectedKyc) }}</div>
                <div style="font-size: 0.875rem; opacity: 0.9;">Rejected</div>
            </div>
        </div>
    </div>

    <!-- Compliance Flags -->
    <div style="margin-bottom: 2rem;">
        <h2 style="font-size: 1.25rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1rem;">Compliance Flags</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
            <div style="background: linear-gradient(135deg, #c0392b, #8e2c1f); border-radius: 12px; padding: 1.5rem; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <div style="font-size: 2.5rem;">🚨</div>
                    <span style="background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">CRITICAL</span>
                </div>
                <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.25rem;">{{ number_format($criticalFlags) }}</div>
                <div style="font-size: 0.875rem; opacity: 0.9;">Critical Flags</div>
            </div>

            <div style="background: linear-gradient(135deg, var(--accent-color), #c67316); border-radius: 12px; padding: 1.5rem; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <div style="font-size: 2.5rem;">⚠️</div>
                    <span style="background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">HIGH</span>
                </div>
                <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.25rem;">{{ number_format($highFlags) }}</div>
                <div style="font-size: 0.875rem; opacity: 0.9;">High Priority Flags</div>
            </div>

            <div style="background: linear-gradient(135deg, var(--primary-color), #1a1f38); border-radius: 12px; padding: 1.5rem; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <div style="font-size: 2.5rem;">📊</div>
                    <span style="background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">TOTAL</span>
                </div>
                <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.25rem;">{{ number_format($totalPendingFlags) }}</div>
                <div style="font-size: 0.875rem; opacity: 0.9;">Pending Flags</div>
            </div>
        </div>
    </div>

    <!-- Charts & Recent Activity -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(500px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <!-- Flag Distribution Chart -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1.5rem;">Flag Severity Distribution</h3>
            <canvas id="flagChart" style="max-height: 300px;"></canvas>
        </div>

        <!-- Quick Actions -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1.5rem;">Quick Actions</h3>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <a href="{{ route('staff.compliance.kyc.index') }}" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f5f5f5; border-radius: 8px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#e8e8e8'" onmouseout="this.style.background='#f5f5f5'">
                    <div style="width: 48px; height: 48px; background: var(--primary-color); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">📋</div>
                    <div>
                        <div style="font-weight: 600; color: var(--primary-color);">Review KYC Submissions</div>
                        <div style="font-size: 0.875rem; color: #666;">Approve or reject pending reviews</div>
                    </div>
                </a>
                <a href="{{ route('staff.compliance.flags.index') }}" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f5f5f5; border-radius: 8px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#e8e8e8'" onmouseout="this.style.background='#f5f5f5'">
                    <div style="width: 48px; height: 48px; background: var(--accent-color); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">🚩</div>
                    <div>
                        <div style="font-weight: 600; color: var(--primary-color);">Manage Compliance Flags</div>
                        <div style="font-size: 0.875rem; color: #666;">Review and resolve flagged accounts</div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem;">
        <!-- Recent KYC Reviews -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--primary-color); margin: 0;">Recent KYC Reviews</h3>
                <a href="{{ route('staff.compliance.kyc.index') }}" style="color: var(--secondary-color); text-decoration: none; font-weight: 600; font-size: 0.875rem;">View All</a>
            </div>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @forelse($recentKycReviews as $review)
                    <a href="{{ route('staff.compliance.kyc.show', $review->id) }}" style="padding: 0.75rem; border-left: 3px solid {{ $review->status === 'approved' ? 'var(--secondary-color)' : ($review->status === 'pending' ? '#4a90e2' : '#e74c3c') }}; background: #f9f9f9; border-radius: 4px; text-decoration: none; display: block;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.25rem;">
                            <div style="font-weight: 600; color: var(--primary-color); font-size: 0.875rem;">{{ $review->user->first_name }} {{ $review->user->last_name }}</div>
                            <span style="padding: 0.125rem 0.5rem; background: {{ $review->status === 'approved' ? '#d4edda' : ($review->status === 'pending' ? '#cce5ff' : '#f8d7da') }}; color: {{ $review->status === 'approved' ? 'var(--secondary-color)' : ($review->status === 'pending' ? '#004085' : '#721c24') }}; border-radius: 12px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">{{ $review->status }}</span>
                        </div>
                        <div style="font-size: 0.75rem; color: #666;">{{ ucfirst($review->review_type) }} • {{ $review->created_at->diffForHumans() }}</div>
                    </a>
                @empty
                    <div style="text-align: center; padding: 2rem; color: #999;">No recent reviews</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Flags -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--primary-color); margin: 0;">Recent Compliance Flags</h3>
                <a href="{{ route('staff.compliance.flags.index') }}" style="color: var(--secondary-color); text-decoration: none; font-weight: 600; font-size: 0.875rem;">View All</a>
            </div>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @forelse($recentFlags as $flag)
                    <div style="padding: 0.75rem; border-left: 3px solid {{ $flag->severity === 'critical' ? '#c0392b' : ($flag->severity === 'high' ? 'var(--accent-color)' : '#999') }}; background: #f9f9f9; border-radius: 4px;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.25rem;">
                            <div style="font-weight: 600; color: var(--primary-color); font-size: 0.875rem;">{{ $flag->user->first_name }} {{ $flag->user->last_name }}</div>
                            <span style="padding: 0.125rem 0.5rem; background: {{ $flag->severity === 'critical' ? '#f8d7da' : ($flag->severity === 'high' ? '#fff3cd' : '#e0e0e0') }}; color: {{ $flag->severity === 'critical' ? '#721c24' : ($flag->severity === 'high' ? '#856404' : '#666') }}; border-radius: 12px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">{{ $flag->severity }}</span>
                        </div>
                        <div style="font-size: 0.75rem; color: #666;">{{ $flag->flag_type }} • {{ $flag->created_at->diffForHumans() }}</div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 2rem; color: #999;">No recent flags</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Flag Distribution Chart
    const flagCtx = document.getElementById('flagChart').getContext('2d');
    new Chart(flagCtx, {
        type: 'doughnut',
        data: {
            labels: ['Low', 'Medium', 'High', 'Critical'],
            datasets: [{
                data: [
                    {{ $flagDistribution['low'] ?? 0 }},
                    {{ $flagDistribution['medium'] ?? 0 }},
                    {{ $flagDistribution['high'] ?? 0 }},
                    {{ $flagDistribution['critical'] ?? 0 }}
                ],
                backgroundColor: ['#95a5a6', '#3498db', '#f39c12', '#e74c3c']
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
