@extends('core::layouts.app')

@section('content')
<div class="card">
    <h2>Edit Staff</h2>
    
    <form action="{{ route('core.staff.update', $staff->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="margin-bottom: 1rem;">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value="{{ $staff->name }}" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        
        <div style="margin-bottom: 1rem;">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ $staff->email }}" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        
        <div style="margin-bottom: 1rem;">
            <label for="password">New Password (leave blank to keep current)</label>
            <input type="password" name="password" id="password" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        
        <div style="margin-bottom: 1rem;">
            <label for="password_confirmation">Confirm New Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        
        <div style="margin-bottom: 1rem;">
            <label for="role_id">Role</label>
            <select name="role_id" id="role_id" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">Select Role</option>
                @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ $staff->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div style="margin-bottom: 1rem;">
            <label for="phone">Phone</label>
            <input type="text" name="phone" id="phone" value="{{ $staff->phone }}" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        
        <div style="margin-bottom: 1rem;">
            <label>
                <input type="checkbox" name="is_active" value="1" {{ $staff->is_active ? 'checked' : '' }}> Active Staff
            </label>
        </div>
        
        <div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('core.staff.index') }}" class="btn btn-warning">Cancel</a>
        </div>
    </form>
</div>
@endsection