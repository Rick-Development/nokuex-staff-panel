@extends('chat::layouts.app')

@section('content')
<div class="card">
    <h2>{{ $channel->name }}</h2>
    <p>{{ $channel->description }}</p>
    
    <div id="chat-messages" style="height: 400px; overflow-y: scroll; border: 1px solid #ddd; padding: 1rem; margin-bottom: 1rem;">
        @foreach($messages as $message)
        <div style="margin-bottom: 1rem;">
            <strong>{{ $message->staff->name }}</strong>: 
            <span>{{ $message->message }}</span>
            <small style="color: #888;">{{ $message->created_at->format('H:i') }}</small>
        </div>
        @endforeach
    </div>
    
    <form id="message-form">
        @csrf
        <div style="display: flex;">
            <input type="text" name="message" id="message-input" style="flex: 1; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;" required>
            <button type="submit" class="btn btn-primary" style="margin-left: 0.5rem;">Send</button>
        </div>
    </form>
</div>

<script>
    // Scroll to bottom of messages
    const messagesContainer = document.getElementById('chat-messages');
    messagesContainer.scrollTop = messagesContainer.scrollHeight;

    // Handle message form submission
    document.getElementById('message-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const messageInput = document.getElementById('message-input');
        const message = messageInput.value;
        
        fetch("{{ route('chat.message.send', $channel->id) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            // Add the new message to the chat
            const messageDiv = document.createElement('div');
            messageDiv.innerHTML = `
                <strong>${data.staff.name}</strong>: 
                <span>${data.message}</span>
                <small style="color: #888;">Just now</small>
            `;
            messagesContainer.appendChild(messageDiv);
            messageInput.value = '';
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        });
    });
</script>
@endsection