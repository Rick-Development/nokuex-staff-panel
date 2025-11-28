@extends('core::layouts.app')

@section('content')
<div class="card">
    <h2>Role Management</h2>
    
    <div style="margin-bottom: 1rem;">
        <a href="{{ route('core.role.create') }}" class="btn btn-primary">Add New Role</a>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                <tr>
                    <td>{{ $role->name }}</td>
                    <td>{{ $role->description }}</td>
                    <td>{{ $role->is_active ? 'Active' : 'Inactive' }}</td>
                    <td>
                        <a href="{{ route('core.role.show', $role->id) }}" class="btn btn-primary">View</a>
                        <a href="{{ route('core.role.edit', $role->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('core.role.destroy', $role->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection