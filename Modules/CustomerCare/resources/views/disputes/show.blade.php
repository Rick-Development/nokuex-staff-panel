@extends('core::layouts.master')

@section('content')
<div style="padding: 2rem; max-width: 1400px; margin: 0 auto;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <a href="{{ route('staff.disputes.index') }}" style="color: var(--primary-color); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 500; margin-bottom: 0.5rem;">
                &larr; Back to Disputes
            </a>
            <h1 style="font-size: 1.75rem; font-weight: bold; color: var(--primary-color); margin: 0;">
                <span style="color: #999; font-weight: 400;">#{{ $dispute->dispute_number }}</span> {{ $dispute->subject }}
            </h1>
            <div style="display: flex; align-items: center; gap: 1rem; margin-top: 0.5rem; color: #666;">
                <span>Filed by <a href="{{ route('staff.customers.show', $dispute->user_id) }}" style="color: var(--secondary-color); text-decoration: none; font-weight: 600;">{{ $dispute->user->first_name }} {{ $dispute->user->last_name }}</a></span>
                <span>•</span>
                <span>{{ $dispute->created_at->format('M d, Y H:i') }}</span>
            </div>
        </div>
        
        <div style="display: flex; gap: 1rem; align-items: center;">
            <span style="padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; 
                {{ $dispute->status === 'open' ? 'background: #f8d7da; color: #721c24;' : 
                   ($dispute->status === 'investigating' ? 'background: #fff3cd; color: #856404;' : 
                   ($dispute->status === 'resolved' ? 'background: #d4edda; color: #155724;' : 'background: #e2e3e5; color: #383d41;')) }}">
                {{ str_replace('_', ' ', $dispute->status) }}
            </span>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 350px; gap: 2rem;">
        <!-- Main Content -->
        <div style="display: flex; flex-direction: column; gap: 2rem;">
            <!-- Description -->
            <div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1rem; border-bottom: 1px solid #eee; padding-bottom: 0.5rem;">Description</h3>
                <div style="color: #333; line-height: 1.6; white-space: pre-wrap;">{{ $dispute->description }}</div>
            </div>

            <!-- Resolution Display -->
            @if($dispute->resolution)
            <div style="background: #f0fff4; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-left: 4px solid var(--secondary-color);">
                <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--secondary-color); margin-bottom: 1rem;">Resolution</h3>
                <div style="color: #333; line-height: 1.6; white-space: pre-wrap;">{{ $dispute->resolution }}</div>
                <div style="margin-top: 1rem; font-size: 0.875rem; color: #666;">
                    Resolved on {{ $dispute->resolved_at ? $dispute->resolved_at->format('M d, Y H:i') : 'N/A' }}
                </div>
            </div>
            @endif

            <!-- Update Form -->
            <div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1.5rem;">Update Dispute</h3>
                <form action="{{ route('staff.disputes.update', $dispute->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #666; margin-bottom: 0.5rem;">Status</label>
                            <select name="status" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px;">
                                <option value="open" {{ $dispute->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="investigating" {{ $dispute->status == 'investigating' ? 'selected' : '' }}>Investigating</option>
                                <option value="pending_user" {{ $dispute->status == 'pending_user' ? 'selected' : '' }}>Pending User</option>
                                <option value="resolved" {{ $dispute->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ $dispute->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #666; margin-bottom: 0.5rem;">Priority</label>
                            <select name="priority" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px;">
                                <option value="low" {{ $dispute->priority == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ $dispute->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ $dispute->priority == 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ $dispute->priority == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #666; margin-bottom: 0.5rem;">Resolution / Investigation Notes</label>
                        <textarea name="resolution" rows="4" placeholder="Enter details..." style="width: 100%; padding: 1rem; border: 2px solid #eee; border-radius: 8px; resize: vertical; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='var(--secondary-color)'" onblur="this.style.borderColor='#eee'">{{ $dispute->resolution }}</textarea>
                    </div>

                    <div style="text-align: right;">
                        <button type="submit" style="padding: 0.75rem 2rem; background: var(--secondary-color); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#4a6633'" onmouseout="this.style.background='var(--secondary-color)'">
                            Update Dispute
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- Dispute Info -->
            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1rem; border-bottom: 1px solid #eee; padding-bottom: 0.5rem;">Dispute Details</h3>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <div style="font-size: 0.75rem; color: #999; text-transform: uppercase; font-weight: 600;">Disputed Amount</div>
                        <div style="font-size: 1.5rem; font-weight: bold; color: var(--primary-color);">₦{{ number_format($dispute->disputed_amount, 2) }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.75rem; color: #999; text-transform: uppercase; font-weight: 600;">Transaction ID</div>
                        <div style="font-weight: 500; color: var(--primary-color); font-family: monospace;">#{{ $dispute->transaction_id ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.75rem; color: #999; text-transform: uppercase; font-weight: 600;">Assigned To</div>
                        <div style="font-weight: 500; color: var(--primary-color);">{{ $dispute->assignedStaff ? $dispute->assignedStaff->name : 'Unassigned' }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.75rem; color: #999; text-transform: uppercase; font-weight: 600;">Last Updated</div>
                        <div style="font-weight: 500; color: var(--primary-color);">{{ $dispute->updated_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
