@extends('core::layouts.app')

@section('title', 'Reconciliation Engine')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <h2 style="margin: 0;">Reconciliation Engine</h2>
        <button onclick="showCreateReconciliationModal()" class="btn btn-primary">
            <i>➕</i> New Reconciliation
        </button>
    </div>

    <div class="card" style="margin-bottom: 1.5rem;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div style="text-align: center;">
                <div style="font-size: 2rem; font-weight: bold; color: var(--accent-color);">{{ $stats['pending'] }}</div>
                <div style="color: #666;">Pending</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 2rem; font-weight: bold; color: var(--secondary-color);">{{ $stats['completed'] }}</div>
                <div style="color: #666;">Completed</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 2rem; font-weight: bold; color: var(--primary-color);">₦{{ number_format($stats['variance_amount'], 2) }}</div>
                <div style="color: #666;">Total Variance</div>
            </div>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table id="reconciliation-table" class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Expected Amount</th>
                    <th>Actual Amount</th>
                    <th>Variance</th>
                    <th>Status</th>
                    <th>Processed By</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Create Reconciliation Modal -->
<div id="createReconciliationModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: white; margin: 5% auto; padding: 2rem; border-radius: 8px; width: 90%; max-width: 600px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="margin: 0;">New Reconciliation</h3>
            <button onclick="closeCreateModal()" class="btn btn-warning">&times;</button>
        </div>
        
        <form id="create-reconciliation-form">
            @csrf
            
            <div class="form-group">
                <label for="reconciliation_date" class="form-label">Reconciliation Date *</label>
                <input type="date" name="reconciliation_date" id="reconciliation_date" class="form-control" required value="{{ date('Y-m-d') }}">
            </div>
            
            <div class="form-group">
                <label for="expected_amount" class="form-label">Expected Amount *</label>
                <input type="number" step="0.01" name="expected_amount" id="expected_amount" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="actual_amount" class="form-label">Actual Amount *</label>
                <input type="number" step="0.01" name="actual_amount" id="actual_amount" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="notes" class="form-label">Notes</label>
                <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
            </div>
            
            <div style="display: flex; gap: 0.5rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">Create Reconciliation</button>
                <button type="button" onclick="closeCreateModal()" class="btn btn-warning">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- View Reconciliation Modal -->
<div id="viewReconciliationModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: white; margin: 5% auto; padding: 2rem; border-radius: 8px; width: 90%; max-width: 800px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="margin: 0;">Reconciliation Details</h3>
            <button onclick="closeViewModal()" class="btn btn-warning">&times;</button>
        </div>
        <div id="reconciliation-details"></div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<script>
let reconciliationTable;

$(document).ready(function() {
    reconciliationTable = $('#reconciliation-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("finance.reconciliation") }}',
        columns: [
            { data: 'reconciliation_date', name: 'reconciliation_date' },
            { data: 'expected_amount', name: 'expected_amount', render: function(data) {
                return '₦' + parseFloat(data).toLocaleString('en-US', {minimumFractionDigits: 2});
            }},
            { data: 'actual_amount', name: 'actual_amount', render: function(data) {
                return '₦' + parseFloat(data).toLocaleString('en-US', {minimumFractionDigits: 2});
            }},
            { data: 'variance_formatted', name: 'variance', orderable: true, searchable: false },
            { data: 'status_badge', name: 'status', orderable: false, searchable: false },
            { data: 'processed_by.name', name: 'processedBy.name' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']],
        language: {
            search: "Search reconciliations:",
            lengthMenu: "Show _MENU_ entries"
        }
    });
});

function showCreateReconciliationModal() {
    document.getElementById('createReconciliationModal').style.display = 'block';
}

function closeCreateModal() {
    document.getElementById('createReconciliationModal').style.display = 'none';
    document.getElementById('create-reconciliation-form').reset();
}

function viewReconciliation(reconciliationId) {
    // Fetch and display reconciliation details
    fetch(`/finance/reconciliation/${reconciliationId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const rec = data.reconciliation;
                const variance = parseFloat(rec.variance);
                const varianceColor = variance === 0 ? 'green' : variance > 0 ? 'orange' : 'red';
                
                document.getElementById('reconciliation-details').innerHTML = `
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                        <div>
                            <h4>Reconciliation Information</h4>
                            <p><strong>Date:</strong> ${rec.reconciliation_date}</p>
                            <p><strong>Expected Amount:</strong> ₦${parseFloat(rec.expected_amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</p>
                            <p><strong>Actual Amount:</strong> ₦${parseFloat(rec.actual_amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</p>
                            <p><strong>Variance:</strong> <span style="color: ${varianceColor}; font-weight: 600;">₦${Math.abs(variance).toLocaleString('en-US', {minimumFractionDigits: 2})}</span></p>
                        </div>
                        <div>
                            <h4>Processing Details</h4>
                            <p><strong>Status:</strong> <span class="badge badge-${rec.status === 'completed' ? 'success' : 'warning'}">${rec.status}</span></p>
                            <p><strong>Processed By:</strong> ${rec.processed_by ? rec.processed_by.name : 'N/A'}</p>
                            <p><strong>Created:</strong> ${new Date(rec.created_at).toLocaleString()}</p>
                        </div>
                    </div>
                    
                    ${rec.notes ? `
                    <div>
                        <h4>Notes</h4>
                        <div style="background: #f8f9fa; padding: 1rem; border-radius: 4px;">
                            ${rec.notes}
                        </div>
                    </div>
                    ` : ''}
                `;
                
                document.getElementById('viewReconciliationModal').style.display = 'block';
            }
        });
}

function closeViewModal() {
    document.getElementById('viewReconciliationModal').style.display = 'none';
}

// Create reconciliation form submission
document.getElementById('create-reconciliation-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const btn = this.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    
    btn.innerHTML = 'Creating...';
    btn.disabled = true;
    
    fetch('{{ route("finance.reconciliation.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeCreateModal();
            reconciliationTable.ajax.reload();
            this.reset();
            alert('Reconciliation created successfully!');
        } else {
            alert('Error: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        alert('Error creating reconciliation. Please try again.');
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
});
</script>
@endpush
@endsection