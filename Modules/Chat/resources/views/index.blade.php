@extends('core::layouts.master')

@section('content')
<div style="height: calc(100vh - 100px); display: flex; flex-direction: column; overflow: hidden;">
    <div style="flex: 1; display: grid; grid-template-columns: 350px 1fr; background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden; margin: 1rem;">
        
        <!-- Sidebar (Conversation List) -->
        <div style="border-right: 1px solid #e0e0e0; display: flex; flex-direction: column; background: #f8f9fa;">
            <div style="padding: 1.5rem; border-bottom: 1px solid #e0e0e0; background: white;">
                <h1 style="font-size: 1.5rem; font-weight: bold; color: var(--primary-color); margin: 0;">Messages</h1>
                <div style="margin-top: 1rem; position: relative;">
                    <input type="text" placeholder="Search conversations..." style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border: 1px solid #e0e0e0; border-radius: 8px; background: #f8f9fa; outline: none;">
                    <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #999;">🔍</span>
                </div>
            </div>
            
            <div style="flex: 1; overflow-y: auto;">
                <!-- General Channel (Always First) -->
                <a href="#" onclick="loadGeneralChat(); return false;" class="conversation-item general-channel" style="display: block; padding: 1rem; border-bottom: 2px solid var(--secondary-color); text-decoration: none; transition: all 0.2s; background: linear-gradient(135deg, rgba(91, 128, 64, 0.1), rgba(91, 128, 64, 0.05));">
                    <div style="display: flex; gap: 1rem;">
                        <div style="width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, var(--secondary-color), #4a6633); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.25rem;">
                            #
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.25rem;">
                                <h3 style="font-weight: 700; color: var(--secondary-color); margin: 0;">General Channel</h3>
                            </div>
                            <p style="font-size: 0.875rem; color: #666; margin: 0;">Team-wide announcements and discussions</p>
                        </div>
                    </div>
                </a>

                <div style="padding: 0.5rem 1rem; background: #f0f0f0; font-size: 0.75rem; font-weight: 600; color: #999; text-transform: uppercase;">Direct Messages</div>

                @forelse($conversations as $conversation)
                    <a href="#" onclick="loadChat({{ $conversation['staff']->id }}); return false;" class="conversation-item" data-id="{{ $conversation['staff']->id }}" style="display: block; padding: 1rem; border-bottom: 1px solid #f0f0f0; text-decoration: none; transition: all 0.2s; background: white;">
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
                                    <div style="display: flex; flex-direction: column;">
                                        <span style="font-size: 0.75rem; color: var(--secondary-color); font-weight: 600; margin-bottom: 0.125rem;">{{ ucfirst($conversation['staff']->role ?? 'Staff') }}</span>
                                        <p style="font-size: 0.875rem; color: #666; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 180px;">
                                            {{ $conversation['last_message'] ? Str::limit($conversation['last_message']->message, 30) : 'Start a conversation' }}
                                        </p>
                                    </div>
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
                        <p>No direct conversations yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Main Chat Area -->
        <div id="chatMainArea" class="chat-main" style="display: flex; flex-direction: column; background: white; height: 100%;">
            <!-- General Channel Messages (Default View) -->
            <div id="generalChatArea" style="display: flex; flex-direction: column; height: 100%;">
                <!-- Header -->
                <div style="padding: 1.5rem; border-bottom: 1px solid #e0e0e0; background: white;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, var(--secondary-color), #4a6633); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.25rem;">
                            #
                        </div>
                        <div>
                            <h2 style="font-size: 1.25rem; font-weight: bold; color: var(--primary-color); margin: 0;">General Channel</h2>
                            <p style="font-size: 0.875rem; color: #666; margin: 0;">Team-wide communication</p>
                        </div>
                    </div>
                </div>

                <!-- Messages -->
                <div id="generalMessagesContainer" style="flex: 1; overflow-y: auto; padding: 1.5rem; background: #f8f9fa;">
                    @forelse($generalMessages as $msg)
                        <div class="message-bubble" data-id="{{ $msg->id }}" style="margin-bottom: 1rem; display: flex; gap: 1rem; {{ $msg->sender_id == Auth::guard('staff')->id() ? 'flex-direction: row-reverse;' : '' }}">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, var(--primary-color), #3a4b7c); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">
                                {{ substr($msg->sender->name, 0, 1) }}
                            </div>
                            <div style="max-width: 70%;">
                                <div style="background: {{ $msg->sender_id == Auth::guard('staff')->id() ? 'linear-gradient(135deg, var(--secondary-color), #4a6633)' : 'white' }}; color: {{ $msg->sender_id == Auth::guard('staff')->id() ? 'white' : '#333' }}; padding: 0.75rem 1rem; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                    <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem; opacity: 0.9;">{{ $msg->sender->name }}</div>
                                    <div>{{ $msg->message }}</div>
                                </div>
                                <div style="font-size: 0.75rem; color: #999; margin-top: 0.25rem; {{ $msg->sender_id == Auth::guard('staff')->id() ? 'text-align: right;' : '' }}">
                                    {{ $msg->created_at->format('H:i') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 3rem; color: #999;">
                            <div style="font-size: 3rem; margin-bottom: 1rem;">💬</div>
                            <p>No messages yet. Start the conversation!</p>
                        </div>
                    @endforelse
                </div>

                <!-- Message Input -->
                <div style="padding: 1.5rem; border-top: 1px solid #e0e0e0; background: white;">
                    <form action="{{ route('staff.chat.general.send') }}" method="POST" id="generalMessageForm">
                        @csrf
                        <div style="display: flex; gap: 1rem;">
                            <input type="text" name="message" id="generalMessageInput" placeholder="Type a message to the team..." required style="flex: 1; padding: 0.75rem 1rem; border: 2px solid #e0e0e0; border-radius: 8px; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='var(--secondary-color)'" onblur="this.style.borderColor='#e0e0e0'">
                            <button type="submit" style="padding: 0.75rem 1.5rem; background: var(--secondary-color); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#4a6633'" onmouseout="this.style.background='var(--secondary-color)'">
                                Send
                            </button>
                        </div>
                    </form>
                </div>
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
            width: 100%;
        }
        .chat-main {
            display: none;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 20;
        }
        .mobile-back {
            display: block !important;
        }
    }
</style>

<script>
    let generalPollingInterval;
    let lastGeneralMessageId = {{ $generalMessages->last()->id ?? 0 }};

    function loadGeneralChat() {
        // Update active state in sidebar
        document.querySelectorAll('.conversation-item').forEach(item => {
            item.style.background = 'white';
            item.style.borderLeft = '4px solid transparent';
        });
        const generalItem = document.querySelector('.general-channel');
        if (generalItem) {
            generalItem.style.background = 'linear-gradient(135deg, rgba(91, 128, 64, 0.1), rgba(91, 128, 64, 0.05))';
            generalItem.style.borderLeft = '4px solid var(--secondary-color)';
        }

        // Show general chat area
        document.getElementById('generalChatArea').style.display = 'flex';
        
        // Scroll to bottom
        const container = document.getElementById('generalMessagesContainer');
        container.scrollTop = container.scrollHeight;

        // Start polling for new messages
        startGeneralPolling();
    }

    function loadChat(staffId) {
        // Stop general polling when switching to DM
        stopGeneralPolling();

        // Update active state in sidebar
        document.querySelectorAll('.conversation-item').forEach(item => {
            item.style.background = 'white';
            item.style.borderLeft = '4px solid transparent';
        });
        const activeItem = document.querySelector(`.conversation-item[data-id="${staffId}"]`);
        if (activeItem) {
            activeItem.style.background = '#e3f2fd';
            activeItem.style.borderLeft = '4px solid var(--primary-color)';
        }

        // Hide general chat
        document.getElementById('generalChatArea').style.display = 'none';

        // Show loading state
        const mainArea = document.getElementById('chatMainArea');
        mainArea.innerHTML = '<div style="flex: 1; display: flex; align-items: center; justify-content: center;"><div style="width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid var(--primary-color); border-radius: 50%; animation: spin 1s linear infinite;"></div></div>';

        // Handle mobile view
        if (window.innerWidth <= 768) {
            document.querySelector('.chat-sidebar').style.display = 'none';
            mainArea.style.display = 'flex';
        }

        // Fetch chat view
        fetch(`/staff/chat/${staffId}/view`)
            .then(response => response.text())
            .then(html => {
                mainArea.innerHTML = html;
                
                // Execute scripts in the returned HTML
                const scripts = mainArea.querySelectorAll('script');
                scripts.forEach(script => {
                    const newScript = document.createElement('script');
                    Array.from(script.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                    newScript.appendChild(document.createTextNode(script.innerHTML));
                    script.parentNode.replaceChild(newScript, script);
                });
            });
    }

    function startGeneralPolling() {
        if (generalPollingInterval) return;
        
        generalPollingInterval = setInterval(() => {
            fetch(`/staff/chat/general/messages?after_id=${lastGeneralMessageId}`)
                .then(response => response.json())
                .then(messages => {
                    if (messages.length > 0) {
                        const container = document.getElementById('generalMessagesContainer');
                        const currentStaffId = {{ Auth::guard('staff')->id() }};
                        
                        messages.forEach(msg => {
                            const messageHtml = `
                                <div class="message-bubble" data-id="${msg.id}" style="margin-bottom: 1rem; display: flex; gap: 1rem; ${msg.sender_id == currentStaffId ? 'flex-direction: row-reverse;' : ''} opacity: 0; transition: opacity 0.3s;">
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, var(--primary-color), #3a4b7c); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">
                                        ${msg.sender.name.charAt(0)}
                                    </div>
                                    <div style="max-width: 70%;">
                                        <div style="background: ${msg.sender_id == currentStaffId ? 'linear-gradient(135deg, var(--secondary-color), #4a6633)' : 'white'}; color: ${msg.sender_id == currentStaffId ? 'white' : '#333'}; padding: 0.75rem 1rem; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                            <div style="font-weight: 600; font-size: 0.875rem; margin-bottom: 0.25rem; opacity: 0.9;">${msg.sender.name}</div>
                                            <div>${msg.message}</div>
                                        </div>
                                        <div style="font-size: 0.75rem; color: #999; margin-top: 0.25rem; ${msg.sender_id == currentStaffId ? 'text-align: right;' : ''}">
                                            ${new Date(msg.created_at).toLocaleTimeString('en-US', {hour: '2-digit', minute: '2-digit'})}
                                        </div>
                                    </div>
                                </div>
                            `;
                            container.insertAdjacentHTML('beforeend', messageHtml);
                            lastGeneralMessageId = msg.id;
                        });

                        // Fade in new messages
                        setTimeout(() => {
                            document.querySelectorAll('.message-bubble[style*="opacity: 0"]').forEach(el => {
                                el.style.opacity = '1';
                            });
                        }, 50);

                        // Scroll to bottom
                        container.scrollTop = container.scrollHeight;
                    }
                });
        }, 1000); // Poll every second
    }

    function stopGeneralPolling() {
        if (generalPollingInterval) {
            clearInterval(generalPollingInterval);
            generalPollingInterval = null;
        }
    }

    // Handle form submission with AJAX
    document.getElementById('generalMessageForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('generalMessageInput');
        const message = input.value.trim();
        
        if (!message) return;

        fetch(this.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ message: message })
        }).then(() => {
            input.value = '';
            // Polling will pick up the new message
        });
    });

    // Start polling on page load (general channel is default)
    document.addEventListener('DOMContentLoaded', function() {
        startGeneralPolling();
        
        // Scroll to bottom on load
        const container = document.getElementById('generalMessagesContainer');
        container.scrollTop = container.scrollHeight;
    });
</script>

<style>
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endsection
