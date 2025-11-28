@extends('core::layouts.app')

@section('content')
<div class="card">
    <h2>Edit Notification</h2>
    
    <form action="{{ route('core.notification.update', $notification->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="margin-bottom: 1rem;">
            <label for="type">Type</label>
            <select name="type" id="type" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                <option value="info" {{ $notification->type == 'info' ? 'selected' : '' }}>Info</option>
                <option value="warning" {{ $notification->type == 'warning' ? 'selected' : '' }}>Warning</option>
                <option value="success" {{ $notification->type == 'success' ? 'selected' : '' }}>Success</option>
                <option value="error" {{ $notification->type == 'error' ? 'selected' : '' }}>Error</option>
                <option value="system" {{ $notification->type == 'system' ? 'selected' : '' }}>System</option>
            </select>
        </div>
        
        <div style="margin-bottom: 1rem;">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" value="{{ $notification->title }}" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        
        <div style="margin-bottom: 1rem;">
            <label for="message">Message</label>
            <textarea name="message" id="message" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; height: 100px;">{{ $notification->message }}</textarea>
        </div>
        
        <div style="margin-bottom: 1rem;">
            <label for="staff_id">Staff Member</label>
            <select name="staff_id" id="staff_id" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">Select Staff</option>
                @foreach($staffs as $staff)
                <option value="{{ $staff->id }}" {{ $notification->staff_id == $staff->id ? 'selected' : '' }}>
                    {{ $staff->name }} ({{ $staff->email }})
                </option>
                @endforeach
            </select>
        </div>
        
        <div style="margin-bottom: 1rem;">
            <label>
                <input type="checkbox" name="mark_read" value="1" {{ $notification->read_at ? 'checked' : '' }}> Mark as Read
            </label>
        </div>
        
        <div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('core.notification.index') }}" class="btn btn-warning">Cancel</a>
        </div>
    </form>
</div>
@endsection