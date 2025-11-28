@extends('core::layouts.app')

@section('content')
<div class="card">
    <h2>Edit Role</h2>
    
    <form action="{{ route('core.role.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="margin-bottom: 1rem;">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value="{{ $role->name }}" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        
        <div style="margin-bottom: 1rem;">
            <label for="description">Description</label>
            <textarea name="description" id="description" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">{{ $role->description }}</textarea>
        </div>
        
        <div style="margin-bottom: 1rem;">
            <label>
                <input type="checkbox" name="is_active" value="1" {{ $role->is_active ? 'checked' : '' }}> Active Role
            </label>
        </div>
        
        <div style="margin-bottom: 1rem;">
            <h3>Permissions</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.5rem;">
                @php
                $defaultPermissions = [
                    'staff.create', 'staff.edit', 'staff.delete', 'staff.view',
                    'role.create', 'role.edit', 'role.delete', 'role.view',
                    'notification.create', 'notification.edit', 'notification.delete', 'notification.view',
                    'chat.access', 'chat.create_channel', 'chat.manage_members'
                ];
                @endphp
                
                @foreach($defaultPermissions as $permission)
                <label style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="permissions[]" value="{{ $permission }}" 
                           {{ in_array($permission, $role->permissions ?? []) ? 'checked' : '' }}>
                    {{ $permission }}
                </label>
                @endforeach
            </div>
        </div>
        
        
        <div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('core.role.index') }}" class="btn btn-warning">Cancel</a>
        </div>
    </form>
</div>
@endsection