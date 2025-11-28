@extends('core::layouts.app')

@section('content')
<div class="card">
    <h2>Role Details</h2>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        <div>
            <h3>Basic Information</h3>
            <p><strong>Name:</strong> {{ $role->name }}</p>
            <p><strong>Description:</strong> {{ $role->description ?? 'N/A' }}</p>
            <p><strong>Status:</strong> {{ $role->is_active ? 'Active' : 'Inactive' }}</p>
            <p><strong>Created:</strong> {{ $role->created_at->format('M d, Y') }}</p>
        </div>
        
        <div>
            <h3>Staff Members</h3>
            <p><strong>Total Staff:</strong> {{ $role->staffs->count() }}</p>
            
            @if($role->staffs->count() > 0)
            <ul>
                @foreach($role->staffs->take(5) as $staff)
                <li>{{ $staff->name }}</li>
                @endforeach
            </ul>
            @endif
        </div>
    </div>
    
    @if($role->permissions)
    <div style="margin-top: 2rem;">
        <h3>Permissions</h3>
        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
            @foreach($role->permissions as $permission)
            <span style="background: var(--secondary-color); color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">
                {{ $permission }}
            </span>
            @endforeach
        </div>
    </div>
    @endif
    
    <div style="margin-top: 2rem;">
        <a href="{{ route('core.role.edit', $role->id) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('core.role.index') }}" class="btn btn-primary">Back to List</a>
    </div>
</div>
@endsection