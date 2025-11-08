@extends('core::layouts.app')

@section('title', 'CRM - Customer Management')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <h2 style="margin: 0;">Customer Relationship Management</h2>
        <div style="display: flex; gap: 0.5rem;">
            <button onclick="exportCustomers()" class="btn btn-success">
                <i>📤</i> Export
            </button>
            <a href="{{ route('customercare.crm.create') }}" class="btn btn-primary">
                <i>➕</i> Add Customer
            </a>
        </div>
    </div>

    <div class="card" style="margin-bottom: 1.5rem;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div style="text-align: center;">
                <div style="font-size: 2rem; font-weight: bold; color: var(--primary-color);">{{ \Modules\CustomerCare\Entities\Customer::count() }}</div>
                <div style="color: #666;">Total Customers</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 2rem; font-weight: bold; color: var(--secondary-color);">{{ \Modules\CustomerCare\Entities\Customer::where('status', 'active')->count() }}</div>
                <div style="color: #666;">Active Customers</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 2rem; font-weight: bold; color: var(--accent-color);">{{ \Modules\CustomerCare\Entities\SupportTicket::where('status', 'open')->count() }}</div>
                <div style="color: #666;">Open Tickets</div>
            </div>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table id="customers-table" class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Tickets</th>
                    <th>Disputes</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- View Customer Modal -->
<div id="viewCustomerModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: white; margin: 2% auto; padding: 2rem; border-radius: 8px; width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="margin: 0;">Customer Details</h3>
            <button onclick="closeViewModal()" class="btn btn-warning">&times;</button>
        </div>
        <div id="customer-details"></div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<script>
let customersTable;

$(document).ready(function() {
    customersTable = $('#customers-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("customercare.crm") }}',
            type: 'GET'
        },
        columns: [
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'phone', name: 'phone' },
            { data: 'status', name: 'status' },
            { data: 'tickets_count', name: 'tickets_count', orderable: false, searchable: false },
            { data: 'disputes_count', name: 'disputes_count', orderable: false, searchable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        language: {
            search: "Search customers:",
            lengthMenu: "Show _MENU_ entries"
        }
    });
});

function viewCustomer(customerId) {
    fetch(`/customercare/customer/${customerId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const customer = data.customer;
                const tickets = data.tickets;
                const disputes = data.disputes;
                
                let ticketsHtml = tickets.map(ticket => `
                    <div style="padding: 0.5rem; border: 1px solid #eee; border-radius: 4px; margin-bottom: 0.5rem;">
                        <strong>${ticket.ticket_number}</strong>: ${ticket.subject}<br>
                        <small>Priority: ${ticket.priority} | Status: ${ticket.status}</small>
                    </div>
                `).join('');
                
                let disputesHtml = disputes.map(dispute => `
                    <div style="padding: 0.5rem; border: 1px solid #eee; border-radius: 4px; margin-bottom: 0.5rem;">
                        <strong>${dispute.dispute_number}</strong>: ${dispute.type}<br>
                        <small>Amount: $${dispute.amount} | Status: ${dispute.status}</small>
                    </div>
                `).join('');
                
                document.getElementById('customer-details').innerHTML = `
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                        <div>
                            <h4>Basic Information</h4>
                            <p><strong>Name:</strong> ${customer.name}</p>
                            <p><strong>Email:</strong> ${customer.email}</p>
                            <p><strong>Phone:</strong> ${customer.phone || 'N/A'}</p>
                            <p><strong>Status:</strong> <span class="badge badge-${customer.status === 'active' ? 'success' : 'danger'}">${customer.status}</span></p>
                        </div>
                        <div>
                            <h4>Address</h4>
                            <p>${customer.address || 'No address provided'}</p>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                        <div>
                            <h4>Support Tickets (${tickets.length})</h4>
                            ${ticketsHtml || '<p>No tickets found</p>'}
                        </div>
                        <div>
                            <h4>Disputes (${disputes.length})</h4>
                            ${disputesHtml || '<p>No disputes found</p>'}
                        </div>
                    </div>
                `;
                
                document.getElementById('viewCustomerModal').style.display = 'block';
            }
        });
}

function closeViewModal() {
    document.getElementById('viewCustomerModal').style.display = 'none';
}

function editCustomer(customerId) {
    window.location.href = `/customercare/customer/${customerId}/edit`;
}

function exportCustomers() {
    // Implement export functionality
    alert('Export functionality coming soon...');
}
</script>
@endpush
@endsection