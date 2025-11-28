@extends('core::layouts.app')

@section('title', 'Support Tickets')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <h2 style="margin: 0;">Support Tickets</h2>
        <a href="{{ route('customer_care.support_tickets.create') }}" class="btn btn-primary">
            <i>➕</i> Create Ticket
        </a>
    </div>

    <div style="overflow-x: auto;">
        <table id="tickets-table" class="table">
            <thead>
                <tr>
                    <th>Ticket #</th>
                    <th>Customer</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Assigned To</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@push('scripts')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#tickets-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("customer_care.support_tickets.data") }}',
        columns: [
            { data: 'ticket_number', name: 'ticket_number' },
            { data: 'customer.name', name: 'customer.name' },
            { data: 'subject', name: 'subject' },
            { data: 'status_badge', name: 'status', orderable: false, searchable: false },
            { data: 'priority_badge', name: 'priority', orderable: false, searchable: false },
            { data: 'assigned_to.name', name: 'assignedTo.name' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        responsive: true,
        language: {
            search: "Search tickets:",
            lengthMenu: "Show _MENU_ entries"
        }
    });
});
</script>
@endpush
@endsection