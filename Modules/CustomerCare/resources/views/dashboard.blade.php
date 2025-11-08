@extends('core::layouts.app')

@section('title', 'Customer Care Dashboard')

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ \Modules\CustomerCare\Entities\Customer::count() }}</div>
        <div class="stat-label">Total Customers</div>
    </div>
    
    <div class="stat-card success">
        <div class="stat-value">{{ \Modules\CustomerCare\Entities\Customer::where('status', 'active')->count() }}</div>
        <div class="stat-label">Active Customers</div>
    </div>
    
    <div class="stat-card warning">
        <div class="stat-value">{{ \Modules\CustomerCare\Entities\SupportTicket::where('status', 'open')->orWhere('status', 'in_progress')->count() }}</div>
        <div class="stat-label">Active Tickets</div>
    </div>
    
    <div class="stat-card info">
        <div class="stat-value">{{ \Modules\CustomerCare\Entities\Dispute::where('status', 'open')->count() }}</div>
        <div class="stat-label">Open Disputes</div>
    </div>
</div>

<div class="card">
    <h2>Customer Care Dashboard</h2>
    <p>Welcome to the Customer Care management system. Manage your customers, support tickets, and dispute resolutions efficiently.</p>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2rem;">
        <div>
            <h3 style="margin-bottom: 1rem; color: var(--primary-color);">Quick Actions</h3>
            <div style="display: grid; grid-template-columns: 1fr; gap: 0.5rem;">
                <a href="{{ route('customercare.crm.create') }}" class="btn btn-primary" style="justify-content: start;">
                    <i>➕</i> Add New Customer
                </a>
                <a href="{{ route('customercare.tickets') }}" class="btn btn-success" style="justify-content: start;">
                    <i>🎫</i> Create Support Ticket
                </a>
                <a href="{{ route('customercare.disputes') }}" class="btn btn-warning" style="justify-content: start;">
                    <i>⚖️</i> Manage Disputes
                </a>
            </div>
        </div>
        
        <div>
            <h3 style="margin-bottom: 1rem; color: var(--primary-color);">Recent Activity</h3>
            <div style="max-height: 200px; overflow-y: auto;">
                @php
                    $recentTickets = \Modules\CustomerCare\Entities\SupportTicket::with(['customer', 'assignedTo'])
                        ->latest()
                        ->take(5)
                        ->get();
                @endphp
                
                @foreach($recentTickets as $ticket)
                <div style="padding: 0.75rem; border-bottom: 1px solid #eee;">
                    <div style="display: flex; justify-content: between; align-items: start;">
                        <div style="flex: 1;">
                            <div style="font-weight: 600;">{{ $ticket->subject }}</div>
                            <div style="font-size: 0.8rem; color: #666;">
                                {{ $ticket->customer->name }} • 
                                <span class="badge badge-{{ $ticket->priority === 'high' ? 'danger' : ($ticket->priority === 'medium' ? 'warning' : 'success') }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </div>
                        </div>
                        <div style="font-size: 0.8rem; color: #999;">
                            {{ $ticket->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
                @endforeach
                
                @if($recentTickets->isEmpty())
                <div style="text-align: center; color: #666; padding: 1rem;">
                    No recent tickets
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card">
    <h3>System Modules</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-top: 1rem;">
        <a href="{{ route('customercare.crm') }}" class="btn btn-primary" style="justify-content: center; flex-direction: column; text-align: center; padding: 1.5rem;">
            <div style="font-size: 2rem; margin-bottom: 0.5rem;">👥</div>
            <div>CRM System</div>
            <small style="opacity: 0.8;">Customer database & management</small>
        </a>
        
        <a href="{{ route('customercare.tickets') }}" class="btn btn-success" style="justify-content: center; flex-direction: column; text-align: center; padding: 1.5rem;">
            <div style="font-size: 2rem; margin-bottom: 0.5rem;">🎫</div>
            <div>Support Tickets</div>
            <small style="opacity: 0.8;">Ticket management system</small>
        </a>
        
        <a href="{{ route('customercare.disputes') }}" class="btn btn-warning" style="justify-content: center; flex-direction: column; text-align: center; padding: 1.5rem;">
            <div style="font-size: 2rem; margin-bottom: 0.5rem;">⚖️</div>
            <div>Dispute Resolution</div>
            <small style="opacity: 0.8;">Handle customer disputes</small>
        </a>
    </div>
</div>
@endsection