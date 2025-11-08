@extends('core::layouts.app')

@section('title', 'Support Tickets')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <h2 style="margin: 0;">Support Tickets</h2>
        <button onclick="showCreateTicketModal()" class="btn btn-primary">
            <i>➕</i> Create Ticket
        </button>
    </div>

    <div class="card" style="margin-bottom: 1.5rem;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div style="text-align: center;">
                <div style="font-size: 2rem; font-weight: bold; color: var(--primary-color);">{{ \Modules\CustomerCare\Entities\SupportTicket::where('status', 'open')->count() }}</div>
                <div style="color: #666;">Open Tickets</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 2rem; font-weight: bold; color: var(--secondary-color);">{{ \Modules\CustomerCare\Entities\SupportTicket::where('status', 'in_progress')->count() }}</div>
                <div style="color: #666;">In Progress</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 2rem; font-weight: bold; color: var(--accent-color);">{{ \Modules\CustomerCare\Entities\SupportTicket::where('status', 'resolved')->count() }}</div>
                <div style="color: #666;">Resolved</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 2rem; font-weight: bold; color: #17a2b8;">{{ \Modules\CustomerCare\Entities\SupportTicket::count() }}</div>
                <div style="color: #666;">Total Tickets</div>
            </div>
        </div>
    </div>

    <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem; flex-wrap: wrap;">
        <button onclick="filterTickets('')" class="btn btn-primary btn-sm">All</button>
        <button onclick="filterTickets('open')" class="btn btn-warning btn-sm">Open</button>
        <button onclick="filterTickets('in_progress')" class="btn btn-info btn-sm">In Progress</button>
        <button onclick="filterTickets('resolved')" class="btn btn-success btn-sm">Resolved</button>
        <button onclick="filterTickets('closed')" class="btn btn-secondary btn-sm">Closed</button>
    </div>

    <div style="overflow-x: auto;">
        <table id="tickets-table" class="table">
            <thead>
                <tr>
                    <th>Ticket #</th>
                    <th>Customer</th>
                    <th>Subject</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Create Ticket Modal -->
<div id="createTicketModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: white; margin: 2% auto; padding: 2rem; border-radius: 8px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="margin: 0;">Create Support Ticket</h3>
            <button onclick="closeCreateTicketModal()" class="btn btn-warning">&times;</button>
        </div>
        
        <form id="create-ticket-form">
            @csrf
            
            <div class="form-group">
                <label for="customer_id" class="form-label">Customer *</label>
                <select name="customer_id" id="customer_id" class="form-control" required>
                    <option value="">Select Customer</option>
                    @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label for="subject" class="form-label">Subject *</label>
                <input type="text" name="subject" id="subject" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="description" class="form-label">Description *</label>
                <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="priority" class="form-label">Priority *</label>
                    <select name="priority" id="priority" class="form-control" required>
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="assigned_to" class="form-label">Assign To</label>
                    <select name="assigned_to" id="assigned_to" class="form-control">
                        <option value="">Unassigned</option>
                        @foreach($staff as $member)
                        <option value="{{ $member->id }}">{{ $member->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div style="display: flex; gap: 0.5rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">Create Ticket</button>
                <button type="button" onclick="closeCreateTicketModal()" class="btn btn-warning">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- View Ticket Modal -->
<div id="viewTicketModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: white; margin: 2% auto; padding: 2rem; border-radius: 8px; width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="margin: 0;">Ticket Details</h3>
            <button onclick="closeViewTicketModal()" class="btn btn-warning">&times;</button>
        </div>
        <div id="ticket-details"></div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<script>
let ticketsTable;
let currentStatusFilter = '';

$(document).ready(function() {
    ticketsTable = $('#tickets-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("customercare.tickets") }}',
            data: function(d) {
                d.status = currentStatusFilter;
            }
        },
        columns: [
            { data: 'ticket_number', name: 'ticket_number' },
            { data: 'customer.name', name: 'customer.name' },
            { data: 'subject', name: 'subject' },
            { data: 'priority_badge', name: 'priority', orderable: false, searchable: false },
            { data: 'status_badge', name: 'status', orderable: false, searchable: false },
            { data: 'assigned_to.name', name: 'assigned_to.name' },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        language: {
            search: "Search tickets:",
            lengthMenu: "Show _MENU_ entries"
        }
    });
});

function filterTickets(status) {
    currentStatusFilter = status;
    ticketsTable.ajax.reload();
}

function showCreateTicketModal() {
    document.getElementById('createTicketModal').style.display = 'block';
}

function closeCreateTicketModal() {
    document.getElementById('createTicketModal').style.display = 'none';
}

function viewTicket(ticketId) {
    fetch(`/customercare/tickets/${ticketId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const ticket = data.ticket;
                
                document.getElementById('ticket-details').innerHTML = `
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                        <div>
                            <h4>Ticket Information</h4>
                            <p><strong>Ticket #:</strong> ${ticket.ticket_number}</p>
                            <p><strong>Subject:</strong> ${ticket.subject}</p>
                            <p><strong>Priority:</strong> <span class="badge badge-${ticket.priority === 'high' ? 'danger' : (ticket.priority === 'medium' ? 'warning' : 'success')}">${ticket.priority}</span></p>
                            <p><strong>Status:</strong> <span class="badge badge-${ticket.status === 'open' ? 'primary' : (ticket.status === 'in_progress' ? 'warning' : (ticket.status === 'resolved' ? 'success' : 'secondary'))}">${ticket.status.replace('_', ' ')}</span></p>
                        </div>
                        <div>
                            <h4>Assignment</h4>
                            <p><strong>Customer:</strong> ${ticket.customer.name} (${ticket.customer.email})</p>
                            <p><strong>Assigned To:</strong> ${ticket.assigned_to ? ticket.assigned_to.name : 'Unassigned'}</p>
                            <p><strong>Created:</strong> ${new Date(ticket.created_at).toLocaleString()}</p>
                            ${ticket.resolved_at ? `<p><strong>Resolved:</strong> ${new Date(ticket.resolved_at).toLocaleString()}</p>` : ''}
                        </div>
                    </div>
                    
                    <div>
                        <h4>Description</h4>
                        <div style="background: #f8f9fa; padding: 1rem; border-radius: 4px;">
                            ${ticket.description}
                        </div>
                    </div>
                `;
                
                document.getElementById('viewTicketModal').style.display = 'block';
            }
        });
}

function closeViewTicketModal() {
    document.getElementById('viewTicketModal').style.display = 'none';
}

// Create ticket form submission
document.getElementById('create-ticket-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const btn = this.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    
    btn.innerHTML = 'Creating...';
    btn.disabled = true;
    
    fetch('{{ route("customercare.tickets.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeCreateTicketModal();
            ticketsTable.ajax.reload();
            this.reset();
        } else {
            alert('Error creating ticket: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        alert('Error creating ticket. Please try again.');
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
});
</script>
@endpush
@endsection