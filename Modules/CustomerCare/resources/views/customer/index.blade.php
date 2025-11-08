@extends('core::layouts.app')

@section('title', 'Customer Management')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <h2 style="margin: 0;">Customer Management</h2>
        <a href="{{ route('customercare.customer.create') }}" class="btn btn-primary">
            <i>➕</i> Add New Customer
        </a>
    </div>

    <div style="overflow-x: auto;">
        <table id="customer-table" class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#customer-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("customercare.customer.data") }}',
        columns: [
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'phone', name: 'phone' },
            { data: 'status', name: 'status' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });
});
</script>
@endpush