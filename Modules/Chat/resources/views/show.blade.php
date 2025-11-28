@extends('core::layouts.master')

@section('content')
<div style="height: calc(100vh - 100px); display: flex; flex-direction: column; overflow: hidden;">
    <div style="flex: 1; display: grid; grid-template-columns: 350px 1fr; background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden; margin: 1rem;">
        
        <!-- Sidebar (Conversation List) -->
        <div class="chat-sidebar" style="border-right: 1px solid #e0e0e0; display: flex; flex-direction: column; background: #f8f9fa;">
            <div style="padding: 1.5rem; border-bottom: 1px solid #e0e0e0; background: white;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <a href="{{ route('staff.chat.index') }}" class="mobile-back" style="display: none; color: var(--primary-color); text-decoration: none; font-size: 1.25rem;">&larr;</a>
                    <h1 style="font-size: 1.5rem; font-weight: bold; color: var(--primary-color); margin: 0;">Messages</h1>
                </div>
                <div style="margin-top: 1rem; position: relative;">
                    <input type="text" placeholder="Search conversations..." style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border: 1px solid #e0e0e0; border-radius: 8px; background: #f8f9fa; outline: none;">
                    <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #999;">🔍</span>
                </div>
            </div>
            
            <div style="flex: 1; overflow-y: auto;">
                @forelse($conversations as $conversation)
                    <a href="{{ route('staff.chat.show', $conversation['staff']->id) }}" style="display: block; padding: 1rem; border-bottom: 1px solid #f0f0f0; text-decoration: none; transition: all 0.2s; background: {{ $conversation['staff']->id == $otherStaff->id ? '#e3f2fd' : 'white' }}; border-left: 4px solid {{ $conversation['staff']->id == $otherStaff->id ? 'var(--primary-color)' : 'transparent' }};" onmouseover="this.style.background='{{ $conversation['staff']->id == $otherStaff->id ? '#e3f2fd' : '#f0f4f8' }}'" onmouseout="this.style.background='{{ $conversation['staff']->id == $otherStaff->id ? '#e3f2fd' : 'white' }}'">
                        <div style="display: flex; gap: 1rem;">
                            <div style="position: relative;">
                                <div style="width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, var(--primary-color), #1a1f38); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.125rem;">
                                    {{ substr($conversation['staff']->name, 0, 1) }}
                                </div>
                                @if($conversation['staff']->is_active)
                                    <div style="position: absolute; bottom: 2px; right: 2px; width: 12px; height: 12px; background: #2ecc71; border: 2px solid white; border-radius: 50%;"></div>
                                @endif
                            </div>
                            <div style="flex: 1; min-width: 0;">
                                <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.25rem;">
                                    <h3 style="font-weight: 600; color: var(--primary-color); margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $conversation['staff']->name }}</h3>
                                    @if($conversation['last_message'])
                                        <span style="font-size: 0.75rem; color: #999;">{{ $conversation['last_message']->created_at->format('H:i') }}</span>
                                    @endif
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <p style="font-size: 0.875rem; color: #666; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 180px;">
                                        {{ $conversation['last_message'] ? Str::limit($conversation['last_message']->message, 30) : 'Start a conversation' }}
                                    </p>
                                    @if($conversation['unread_count'] > 0)
                                        <span style="background: var(--accent-color); color: white; border-radius: 10px; padding: 0.125rem 0.5rem; font-size: 0.75rem; font-weight: bold; min-width: 20px; text-align: center;">
                                            {{ $conversation['unread_count'] }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div style="text-align: center; padding: 3rem 1rem; color: #999;">
                        <div style="font-size: 2rem; margin-bottom: 1rem;">💬</div>
                        <p>No conversations yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Main Chat Area -->
        <div class="chat-main" style="display: flex; flex-direction: column; background: white;">
            <!-- Chat Header -->
            <div style="padding: 1rem 1.5rem; border-bottom: 1px solid #e0e0e0; display: flex; align-items: center; justify-content: space-between; background: white; z-index: 10;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <a href="{{ route('staff.chat.index') }}" class="mobile-back" style="display: none; color: var(--primary-color); text-decoration: none; font-size: 1.5rem; margin-right: 0.5rem;">&larr;</a>
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                        {{ substr($otherStaff->name, 0, 1) }}
                    </div>
                    <div>
                        <h2 style="font-size: 1.125rem; font-weight: 600; color: var(--primary-color); margin: 0;">{{ $otherStaff->name }}</h2>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="width: 8px; height: 8px; background: #2ecc71; border-radius: 50%;"></span>
                            <span style="font-size: 0.75rem; color: #666;">Active Now</span>
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
        </div>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        div[style*="grid-template-columns"] {
            grid-template-columns: 1fr !important;
        }
        .chat-sidebar {
            display: none !important;
        }
        .chat-main {
            display: flex !important;
            height: 100%;
        }
        .mobile-back {
            display: block !important;
        }
    }
</style>

<script>
    // Auto-scroll to bottom
    function scrollToBottom() {
        const container = document.getElementById('messagesContainer');
        container.scrollTop = container.scrollHeight;
    }
    
    // Scroll on load
    scrollToBottom();
    
    // Auto-refresh messages every 3 seconds
    setInterval(function() {
        fetch('{{ route('staff.chat.messages', $otherStaff->id) }}')
            .then(response => response.json())
            .then(messages => {
                const container = document.getElementById('messagesContainer');
                const currentStaffId = {{ Auth::guard('staff')->id() }};
                
                // Only update if there are new messages (simple check by count or ID would be better, but full replace works for now)
                // For smoother UX, we should ideally append only new ones, but this is a quick implementation
                
                container.innerHTML = '';
                messages.forEach(message => {
                    const isSent = message.sender_id === currentStaffId;
                    const messageDiv = document.createElement('div');
                    messageDiv.style.display = 'flex';
                    messageDiv.style.justifyContent = isSent ? 'flex-end' : 'flex-start';
                    messageDiv.style.marginBottom = '1rem';
                    
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
                });
                
                // Only scroll if we were already near bottom or it's a new load
                // For now, just scroll
                // scrollToBottom(); 
            });
    }, 3000);
    
    // Handle form submission
    document.getElementById('messageForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('messageInput');
        const message = input.value.trim();
        if (!message) return;
        
        const formData = new FormData(this);
        
        // Optimistic UI update
        const container = document.getElementById('messagesContainer');
        const messageDiv = document.createElement('div');
        messageDiv.style.display = 'flex';
        messageDiv.style.justifyContent = 'flex-end';
        messageDiv.style.marginBottom = '1rem';
        const time = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false });
        
        messageDiv.innerHTML = `
            <div style="max-width: 70%;">
                <div style="background: linear-gradient(135deg, var(--primary-color), #3a4b7c); color: white; padding: 0.75rem 1rem; border-radius: 18px 18px 4px 18px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <p style="margin: 0; word-wrap: break-word; line-height: 1.5;">${message}</p>
                </div>
                <div style="text-align: right; margin-top: 0.25rem;">
                    <span style="font-size: 0.7rem; color: #999;">${time}</span>
                    <span style="font-size: 0.7rem; color: var(--primary-color);">✓</span>
                </div>
            </div>
        `;
        container.appendChild(messageDiv);
        scrollToBottom();
        input.value = '';

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
    });
</script>
@endsection
