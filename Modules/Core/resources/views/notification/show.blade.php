@extends('core::layouts.app')

@section('content')
<div class="card">
    <h2>Notification Details</h2>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        <div>
            <h3>Basic Information</h3>
            <p><strong>Type:</strong> 
                <span style="padding: 0.25rem 0.5rem; border-radius: 4px; color: white; 
                    @if($notification->type == 'info') background: blue;
                    @elseif($notification->type == 'warning') background: orange;
                    @elseif($notification->type == 'success') background: var(--secondary-color);
                    @elseif($notification->type == 'error') background: red;
                    @else background: gray; @endif">
                    {{ $notification->type }}
                </span>
            </p>
            <p><strong>Title:</strong> {{ $notification->title }}</p>
            <p><strong>Message:</strong> {{ $notification->message }}</p>
            <p><strong>Status:</strong> {{ $notification->read_at ? 'Read' : 'Unread' }}</p>
        </div>
        
        <div>
            <h3>Recipient & Timing</h3>
            <p><strong>Staff:</strong> {{ $notification->staff->name }}</p>
            <p><strong>Email:</strong> {{ $notification->staff->email }}</p>
            <p><strong>Created:</strong> {{ $notification->created_at->format('M d, Y H:i') }}</p>
            <p><strong>Read:</strong> {{ $notification->read_at ? $notification->read_at->format('M d, Y H:i') : 'Not read yet' }}</p>
        </div>
    </div>
    
    <div style="margin-top: 2rem;">
        <a href="{{ route('core.notification.edit', $notification->id) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('core.notification.index') }}" class="btn btn-primary">Back to List</a>
        
        @if(!$notification->read_at)
        <form action="{{ route('core.notification.mark-read', $notification->id) }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-success">Mark as Read</button>
        </form>
        @endif
    </div>
</div>
@endsection