@extends('core::layouts.app')

@section('content')
<div class="card">
    <h2>Notifications</h2>
    
    <div style="margin-bottom: 1rem;">
        <a href="{{ route('core.notification.create') }}" class="btn btn-primary">Create Notification</a>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Title</th>
                    <th>Staff</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notifications as $notification)
                <tr>
                    <td>{{ $notification->type }}</td>
                    <td>{{ $notification->title }}</td>
                    <td>{{ $notification->staff->name }}</td>
                    <td>
                        @if($notification->read_at)
                        <span style="color: var(--secondary-color);">Read</span>
                        @else
                        <span style="color: var(--accent-color);">Unread</span>
                        @endif
                    </td>
                    <td>{{ $notification->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('core.notification.show', $notification->id) }}" class="btn btn-primary">View</a>
                        <a href="{{ route('core.notification.edit', $notification->id) }}" class="btn btn-warning">Edit</a>
                        @if(!$notification->read_at)
                        <form action="{{ route('core.notification.mark-read', $notification->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-success">Mark Read</button>
                        </form>
                        @endif
                        <form action="{{ route('core.notification.destroy', $notification->id) }}" method="POST" style="display: inline;">
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