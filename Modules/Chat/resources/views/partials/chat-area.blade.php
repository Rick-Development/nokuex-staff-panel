<!-- Chat Header -->
<div style="padding: 1rem 1.5rem; border-bottom: 1px solid #e0e0e0; display: flex; align-items: center; justify-content: space-between; background: white; z-index: 10;">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <button onclick="document.querySelector('.chat-sidebar').style.display='flex'; document.querySelector('.chat-main').style.display='none';" class="mobile-back" style="display: none; background: none; border: none; color: var(--primary-color); font-size: 1.5rem; margin-right: 0.5rem; cursor: pointer;">&larr;</button>
        <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">
            {{ substr($otherStaff->name, 0, 1) }}
        </div>
        <div>
            <h2 style="font-size: 1.125rem; font-weight: 600; color: var(--primary-color); margin: 0;">{{ $otherStaff->name }}</h2>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span style="width: 8px; height: 8px; background: #2ecc71; border-radius: 50%;"></span>
                <span style="font-size: 0.75rem; color: #666;">{{ ucfirst($otherStaff->role ?? 'Staff') }}</span>
            </div>
        </div>
    </div>
    <div style="display: flex; gap: 1rem;">
        <button style="background: none; border: none; font-size: 1.25rem; cursor: pointer; color: #999;">📞</button>
        <button style="background: none; border: none; font-size: 1.25rem; cursor: pointer; color: #999;">📹</button>
        <button style="background: none; border: none; font-size: 1.25rem; cursor: pointer; color: #999;">ℹ️</button>
    </div>
</div>

<!-- Messages -->
<div id="messagesContainer" style="flex: 1; overflow-y: auto; padding: 1.5rem; background: #f0f2f5; background-image: radial-gradient(#e1e4e8 1px, transparent 1px); background-size: 20px 20px;">
    @foreach($messages as $message)
        @if($message->sender_id == Auth::guard('staff')->id())
            <!-- Sent Message -->
            <div style="display: flex; justify-content: flex-end; margin-bottom: 1rem;">
                <div style="max-width: 70%;">
                    <div style="background: linear-gradient(135deg, var(--primary-color), #3a4b7c); color: white; padding: 0.75rem 1rem; border-radius: 18px 18px 4px 18px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        <p style="margin: 0; word-wrap: break-word; line-height: 1.5;">{{ $message->message }}</p>
                    </div>
                    <div style="text-align: right; margin-top: 0.25rem;">
                        <span style="font-size: 0.7rem; color: #999;">{{ $message->created_at->format('H:i') }}</span>
                        <span style="font-size: 0.7rem; color: var(--primary-color);">✓✓</span>
                    </div>
                </div>
            </div>
        @else
            <!-- Received Message -->
            <div style="display: flex; justify-content: flex-start; margin-bottom: 1rem;">
                <div style="max-width: 70%;">
                    <div style="background: white; color: #333; padding: 0.75rem 1rem; border-radius: 18px 18px 18px 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                        <p style="margin: 0; word-wrap: break-word; line-height: 1.5;">{{ $message->message }}</p>
                    </div>
                    <div style="margin-top: 0.25rem;">
                        <span style="font-size: 0.7rem; color: #999;">{{ $message->created_at->format('H:i') }}</span>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>

<!-- Input Area -->
<div style="padding: 1rem 1.5rem; background: white; border-top: 1px solid #e0e0e0;">
    <form action="{{ route('staff.chat.send', $otherStaff->id) }}" method="POST" id="messageForm" style="display: flex; gap: 1rem; align-items: center;">
        @csrf
        <button type="button" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #999;">📎</button>
        <div style="flex: 1; position: relative;">
            <input type="text" name="message" id="messageInput" placeholder="Type a message..." style="width: 100%; padding: 0.875rem 1.25rem; border: 1px solid #e0e0e0; border-radius: 24px; outline: none; background: #f8f9fa; transition: all 0.2s;" onfocus="this.style.background='white'; this.style.borderColor='var(--primary-color)';" onblur="this.style.background='#f8f9fa'; this.style.borderColor='#e0e0e0';">
        </div>
        <button type="submit" style="width: 48px; height: 48px; border-radius: 50%; background: var(--primary-color); color: white; border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: transform 0.1s;" onmousedown="this.style.transform='scale(0.95)'" onmouseup="this.style.transform='scale(1)'">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
        </button>
    </form>
</div>

<script>
    // Initialize chat functionality
    function initChat() {
        const container = document.getElementById('messagesContainer');
        if (container) container.scrollTop = container.scrollHeight;
        
        // Store last message ID
        let lastMessageId = {{ $messages->last()->id ?? 0 }};
        
        // Clear existing interval if any
        if (window.chatInterval) clearInterval(window.chatInterval);
        
        // Auto-refresh messages every 1 second (Real-time)
        window.chatInterval = setInterval(function() {
            fetch(`{{ route('staff.chat.messages', $otherStaff->id) }}?after_id=${lastMessageId}`)
                .then(response => response.json())
                .then(messages => {
                    if (messages.length === 0) return;
                    
                    const container = document.getElementById('messagesContainer');
                    const currentStaffId = {{ Auth::guard('staff')->id() }};
                    let shouldScroll = container.scrollTop + container.clientHeight >= container.scrollHeight - 50;
                    
                    messages.forEach(message => {
                        // Update last ID
                        if (message.id > lastMessageId) lastMessageId = message.id;
                        
                        const isSent = message.sender_id === currentStaffId;
                        const messageDiv = document.createElement('div');
                        messageDiv.style.display = 'flex';
                        messageDiv.style.justifyContent = isSent ? 'flex-end' : 'flex-start';
                        messageDiv.style.marginBottom = '1rem';
                        messageDiv.style.opacity = '0';
                        messageDiv.style.transition = 'opacity 0.3s ease';
                        
                        const time = new Date(message.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false });
                        
                        messageDiv.innerHTML = `
                            <div style="max-width: 70%;">
                                <div style="${isSent ? 'background: linear-gradient(135deg, var(--primary-color), #3a4b7c); color: white; border-radius: 18px 18px 4px 18px;' : 'background: white; color: #333; border-radius: 18px 18px 18px 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);'} padding: 0.75rem 1rem;">
                                    <p style="margin: 0; word-wrap: break-word; line-height: 1.5;">${message.message}</p>
                                </div>
                                <div style="${isSent ? 'text-align: right;' : ''} margin-top: 0.25rem;">
                                    <span style="font-size: 0.7rem; color: #999;">${time}</span>
                                    ${isSent ? '<span style="font-size: 0.7rem; color: var(--primary-color);">✓✓</span>' : ''}
                                </div>
                            </div>
                        `;
                        container.appendChild(messageDiv);
                        
                        // Fade in effect
                        setTimeout(() => { messageDiv.style.opacity = '1'; }, 10);
                    });
                    
                    if (shouldScroll) {
                        container.scrollTop = container.scrollHeight;
                    }
                });
        }, 1000);
        
        // Handle form submission
        const form = document.getElementById('messageForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const input = document.getElementById('messageInput');
                const message = input.value.trim();
                if (!message) return;
                
                const formData = new FormData(this);
                
                // Clear input immediately
                input.value = '';

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
            });
        }
    }
    
    // Run init
    initChat();
</script>
