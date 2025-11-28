@extends('core::layouts.master')

@section('content')
<div style="padding: 2rem; max-width: 1400px; margin: 0 auto;">
    <!-- Header -->
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('staff.compliance.kyc.index') }}" style="color: var(--primary-color); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 500; margin-bottom: 0.5rem;">
            &larr; Back to Reviews
        </a>
        <div style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 1.5rem;">
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--primary-color), #3a4b7c); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; font-weight: bold;">
                    {{ substr($review->user->first_name, 0, 1) }}{{ substr($review->user->last_name, 0, 1) }}
                </div>
                <div>
                    <h1 style="font-size: 1.75rem; font-weight: bold; color: var(--primary-color); margin: 0 0 0.25rem 0;">{{ $review->user->first_name }} {{ $review->user->last_name }}</h1>
                    <div style="color: #666;">{{ $review->user->email }}</div>
                    <div style="margin-top: 0.5rem;">
                        <span style="padding: 0.25rem 0.75rem; background: #f0f0f0; border-radius: 12px; font-size: 0.875rem; font-weight: 600; text-transform: uppercase;">{{ $review->review_type }}</span>
                    </div>
                </div>
            </div>
            <div>
                <span style="padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; {{ $review->status === 'approved' ? 'background: #d4edda; color: #155724;' : ($review->status === 'pending' ? 'background: #cce5ff; color: #004085;' : ($review->status === 'in_review' ? 'background: #fff3cd; color: #856404;' : 'background: #f8d7da; color: #721c24;')) }}">
                    {{ str_replace('_', ' ', $review->status) }}
                </span>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 350px; gap: 2rem;">
        <!-- Main Content -->
        <div style="display: flex; flex-direction: column; gap: 2rem;">
            <!-- Review Details -->
            <div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1rem; border-bottom: 1px solid #eee; padding-bottom: 0.5rem;">Review Information</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div>
                        <div style="font-size: 0.75rem; color: #999; text-transform: uppercase; font-weight: 600;">Submitted At</div>
                        <div style="font-weight: 500; color: var(--primary-color);">{{ $review->submitted_at ? $review->submitted_at->format('M d, Y H:i') : 'N/A' }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.75rem; color: #999; text-transform: uppercase; font-weight: 600;">Reviewed At</div>
                        <div style="font-weight: 500; color: var(--primary-color);">{{ $review->reviewed_at ? $review->reviewed_at->format('M d, Y H:i') : 'Not yet reviewed' }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.75rem; color: #999; text-transform: uppercase; font-weight: 600;">Reviewed By</div>
                        <div style="font-weight: 500; color: var(--primary-color);">{{ $review->reviewer ? $review->reviewer->name : 'Unassigned' }}</div>
                    </div>
                </div>
            </div>

            @if($review->notes)
            <div style="background: #f0fff4; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-left: 4px solid var(--secondary-color);">
                <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--secondary-color); margin-bottom: 1rem;">Review Notes</h3>
                <div style="color: #333; line-height: 1.6; white-space: pre-wrap;">{{ $review->notes }}</div>
            </div>
            @endif

            @if($review->rejection_reason)
            <div style="background: #fff5f5; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-left: 4px solid #e74c3c;">
                <h3 style="font-size: 1.125rem; font-weight: 600; color: #e74c3c; margin-bottom: 1rem;">Rejection Reason</h3>
                <div style="color: #333; line-height: 1.6; white-space: pre-wrap;">{{ $review->rejection_reason }}</div>
            </div>
            @endif

            <!-- Account Actions History -->
            <div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1rem; border-bottom: 1px solid #eee; padding-bottom: 0.5rem;">Recent Account Actions</h3>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    @forelse($accountActions as $action)
                        <div style="padding: 0.75rem; border-left: 3px solid {{ $action->action_type === 'freeze' ? '#e74c3c' : 'var(--secondary-color)' }}; background: #f9f9f9; border-radius: 4px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem;">
                                <span style="font-weight: 600; color: var(--primary-color); text-transform: capitalize;">{{ str_replace('_', ' ', $action->action_type) }}</span>
                                <span style="font-size: 0.75rem; color: #999;">{{ $action->created_at->diffForHumans() }}</span>
                            </div>
                            <div style="font-size: 0.875rem; color: #666;">{{ $action->reason }}</div>
                            <div style="font-size: 0.75rem; color: #999; margin-top: 0.25rem;">By: {{ $action->staff->name }}</div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 1rem; color: #999;">No account actions</div>
                    @endforelse
                </div>
            </div>

            <!-- Update Form -->
            <div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1.5rem;">Update Review</h3>
                <form action="{{ route('staff.compliance.kyc.update', $review->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #666; margin-bottom: 0.5rem;">Status</label>
                        <select name="status" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px;">
                            <option value="pending" {{ $review->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_review" {{ $review->status == 'in_review' ? 'selected' : '' }}>In Review</option>
                            <option value="approved" {{ $review->status == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $review->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="resubmit_required" {{ $review->status == 'resubmit_required' ? 'selected' : '' }}>Resubmit Required</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #666; margin-bottom: 0.5rem;">Rejection Reason (if rejected)</label>
                        <textarea name="rejection_reason" rows="3" style="width: 100%; padding: 1rem; border: 2px solid #eee; border-radius: 8px; resize: vertical;">{{ $review->rejection_reason }}</textarea>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #666; margin-bottom: 0.5rem;">Review Notes</label>
                        <textarea name="notes" rows="4" style="width: 100%; padding: 1rem; border: 2px solid #eee; border-radius: 8px; resize: vertical;">{{ $review->notes }}</textarea>
                    </div>

                    <button type="submit" style="width: 100%; padding: 0.75rem; background: var(--secondary-color); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        Update Review
                    </button>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- User Info -->
            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1rem; border-bottom: 1px solid #eee; padding-bottom: 0.5rem;">User Details</h3>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <div style="font-size: 0.75rem; color: #999; text-transform: uppercase; font-weight: 600;">Phone</div>
                        <div style="font-weight: 500; color: var(--primary-color);">{{ $review->user->phone ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.75rem; color: #999; text-transform: uppercase; font-weight: 600;">Joined</div>
                        <div style="font-weight: 500; color: var(--primary-color);">{{ $review->user->created_at->format('M d, Y') }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.75rem; color: #999; text-transform: uppercase; font-weight: 600;">Account Status</div>
                        <div style="font-weight: 500; color: var(--primary-color); text-transform: capitalize;">{{ $review->user->account_status ?? 'Active' }}</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1rem; border-bottom: 1px solid #eee; padding-bottom: 0.5rem;">Quick Actions</h3>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <a href="{{ route('staff.customers.show', $review->user_id) }}" style="padding: 0.75rem; background: var(--primary-color); color: white; text-align: center; border-radius: 8px; text-decoration: none; font-weight: 600;">
                        View Customer Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
