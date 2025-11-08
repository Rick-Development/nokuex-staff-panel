@extends('chat::layouts.app')

@section('content')
<div class="card">
    <h2>Chat Channels</h2>
    
    <div style="margin-bottom: 1rem;">
        <button onclick="document.getElementById('createChannelModal').style.display='block'" class="btn btn-primary">Create Channel</button>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($channels as $channel)
                <tr>
                    <td>{{ $channel->name }}</td>
                    <td>{{ $channel->description }}</td>
                    <td>{{ $channel->is_private ? 'Private' : 'Public' }}</td>
                    <td>
                        <a href="{{ route('chat.channel.show', $channel->id) }}" class="btn btn-primary">Join</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Create Channel Modal -->
<div id="createChannelModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: white; margin: 10% auto; padding: 2rem; border-radius: 8px; width: 500px;">
        <h3>Create Channel</h3>
        <form action="{{ route('chat.channel.create') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label for="name">Channel Name</label>
                <input type="text" name="name" id="name" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label for="description">Description</label>
                <textarea name="description" id="description" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;"></textarea>
            </div>
            <div style="margin-bottom: 1rem;">
                <label>
                    <input type="checkbox" name="is_private" value="1"> Private Channel
                </label>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Create</button>
                <button type="button" class="btn btn-warning" onclick="document.getElementById('createChannelModal').style.display='none'">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection