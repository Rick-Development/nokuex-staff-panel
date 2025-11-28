@extends('core::layouts.master')

@section('content')
<div style="padding: 2rem; max-width: 1600px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <a href="{{ route('staff.compliance.dashboard') }}" style="color: var(--primary-color); text-decoration: none;">&larr; Back to Dashboard</a>
            <h1 style="font-size: 2rem; font-weight: bold; color: var(--primary-color); margin: 0.5rem 0 0 0;">KYC/KYB Reviews</h1>
        </div>
    </div>

    <!-- Filters -->
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 1.5rem; margin-bottom: 1.5rem;">
        <form action="{{ route('staff.compliance.kyc.index') }}" method="GET">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div>
                    <label style="display: block; font-weight: 600; color: var(--primary-color); margin-bottom: 0.5rem; font-size: 0.875rem;">Status</label>
                    <select name="status" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_review" {{ request('status') == 'in_review' ? 'selected' : '' }}>In Review</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-weight: 600; color: var(--primary-color); margin-bottom: 0.5rem; font-size: 0.875rem;">Type</label>
                    <select name="type" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
                        <option value="">All Types</option>
                        <option value="kyc" {{ request('type') == 'kyc' ? 'selected' : '' }}>KYC</option>
                        <option value="kyb" {{ request('type') == 'kyb' ? 'selected' : '' }}>KYB</option>
                    </select>
                </div>
                <div style="display: flex; align-items: end; gap: 0.5rem;">
                    <button type="submit" style="flex: 1; padding: 0.75rem; background: var(--primary-color); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        🔍 Filter
                    </button>
                    @if(request()->hasAny(['status', 'type']))
                        <a href="{{ route('staff.compliance.kyc.index') }}" style="padding: 0.75rem 1rem; background: #f5f5f5; color: #666; text-decoration: none; border-radius: 8px; font-weight: 600;">✕</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Reviews Grid -->
    <div style="display: grid; gap: 1rem;">
        @forelse($reviews as $review)
            <a href="{{ route('staff.compliance.kyc.show', $review->id) }}" style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.2s; text-decoration: none; display: block;" onmouseover="this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'" onmouseout="this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
                <div style="display: grid; grid-template-columns: auto 1fr auto auto; gap: 1.5rem; align-items: center;">
                    <!-- Avatar -->
                    <div style="width: 56px; height: 56px; border-radius: 12px; background: linear-gradient(135deg, var(--primary-color), #3a4b7c); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white; font-weight: bold;">
                        {{ substr($review->user->first_name, 0, 1) }}{{ substr($review->user->last_name, 0, 1) }}
                    </div>

                    <!-- User Info -->
                    <div>
                        <div style="font-weight: 600; color: var(--primary-color); font-size: 1.125rem; margin-bottom: 0.25rem;">
                            {{ $review->user->first_name }} {{ $review->user->last_name }}
                        </div>
                        <div style="font-size: 0.875rem; color: #666;">
                            {{ $review->user->email }}
                        </div>
                        <div style="font-size: 0.875rem; color: #999; margin-top: 0.25rem;">
                            Type: <span style="text-transform: uppercase; font-weight: 600;">{{ $review->review_type }}</span> • 
                            Submitted: {{ $review->submitted_at ? $review->submitted_at->format('M d, Y') : 'N/A' }}
                        </div>
                    </div>

                    <!-- Status Badge -->
                    <div>
                        <span style="padding: 0.5rem 1rem; background: {{ $review->status === 'approved' ? '#d4edda' : ($review->status === 'pending' ? '#cce5ff' : ($review->status === 'in_review' ? '#fff3cd' : '#f8d7da')) }}; color: {{ $review->status === 'approved' ? '#155724' : ($review->status === 'pending' ? '#004085' : ($review->status === 'in_review' ? '#856404' : '#721c24')) }}; border-radius: 20px; font-size: 0.875rem; font-weight: 600; text-transform: uppercase;">
                            {{ str_replace('_', ' ', $review->status) }}
                        </span>
                    </div>

                    <!-- Reviewer -->
                    <div style="text-align: right;">
                        <div style="font-size: 0.875rem; color: #999;">
                            {{ $review->reviewer ? $review->reviewer->name : 'Unassigned' }}
                        </div>
                        <div style="font-size: 0.75rem; color: #999; margin-top: 0.25rem;">
                            {{ $review->reviewed_at ? $review->reviewed_at->diffForHumans() : '' }}
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div style="background: white; border-radius: 12px; padding: 4rem; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">📭</div>
                <h3 style="color: var(--primary-color); margin-bottom: 0.5rem;">No reviews found</h3>
                <p style="color: #666;">Try adjusting your filters</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div style="margin-top: 2rem;">
        {{ $reviews->links() }}
    </div>
</div>
@endsection
