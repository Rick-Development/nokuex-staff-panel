@extends('core::layouts.app')

@section('title', 'Transaction Monitoring')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <h2 style="margin: 0;">Transaction Monitoring</h2>
        <div style="display: flex; gap: 0.5rem;">
            <button onclick="exportTransactions()" class="btn btn-success">
                <i>📤</i> Export
            </button>
        </div>
    </div>

    <div class="card" style="margin-bottom: 1.5rem;">
        <h3 style="margin-bottom: 1rem;">Filters</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div class="form-group">
                <label class="form-label">Status</label>
                <select id="filter_status" class="form-control">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                    <option value="failed">Failed</option>
                    <option value="processing">Processing</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Type</label>
                <select id="filter_type" class="form-control">
                    <option value="">All Types</option>
                    <option value="deposit">Deposit</option>
                    <option value="withdrawal">Withdrawal</option>
                    <option value="transfer">Transfer</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Date From</label>
                <input type="date" id="filter_date_from" class="form-control">
            </div>
            
            <div class="form-group">
                <label class="form-label">Date To</label>
                <input type="date" id="filter_date_to" class="form-control">
            </div>
        </div>
        <div style="margin-top: 1rem;">
            <button onclick="applyFilters()" class="btn btn-primary">Apply Filters</button>
            <button onclick="clearFilters()" class="btn btn-warning">Clear</button>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table id="transactions-table" class="table">
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Customer</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- View Transaction Modal -->
<div id="viewTransactionModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: white; margin: 2% auto; padding: 2rem; border-radius: 8px; width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="margin: 0;">Transaction Details</h3>
            <button onclick="closeViewModal()" class="btn btn-warning">&times;</button>
        </div>
        <div id="transaction-details"></div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<script>
let transactionsTable;

$(document).ready(function() {
    transactionsTable = $('#transactions-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("finance.transactions") }}',
            data: function(d) {
                d.status = $('#filter_status').val();
                d.type = $('#filter_type').val();
                d.date_from = $('#filter_date_from').val();
                d.date_to = $('#filter_date_to').val();
            }
        },
        columns: [
            { data: 'transaction_id', name: 'transaction_id' },
            { data: 'customer.name', name: 'customer.name' },
            { data: 'type', name: 'type' },
            { data: 'amount_formatted', name: 'amount', orderable: true, searchable: false },
            { data: 'status_badge', name: 'status', orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[5, 'desc']],
        language: {
            search: "Search transactions:",
            lengthMenu: "Show _MENU_ entries"
        }
    });
});

function applyFilters() {
    transactionsTable.ajax.reload();
}

function clearFilters() {
    $('#filter_status').val('');
    $('#filter_type').val('');
    $('#filter_date_from').val('');
    $('#filter_date_to').val('');
    transactionsTable.ajax.reload();
}

function viewTransaction(transactionId) {
    fetch(`/finance/transactions/${transactionId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const transaction = data.transaction;
                
                document.getElementById('transaction-details').innerHTML = `
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                        <div>
                            <h4>Transaction Information</h4>
                            <p><strong>Transaction ID:</strong> ${transaction.transaction_id}</p>
                            <p><strong>Type:</strong> ${transaction.type}</p>
                            <p><strong>Amount:</strong> ${transaction.currency} ${parseFloat(transaction.amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</p>
                            <p><strong>Status:</strong> <span class="badge badge-${transaction.status === 'completed' ? 'success' : (transaction.status === 'pending' ? 'warning' : 'danger')}">${transaction.status}</span></p>
                        </div>
                        <div>
                            <h4>Customer Information</h4>
                            <p><strong>Name:</strong> ${transaction.customer.name}</p>
                            <p><strong>Email:</strong> ${transaction.customer.email}</p>
                            <p><strong>Phone:</strong> ${transaction.customer.phone || 'N/A'}</p>
                        </div>
                    </div>
                    
                    <div>
                        <h4>Additional Details</h4>
                        <p><strong>Description:</strong> ${transaction.description || 'N/A'}</p>
                        <p><strong>Created:</strong> ${new Date(transaction.created_at).toLocaleString()}</p>
                        ${transaction.processed_at ? `<p><strong>Processed:</strong> ${new Date(transaction.processed_at).toLocaleString()}</p>` : ''}
                    </div>
                `;
                
                document.getElementById('viewTransactionModal').style.display = 'block';
            }
        });
}

function closeViewModal() {
    document.getElementById('viewTransactionModal').style.display = 'none';
}

function exportTransactions() {
    alert('Export functionality will be implemented with your preferred format (CSV/Excel/PDF)');
}
</script>
@endpush
@endsection