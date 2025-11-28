@extends('core::layouts.master')

@section('content')
<div style="padding: 2rem; max-width: 1600px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <a href="{{ route('staff.sales.dashboard') }}" style="color: var(--primary-color); text-decoration: none;">&larr; Back to Dashboard</a>
            <h1 style="font-size: 2rem; font-weight: bold; color: var(--primary-color); margin: 0.5rem 0 0 0;">Leads Management</h1>
        </div>
        <div>
            <a href="{{ route('staff.sales.leads.create') }}" style="padding: 0.75rem 1.5rem; background: var(--secondary-color); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block;">
                ➕ Add New Lead
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 1.5rem; margin-bottom: 1.5rem;">
        <form action="{{ route('staff.sales.leads.index') }}" method="GET">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-weight: 600; color: var(--primary-color); margin-bottom: 0.5rem; font-size: 0.875rem;">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email..." style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px; outline: none;" onfocus="this.style.borderColor='var(--primary-color)'" onblur="this.style.borderColor='#e0e0e0'">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; color: var(--primary-color); margin-bottom: 0.5rem; font-size: 0.875rem;">Status</label>
                    <select name="status" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px; outline: none;">
                        <option value="">All Statuses</option>
                        <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                        <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                        <option value="qualified" {{ request('status') == 'qualified' ? 'selected' : '' }}>Qualified</option>
                        <option value="converted" {{ request('status') == 'converted' ? 'selected' : '' }}>Converted</option>
                        <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>Lost</option>
                    </select>
                </div>
                <div style="display: flex; align-items: end; gap: 0.5rem;">
                    <button type="submit" style="flex: 1; padding: 0.75rem; background: var(--primary-color); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        🔍 Filter
                    </button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('staff.sales.leads.index') }}" style="padding: 0.75rem 1rem; background: #f5f5f5; color: #666; text-decoration: none; border-radius: 8px; font-weight: 600;">
                            ✕
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Leads Grid -->
    <div style="display: grid; gap: 1rem;">
        @forelse($leads as $lead)
            <a href="{{ route('staff.sales.leads.edit', $lead->id) }}" style="text-decoration: none; display: block; background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.2s; cursor: pointer;" onmouseover="this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'" onmouseout="this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
                <div style="display: grid; grid-template-columns: auto 1fr auto auto; gap: 1.5rem; align-items: center;">
                    <!-- Avatar -->
                    <div style="width: 56px; height: 56px; border-radius: 12px; background: linear-gradient(135deg, var(--primary-color), #3a4b7c); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white; font-weight: bold;">
                        {{ substr($lead->name, 0, 1) }}
                    </div>

                    <!-- Lead Info -->
                    <div>
                        <div style="font-weight: 600; color: var(--primary-color); font-size: 1.125rem; margin-bottom: 0.25rem;">
                            {{ $lead->name }}
                        </div>
                        <div style="display: flex; align-items: center; gap: 1rem; font-size: 0.875rem; color: #666;">
                            <span>{{ $lead->email ?? 'No email' }}</span>
                            @if($lead->phone)
                                <span>•</span>
                                <span>{{ $lead->phone }}</span>
                            @endif
                        </div>
                        <div style="font-size: 0.875rem; color: #999; margin-top: 0.25rem;">
                            Source: <span style="text-transform: capitalize;">{{ str_replace('_', ' ', $lead->source) }}</span> • 
                            Assigned: {{ $lead->assignedStaff ? $lead->assignedStaff->name : 'Unassigned' }}
                        </div>
                    </div>

                    <!-- Status Badge -->
                    <div>
                        <span style="padding: 0.5rem 1rem; background: {{ $lead->status === 'converted' ? '#d4edda' : ($lead->status === 'new' ? '#cce5ff' : ($lead->status === 'qualified' ? '#e7d4f7' : ($lead->status === 'contacted' ? '#fff3cd' : '#f8d7da'))) }}; color: {{ $lead->status === 'converted' ? '#155724' : ($lead->status === 'new' ? '#004085' : ($lead->status === 'qualified' ? '#6f42c1' : ($lead->status === 'contacted' ? '#856404' : '#721c24'))) }}; border-radius: 20px; font-size: 0.875rem; font-weight: 600; text-transform: uppercase;">
                            {{ $lead->status }}
                        </span>
                    </div>

                    <!-- Date -->
                    <div style="text-align: right;">
                        <div style="font-size: 0.875rem; color: #999;">
                            {{ $lead->created_at->format('M d, Y') }}
                        </div>
                        @if($lead->next_follow_up_at)
                            <div style="font-size: 0.75rem; color: var(--accent-color); font-weight: 600; margin-top: 0.25rem;">
                                Follow-up: {{ \Carbon\Carbon::parse($lead->next_follow_up_at)->format('M d') }}
                            </div>
                        @endif
                    </div>
                </div>
            </a>
        @empty
            <div style="background: white; border-radius: 12px; padding: 4rem; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">📭</div>
                <h3 style="color: var(--primary-color); margin-bottom: 0.5rem;">No leads found</h3>
                <p style="color: #666;">Try adjusting your filters or add a new lead</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div style="margin-top: 2rem;">
        {{ $leads->links() }}
    </div>
</div>
@endsection
