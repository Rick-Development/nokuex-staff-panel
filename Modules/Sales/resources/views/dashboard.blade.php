@extends('core::layouts.master')

@section('content')
<div style="padding: 2rem; max-width: 1600px; margin: 0 auto;">
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 2rem; font-weight: bold; color: var(--primary-color); margin-bottom: 0.5rem;">Sales Dashboard</h1>
        <p style="color: #666;">Track leads, conversions, and sales performance</p>
    </div>

    <!-- KPI Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <!-- Total Leads -->
        <div style="background: linear-gradient(135deg, var(--primary-color), #1a1f38); border-radius: 12px; padding: 1.5rem; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                <div style="font-size: 2.5rem;">📊</div>
                <span style="background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">TOTAL</span>
            </div>
            <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.25rem;">{{ number_format($totalLeads) }}</div>
            <div style="font-size: 0.875rem; opacity: 0.9;">Total Leads</div>
        </div>

        <!-- New Leads -->
        <div style="background: linear-gradient(135deg, #4a90e2, #357abd); border-radius: 12px; padding: 1.5rem; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                <div style="font-size: 2.5rem;">🆕</div>
                <span style="background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">NEW</span>
            </div>
            <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.25rem;">{{ number_format($newLeads) }}</div>
            <div style="font-size: 0.875rem; opacity: 0.9;">New Leads</div>
        </div>

        <!-- Converted -->
        <div style="background: linear-gradient(135deg, var(--secondary-color), #4a6633); border-radius: 12px; padding: 1.5rem; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                <div style="font-size: 2.5rem;">✅</div>
                <span style="background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">CONVERTED</span>
            </div>
            <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.25rem;">{{ number_format($convertedLeads) }}</div>
            <div style="font-size: 0.875rem; opacity: 0.9;">Converted Leads</div>
        </div>

        <!-- Conversion Rate -->
        <div style="background: linear-gradient(135deg, var(--accent-color), #c67316); border-radius: 12px; padding: 1.5rem; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                <div style="font-size: 2.5rem;">📈</div>
                <span style="background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">RATE</span>
            </div>
            <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.25rem;">{{ number_format($conversionRate, 1) }}%</div>
            <div style="font-size: 0.875rem; opacity: 0.9;">Conversion Rate</div>
        </div>
    </div>

    <!-- Charts Row -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(500px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <!-- Pipeline Chart -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1.5rem;">Sales Pipeline</h3>
            <canvas id="pipelineChart" style="max-height: 300px;"></canvas>
        </div>

        <!-- Lead Sources -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1.5rem;">Lead Sources</h3>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @foreach($topSources as $source)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 40px; height: 40px; background: var(--primary-color); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                {{ substr(ucfirst($source->source), 0, 1) }}
                            </div>
                            <span style="font-weight: 600; color: var(--primary-color); text-transform: capitalize;">{{ str_replace('_', ' ', $source->source) }}</span>
                        </div>
                        <span style="font-size: 1.5rem; font-weight: bold; color: var(--secondary-color);">{{ $source->count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Leads & Quick Actions -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem;">
        <!-- Recent Leads -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--primary-color); margin: 0;">Recent Leads</h3>
                <a href="{{ route('staff.sales.leads.index') }}" style="color: var(--secondary-color); text-decoration: none; font-weight: 600; font-size: 0.875rem;">View All</a>
            </div>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @forelse($recentLeads as $lead)
                    <div style="padding: 0.75rem; border-left: 3px solid {{ $lead->status === 'converted' ? 'var(--secondary-color)' : ($lead->status === 'new' ? '#4a90e2' : '#999') }}; background: #f9f9f9; border-radius: 4px;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.25rem;">
                            <div style="font-weight: 600; color: var(--primary-color); font-size: 0.875rem;">{{ $lead->name }}</div>
                            <span style="padding: 0.125rem 0.5rem; background: {{ $lead->status === 'converted' ? '#d4edda' : ($lead->status === 'new' ? '#cce5ff' : '#e0e0e0') }}; color: {{ $lead->status === 'converted' ? 'var(--secondary-color)' : ($lead->status === 'new' ? '#004085' : '#666') }}; border-radius: 12px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">{{ $lead->status }}</span>
                        </div>
                        <div style="font-size: 0.75rem; color: #666;">{{ $lead->email ?? 'No email' }} • Assigned: {{ $lead->assignedStaff ? $lead->assignedStaff->name : 'Unassigned' }}</div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 2rem; color: #999;">No leads yet</div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1.5rem;">Quick Actions</h3>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <a href="{{ route('staff.sales.leads.index') }}" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f5f5f5; border-radius: 8px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#e8e8e8'" onmouseout="this.style.background='#f5f5f5'">
                    <div style="width: 48px; height: 48px; background: var(--primary-color); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">📋</div>
                    <div>
                        <div style="font-weight: 600; color: var(--primary-color);">View All Leads</div>
                        <div style="font-size: 0.875rem; color: #666;">Manage your sales pipeline</div>
                    </div>
                </a>
                <a href="#" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f5f5f5; border-radius: 8px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#e8e8e8'" onmouseout="this.style.background='#f5f5f5'">
                    <div style="width: 48px; height: 48px; background: var(--secondary-color); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">➕</div>
                    <div>
                        <div style="font-weight: 600; color: var(--primary-color);">Add New Lead</div>
                        <div style="font-size: 0.875rem; color: #666;">Create a new lead entry</div>
                    </div>
                </a>
                <a href="#" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f5f5f5; border-radius: 8px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#e8e8e8'" onmouseout="this.style.background='#f5f5f5'">
                    <div style="width: 48px; height: 48px; background: var(--accent-color); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">📊</div>
                    <div>
                        <div style="font-weight: 600; color: var(--primary-color);">Sales Reports</div>
                        <div style="font-size: 0.875rem; color: #666;">View detailed analytics</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Pipeline Chart
    const pipelineCtx = document.getElementById('pipelineChart').getContext('2d');
    new Chart(pipelineCtx, {
        type: 'doughnut',
        data: {
            labels: ['New', 'Contacted', 'Qualified', 'Converted', 'Lost'],
            datasets: [{
                data: [
                    {{ $pipelineData['new'] ?? 0 }},
                    {{ $pipelineData['contacted'] ?? 0 }},
                    {{ $pipelineData['qualified'] ?? 0 }},
                    {{ $pipelineData['converted'] ?? 0 }},
                    {{ $pipelineData['lost'] ?? 0 }}
                ],
                backgroundColor: ['#4a90e2', '#f39c12', '#9b59b6', '#5B8040', '#e74c3c']
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
