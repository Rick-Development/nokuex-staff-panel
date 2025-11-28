@extends('core::layouts.app')

@section('content')
<div class="card">
    <h2>Create Staff</h2>
    
    <form action="{{ route('core.staff.store') }}" method="POST">
        @csrf
        <div style="margin-bottom: 1rem;">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        
        <div style="margin-bottom: 1rem;">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        
        <div style="margin-bottom: 1rem;">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        
        <div style="margin-bottom: 1rem;">
            <label for="role_id">Role</label>
            <select name="role_id" id="role_id" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">Select Role</option>
                @foreach($roles as $role)
                <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div style="margin-bottom: 1rem;">
            <label for="phone">Phone</label>
            <input type="text" name="phone" id="phone" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        
        <div>
            <button type="submit" class="btn btn-primary">Create</button>
            <a href="{{ route('core.staff.index') }}" class="btn btn-warning">Cancel</a>
        </div>
    </form>
</div>
@endsection