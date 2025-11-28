@extends('core::layouts.master')

@section('content')
<div style="padding: 2rem; max-width: 1600px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <a href="{{ route('staff.finance.dashboard') }}" style="color: var(--primary-color); text-decoration: none;">&larr; Back to Finance Dashboard</a>
            <h1 style="font-size: 2rem; font-weight: bold; color: var(--primary-color); margin: 0.5rem 0 0 0;">Reconciliation Center</h1>
        </div>
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <button onclick="document.getElementById('runReconciliationModal').style.display='flex'" style="padding: 0.75rem 1.5rem; background: var(--secondary-color); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
                🔄 Run Reconciliation
            </button>
            <button onclick="document.getElementById('manualReconciliationModal').style.display='flex'" style="padding: 0.75rem 1.5rem; background: var(--accent-color); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
                ✏️ Manual Entry
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div style="background: linear-gradient(135deg, var(--secondary-color), #4a6633); border-radius: 12px; padding: 1.5rem; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                <div style="font-size: 2.5rem;">✓</div>
                <span style="background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">MATCHED</span>
            </div>
            <div style="font-size: 2.5rem; font-weight: bold; margin-bottom: 0.25rem;">{{ $summary['matched'] }}</div>
            <div style="font-size: 0.875rem; opacity: 0.9;">Perfect Matches</div>
        </div>

        <div style="background: linear-gradient(135deg, var(--accent-color), #c67316); border-radius: 12px; padding: 1.5rem; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                <div style="font-size: 2.5rem;">⚠️</div>
                <span style="background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">DISCREPANCIES</span>
            </div>
            <div style="font-size: 2.5rem; font-weight: bold; margin-bottom: 0.25rem;">{{ $summary['discrepancies'] }}</div>
            <div style="font-size: 0.875rem; opacity: 0.9;">Needs Attention</div>
        </div>

        <div style="background: linear-gradient(135deg, #4a90e2, #357abd); border-radius: 12px; padding: 1.5rem; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                <div style="font-size: 2.5rem;">⏳</div>
                <span style="background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">PENDING</span>
            </div>
            <div style="font-size: 2.5rem; font-weight: bold; margin-bottom: 0.25rem;">{{ $summary['pending'] }}</div>
            <div style="font-size: 0.875rem; opacity: 0.9;">In Progress</div>
        </div>

        <div style="background: linear-gradient(135deg, var(--primary-color), #1a1f38); border-radius: 12px; padding: 1.5rem; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                <div style="font-size: 2.5rem;">📊</div>
                <span style="background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">TOTAL</span>
            </div>
            <div style="font-size: 2.5rem; font-weight: bold; margin-bottom: 0.25rem;">{{ $summary['total_reconciliations'] }}</div>
            <div style="font-size: 0.875rem; opacity: 0.9;">All Reconciliations</div>
        </div>
    </div>

    <!-- Reconciliation Logs -->
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden;">
        <div style="padding: 1.5rem; border-bottom: 2px solid #f0f0f0;">
            <h2 style="font-size: 1.25rem; font-weight: 600; color: var(--primary-color); margin: 0;">Reconciliation History</h2>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #f9f9f9;">
                    <tr>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--primary-color); border-bottom: 2px solid #e0e0e0;">Date</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--primary-color); border-bottom: 2px solid #e0e0e0;">Type</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--primary-color); border-bottom: 2px solid #e0e0e0;">Currency</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--primary-color); border-bottom: 2px solid #e0e0e0;">Expected Balance</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--primary-color); border-bottom: 2px solid #e0e0e0;">Actual Balance</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--primary-color); border-bottom: 2px solid #e0e0e0;">Difference</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--primary-color); border-bottom: 2px solid #e0e0e0;">Status</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--primary-color); border-bottom: 2px solid #e0e0e0;">Staff</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--primary-color); border-bottom: 2px solid #e0e0e0;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr style="border-bottom: 1px solid #f0f0f0; transition: background 0.2s;" onmouseover="this.style.background='#f9f9f9'" onmouseout="this.style.background='white'">
                            <td style="padding: 1rem;">
                                <div style="font-weight: 600; color: var(--primary-color);">{{ \Carbon\Carbon::parse($log->reconciliation_date)->format('M d, Y') }}</div>
                                <div style="font-size: 0.875rem; color: #999;">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i') }}</div>
                            </td>
                            <td style="padding: 1rem;">
                                <span style="padding: 0.5rem 1rem; background: #f0f0f0; color: var(--primary-color); border-radius: 20px; font-size: 0.875rem; font-weight: 600; text-transform: capitalize;">
                                    {{ $log->type }}
                                </span>
                            </td>
                            <td style="padding: 1rem;">
                                <div style="font-weight: 600; color: var(--primary-color);">{{ $log->currency ?? 'NGN' }}</div>
                            </td>
                            <td style="padding: 1rem;">
                                <div style="font-weight: 600; color: var(--primary-color);">{{ $log->currency ?? 'NGN' }} {{ number_format($log->expected_balance, 2) }}</div>
                            </td>
                            <td style="padding: 1rem;">
                                <div style="font-weight: 600; color: var(--primary-color);">{{ $log->currency ?? 'NGN' }} {{ number_format($log->actual_balance, 2) }}</div>
                            </td>
                            <td style="padding: 1rem;">
                                <div style="font-weight: 700; font-size: 1.125rem; color: {{ abs($log->difference) < 0.01 ? 'var(--secondary-color)' : 'var(--accent-color)' }};">
                                    {{ $log->difference >= 0 ? '+' : '' }}{{ $log->currency ?? 'NGN' }} {{ number_format($log->difference, 2) }}
                                </div>
                            </td>
                            <td style="padding: 1rem;">
                                <span style="padding: 0.5rem 1rem; background: {{ $log->status === 'matched' ? '#d4edda' : ($log->status === 'discrepancy' ? '#f8d7da' : ($log->status === 'resolved' ? '#d1ecf1' : '#fff3cd')) }}; color: {{ $log->status === 'matched' ? 'var(--secondary-color)' : ($log->status === 'discrepancy' ? 'var(--accent-color)' : ($log->status === 'resolved' ? '#0c5460' : '#856404')) }}; border-radius: 20px; font-size: 0.875rem; font-weight: 600; text-transform: uppercase;">
                                    {{ $log->status === 'matched' ? '✓ Matched' : ($log->status === 'discrepancy' ? '⚠ Discrepancy' : ($log->status === 'resolved' ? '✓ Resolved' : '⏳ Pending')) }}
                                </span>
                            </td>
                            <td style="padding: 1rem;">
                                <div style="font-weight: 500; color: var(--primary-color);">{{ $log->staff->name ?? 'System' }}</div>
                            </td>
                            <td style="padding: 1rem;">
                                <button onclick="openStatusModal({{ $log->id }}, '{{ $log->status }}', '{{ $log->notes ?? '' }}')" style="padding: 0.5rem 1rem; background: var(--primary-color); color: white; border: none; border-radius: 6px; font-size: 0.875rem; font-weight: 600; cursor: pointer;">
                                    Update
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="padding: 4rem; text-align: center;">
                                <div style="font-size: 3rem; margin-bottom: 1rem;">📋</div>
                                <h3 style="color: var(--primary-color); margin-bottom: 0.5rem;">No Reconciliations Yet</h3>
                                <p style="color: #666;">Click "Run Reconciliation" to start your first reconciliation</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
        <div style="padding: 1rem; border-top: 1px solid #e0e0e0;">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Run Reconciliation Modal -->
<div id="runReconciliationModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 2rem; max-width: 500px; width: 90%;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.5rem; font-weight: bold; color: var(--primary-color); margin: 0;">Run Automated Reconciliation</h2>
            <button onclick="document.getElementById('runReconciliationModal').style.display='none'" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #999;">&times;</button>
        </div>
        <form action="{{ route('staff.finance.reconciliation.run') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; color: var(--primary-color); margin-bottom: 0.5rem;">Reconciliation Type</label>
                <select name="type" required style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px; outline: none;">
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                    <option value="quarterly">Quarterly</option>
                </select>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; color: var(--primary-color); margin-bottom: 0.5rem;">Date</label>
                <input type="date" name="date" value="{{ date('Y-m-d') }}" required style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px; outline: none;">
            </div>
            <div style="padding: 1rem; background: #f9f9f9; border-radius: 8px; margin-bottom: 1.5rem; border-left: 4px solid var(--secondary-color);">
                <div style="font-size: 0.875rem; color: #666; line-height: 1.6;">
                    <strong>Automated reconciliation will:</strong><br>
                    • Compare wallet balances with transaction totals<br>
                    • Calculate any discrepancies<br>
                    • Generate a detailed report
                </div>
            </div>
            <div style="display: flex; gap: 1rem;">
                <button type="button" onclick="document.getElementById('runReconciliationModal').style.display='none'" style="flex: 1; padding: 0.75rem; background: #f5f5f5; color: #666; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    Cancel
                </button>
                <button type="submit" style="flex: 1; padding: 0.75rem; background: var(--secondary-color); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    Run Reconciliation
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Manual Reconciliation Modal -->
<div id="manualReconciliationModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 2rem; max-width: 500px; width: 90%;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.5rem; font-weight: bold; color: var(--primary-color); margin: 0;">Manual Reconciliation Entry</h2>
            <button onclick="document.getElementById('manualReconciliationModal').style.display='none'" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #999;">&times;</button>
        </div>
        <form action="{{ route('staff.finance.reconciliation.create') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-weight: 600; color: var(--primary-color); margin-bottom: 0.5rem;">Type</label>
                <select name="type" required style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
                    <option value="manual">Manual</option>
                    <option value="adjustment">Adjustment</option>
                    <option value="correction">Correction</option>
                </select>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-weight: 600; color: var(--primary-color); margin-bottom: 0.5rem;">Currency</label>
                <select name="currency" required style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
                    <option value="NGN">NGN</option>
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                    <option value="GBP">GBP</option>
                </select>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-weight: 600; color: var(--primary-color); margin-bottom: 0.5rem;">Expected Balance</label>
                <input type="number" name="expected_balance" step="0.01" required style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-weight: 600; color: var(--primary-color); margin-bottom: 0.5rem;">Actual Balance</label>
                <input type="number" name="actual_balance" step="0.01" required style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; color: var(--primary-color); margin-bottom: 0.5rem;">Notes</label>
                <textarea name="notes" rows="3" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px; resize: vertical;"></textarea>
            </div>
            <div style="display: flex; gap: 1rem;">
                <button type="button" onclick="document.getElementById('manualReconciliationModal').style.display='none'" style="flex: 1; padding: 0.75rem; background: #f5f5f5; color: #666; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    Cancel
                </button>
                <button type="submit" style="flex: 1; padding: 0.75rem; background: var(--accent-color); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    Create Entry
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Update Status Modal -->
<div id="updateStatusModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 2rem; max-width: 500px; width: 90%;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.5rem; font-weight: bold; color: var(--primary-color); margin: 0;">Update Reconciliation Status</h2>
            <button onclick="document.getElementById('updateStatusModal').style.display='none'" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #999;">&times;</button>
        </div>
        <form id="updateStatusForm" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-weight: 600; color: var(--primary-color); margin-bottom: 0.5rem;">Status</label>
                <select name="status" id="statusSelect" required style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
                    <option value="pending">Pending</option>
                    <option value="matched">Matched</option>
                    <option value="discrepancy">Discrepancy</option>
                    <option value="resolved">Resolved</option>
                </select>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; color: var(--primary-color); margin-bottom: 0.5rem;">Notes</label>
                <textarea name="notes" id="notesTextarea" rows="3" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px; resize: vertical;"></textarea>
            </div>
            <div style="display: flex; gap: 1rem;">
                <button type="button" onclick="document.getElementById('updateStatusModal').style.display='none'" style="flex: 1; padding: 0.75rem; background: #f5f5f5; color: #666; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    Cancel
                </button>
                <button type="submit" style="flex: 1; padding: 0.75rem; background: var(--primary-color); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    Update Status
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openStatusModal(id, currentStatus, currentNotes) {
    const form = document.getElementById('updateStatusForm');
    form.action = `/staff/finance/reconciliation/${id}/update-status`;
    document.getElementById('statusSelect').value = currentStatus;
    document.getElementById('notesTextarea').value = currentNotes;
    document.getElementById('updateStatusModal').style.display = 'flex';
}
</script>
@endsection
