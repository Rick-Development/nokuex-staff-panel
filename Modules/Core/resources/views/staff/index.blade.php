@extends('core::layouts.app')

@section('title', 'Staff Management')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <h2 style="margin: 0;">Staff Management</h2>
        <a href="{{ route('core.staff.create') }}" class="btn btn-primary">
            <i>➕</i> Add New Staff
        </a>
    </div>

    <div style="overflow-x: auto;">
        <table id="staff-table" class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: white; margin: 15% auto; padding: 2rem; border-radius: 8px; width: 90%; max-width: 400px;">
        <h3 style="margin-bottom: 1rem;">Confirm Delete</h3>
        <p style="margin-bottom: 1.5rem;">Are you sure you want to delete this staff member?</p>
        <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
            <button id="confirmDelete" class="btn btn-danger">Delete</button>
            <button onclick="closeDeleteModal()" class="btn btn-warning">Cancel</button>
        </div>
    </div>
</div>

@push('scripts')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
let staffIdToDelete = null;
let staffTable = null;

$(document).ready(function() {
    staffTable = $('#staff-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("core.staff.data") }}',
        columns: [
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'role.name', name: 'role.name' },
            { data: 'status_badge', name: 'is_active', orderable: false, searchable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        responsive: true,
        language: {
            search: "Search staff:",
            lengthMenu: "Show _MENU_ entries"
        }
    });
});

function deleteStaff(id) {
    staffIdToDelete = id;
    document.getElementById('deleteModal').style.display = 'block';
}

function closeDeleteModal() {
    staffIdToDelete = null;
    document.getElementById('deleteModal').style.display = 'none';
}

document.getElementById('confirmDelete').addEventListener('click', function() {
    if (staffIdToDelete) {
        fetch(`/core/staff/${staffIdToDelete}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                staffTable.ajax.reload();
                closeDeleteModal();
            }
        });
    }
});
</script>
@endpush
@endsection