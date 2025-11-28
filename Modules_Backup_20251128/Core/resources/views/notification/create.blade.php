@extends('core::layouts.app')

@section('content')
<div class="card">
    <h2>Create Notification</h2>
    
    <form action="{{ route('core.notification.store') }}" method="POST">
        @csrf
        
        <div style="margin-bottom: 1rem;">
            <label for="type">Type</label>
            <select name="type" id="type" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">Select Type</option>
                <option value="info">Info</option>
                <option value="warning">Warning</option>
                <option value="success">Success</option>
                <option value="error">Error</option>
                <option value="system">System</option>
            </select>
        </div>
        
        <div style="margin-bottom: 1rem;">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        
        <div style="margin-bottom: 1rem;">
            <label for="message">Message</label>
            <textarea name="message" id="message" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; height: 100px;"></textarea>
        </div>
        
        <div style="margin-bottom: 1rem;">
            <label for="staff_id">Staff Member</label>
            <select name="staff_id" id="staff_id" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">Select Staff</option>
                @foreach($staffs as $staff)
                <option value="{{ $staff->id }}">{{ $staff->name }} ({{ $staff->email }})</option>
                @endforeach
            </select>
        </div>
        
        <div>
            <button type="submit" class="btn btn-primary">Create</button>
            <a href="{{ route('core.notification.index') }}" class="btn btn-warning">Cancel</a>
        </div>
    </form>
</div>
@endsection