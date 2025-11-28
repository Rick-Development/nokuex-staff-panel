@extends('core::layouts.master')

@section('content')
<div style="padding: 2rem; max-width: 1600px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <a href="{{ route('staff.compliance.dashboard') }}" style="color: var(--primary-color); text-decoration: none;">&larr; Back to Dashboard</a>
            <h1 style="font-size: 2rem; font-weight: bold; color: var(--primary-color); margin: 0.5rem 0 0 0;">Compliance Flags</h1>
        </div>
    </div>

    <!-- Filters -->
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 1.5rem; margin-bottom: 1.5rem;">
        <form action="{{ route('staff.compliance.flags.index') }}" method="GET">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div>
                    <label style="display: block; font-weight: 600; color: var(--primary-color); margin-bottom: 0.5rem; font-size: 0.875rem;">Status</label>
                    <select name="status" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                        <option value="cleared" {{ request('status') == 'cleared' ? 'selected' : '' }}>Cleared</option>
                        <option value="escalated" {{ request('status') == 'escalated' ? 'selected' : '' }}>Escalated</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-weight: 600; color: var(--primary-color); margin-bottom: 0.5rem; font-size: 0.875rem;">Severity</label>
                    <select name="severity" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
                        <option value="">All Severities</option>
                        <option value="low" {{ request('severity') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ request('severity') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ request('severity') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="critical" {{ request('severity') == 'critical' ? 'selected' : '' }}>Critical</option>
                    </select>
                </div>
                <div style="display: flex; align-items: end; gap: 0.5rem;">
                    <button type="submit" style="flex: 1; padding: 0.75rem; background: var(--primary-color); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        🔍 Filter
                    </button>
                    @if(request()->hasAny(['status', 'severity']))
                        <a href="{{ route('staff.compliance.flags.index') }}" style="padding: 0.75rem 1rem; background: #f5f5f5; color: #666; text-decoration: none; border-radius: 8px; font-weight: 600;">✕</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Flags Grid -->
    <div style="display: grid; gap: 1rem;">
        @forelse($flags as $flag)
            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-left: 4px solid {{ $flag->severity === 'critical' ? '#c0392b' : ($flag->severity === 'high' ? 'var(--accent-color)' : ($flag->severity === 'medium' ? '#f39c12' : '#95a5a6')) }};">
                <div style="display: grid; grid-template-columns: auto 1fr auto auto; gap: 1.5rem; align-items: start;">
                    <!-- Icon -->
                    <div style="width: 56px; height: 56px; border-radius: 12px; background: {{ $flag->severity === 'critical' ? 'linear-gradient(135deg, #c0392b, #8e2c1f)' : ($flag->severity === 'high' ? 'linear-gradient(135deg, var(--accent-color), #c67316)' : 'linear-gradient(135deg, #f39c12, #e67e22)') }}; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; color: white;">
                        {{ $flag->severity === 'critical' ? '🚨' : ($flag->severity === 'high' ? '⚠️' : '⚡') }}
                    </div>

                    <!-- Flag Info -->
                    <div>
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                            <div style="font-weight: 600; color: var(--primary-color); font-size: 1.125rem;">
                                {{ $flag->user->first_name }} {{ $flag->user->last_name }}
                            </div>
                            <span style="padding: 0.25rem 0.75rem; background: {{ $flag->severity === 'critical' ? '#f8d7da' : ($flag->severity === 'high' ? '#fff3cd' : ($flag->severity === 'medium' ? '#ffe5b4' : '#e0e0e0')) }}; color: {{ $flag->severity === 'critical' ? '#721c24' : ($flag->severity === 'high' ? '#856404' : ($flag->severity === 'medium' ? '#856404' : '#666')) }}; border-radius: 12px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">
                                {{ $flag->severity }}
                            </span>
                        </div>
                        <div style="font-size: 0.875rem; color: #666; margin-bottom: 0.5rem;">
                            <strong>Type:</strong> <span style="text-transform: capitalize;">{{ str_replace('_', ' ', $flag->flag_type) }}</span>
                        </div>
                        <div style="color: #333; line-height: 1.5; margin-bottom: 0.5rem;">
                            {{ $flag->description }}
                        </div>
                        <div style="font-size: 0.875rem; color: #999;">
                            Flagged: {{ $flag->created_at->format('M d, Y H:i') }}
                            @if($flag->reviewed_at)
                                • Reviewed: {{ $flag->reviewed_at->diffForHumans() }}
                            @endif
                        </div>
                    </div>

                    <!-- Status Badge -->
                    <div>
                        <span style="padding: 0.5rem 1rem; background: {{ $flag->status === 'cleared' ? '#d4edda' : ($flag->status === 'pending' ? '#cce5ff' : ($flag->status === 'under_review' ? '#fff3cd' : '#f8d7da')) }}; color: {{ $flag->status === 'cleared' ? '#155724' : ($flag->status === 'pending' ? '#004085' : ($flag->status === 'under_review' ? '#856404' : '#721c24')) }}; border-radius: 20px; font-size: 0.875rem; font-weight: 600; text-transform: uppercase;">
                            {{ str_replace('_', ' ', $flag->status) }}
                        </span>
                    </div>

                    <!-- Actions -->
                    <div>
                        <button onclick="openFlagModal({{ $flag->id }}, '{{ $flag->status }}', '{{ addslashes($flag->resolution_notes ?? '') }}')" style="padding: 0.5rem 1rem; background: var(--primary-color); color: white; border: none; border-radius: 6px; font-size: 0.875rem; font-weight: 600; cursor: pointer;">
                            Update
                        </button>
                    </div>
                </div>

                @if($flag->resolution_notes)
                <div style="margin-top: 1rem; padding: 1rem; background: #f0fff4; border-radius: 8px; border-left: 3px solid var(--secondary-color);">
                    <div style="font-size: 0.75rem; color: #666; font-weight: 600; margin-bottom: 0.25rem;">RESOLUTION NOTES</div>
                    <div style="color: #333; font-size: 0.875rem;">{{ $flag->resolution_notes }}</div>
                </div>
                @endif
            </div>
        @empty
            <div style="background: white; border-radius: 12px; padding: 4rem; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">✅</div>
                <h3 style="color: var(--primary-color); margin-bottom: 0.5rem;">No flags found</h3>
                <p style="color: #666;">All clear or try adjusting your filters</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div style="margin-top: 2rem;">
        {{ $flags->links() }}
    </div>
</div>

<!-- Update Flag Modal -->
<div id="updateFlagModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 2rem; max-width: 500px; width: 90%;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--primary-color); margin: 0;">Update Flag Status</h3>
            <button onclick="closeModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #999;">&times;</button>
        </div>
        <form id="updateFlagForm" method="POST">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #666; margin-bottom: 0.5rem;">Status</label>
                <select name="status" id="flagStatus" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px;">
                    <option value="pending">Pending</option>
                    <option value="under_review">Under Review</option>
                    <option value="cleared">Cleared</option>
                    <option value="escalated">Escalated</option>
                </select>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #666; margin-bottom: 0.5rem;">Resolution Notes</label>
                <textarea name="resolution_notes" id="flagNotes" rows="4" style="width: 100%; padding: 1rem; border: 2px solid #eee; border-radius: 8px; resize: vertical;"></textarea>
            </div>
            <div style="display: flex; gap: 1rem;">
                <button type="button" onclick="closeModal()" style="flex: 1; padding: 0.75rem; background: #eee; border: none; border-radius: 8px; cursor: pointer;">Cancel</button>
                <button type="submit" style="flex: 1; padding: 0.75rem; background: var(--secondary-color); color: white; border: none; border-radius: 8px; cursor: pointer;">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openFlagModal(id, status, notes) {
        const form = document.getElementById('updateFlagForm');
        form.action = `/staff/compliance/flags/${id}`;
        document.getElementById('flagStatus').value = status;
        document.getElementById('flagNotes').value = notes;
        document.getElementById('updateFlagModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('updateFlagModal').style.display = 'none';
    }
</script>
@endsection
