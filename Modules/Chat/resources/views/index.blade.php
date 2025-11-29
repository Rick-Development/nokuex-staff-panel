@extends('core::layouts.master')

@section('content')
<div class="chat-wrapper">
    <div id="chatContainer" class="chat-container">
        
        <!-- Sidebar (Conversation List) -->
        <div id="chatSidebar" class="chat-sidebar">
            <div class="sidebar-header">
                <h1>Messages</h1>
                <div class="search-box">
                    <input type="text" placeholder="Search team..." id="userSearch">
                    <span class="search-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </span>
                </div>
            </div>
            
            <div class="conversations-list">
                <!-- General Channel -->
                <a href="#" onclick="loadGeneralChat(); return false;" class="conversation-item general-channel active">
                    <div class="avatar general-avatar">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="9" x2="20" y2="9"></line><line x1="4" y1="15" x2="20" y2="15"></line><line x1="10" y1="3" x2="8" y2="21"></line><line x1="16" y1="3" x2="14" y2="21"></line></svg>
                    </div>
                    <div class="conversation-info">
                        <div class="conversation-top">
                            <h3>General Channel</h3>
                        </div>
                        <p>Team-wide announcements</p>
                    </div>
                </a>

                <div class="section-label">Direct Messages</div>

                @forelse($conversations as $conversation)
                    <a href="#" onclick="loadChat({{ $conversation['staff']->id }}); return false;" class="conversation-item" data-id="{{ $conversation['staff']->id }}">
                        <div class="avatar-wrapper">
                            <div class="avatar" style="background: {{ $conversation['staff']->is_active ? 'var(--primary-color)' : '#95a5a6' }}">
                                {{ substr($conversation['staff']->name, 0, 1) }}
                            </div>
                            @if($conversation['staff']->is_active)
                                <div class="status-dot"></div>
                            @endif
                        </div>
                        <div class="conversation-info">
                            <div class="conversation-top">
                                <h3>{{ $conversation['staff']->name }}</h3>
                                @if($conversation['last_message'])
                                    <span class="time">{{ $conversation['last_message']->created_at->format('H:i') }}</span>
                                @endif
                            </div>
                            <div class="conversation-bottom">
                                <p>{{ $conversation['last_message'] ? Str::limit($conversation['last_message']->message, 25) : ucfirst($conversation['staff']->role) }}</p>
                                <span class="unread-badge" style="display: {{ $conversation['unread_count'] > 0 ? 'flex' : 'none' }}">
                                    {{ $conversation['unread_count'] }}
                                </span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="empty-state">
                        <p>No staff members found.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Main Chat Area -->
        <div id="chatMainArea" class="chat-main mobile-hidden">
            <!-- General Channel View -->
            <div id="generalChatArea" class="channel-view">
                <div class="chat-header">
                    <button class="back-button" onclick="showMobileList()">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </button>
                    <div class="header-info">
                        <div class="avatar general-avatar small">#</div>
                        <div>
                            <h2>General Channel</h2>
                            <p>Team-wide communication</p>
                        </div>
                    </div>
                </div>

                <div id="generalMessagesContainer" class="messages-container">
                    @forelse($generalMessages as $msg)
                        <div class="message-group {{ $msg->sender_id == Auth::guard('staff')->id() ? 'sent' : 'received' }}" data-id="{{ $msg->id }}">
                            @if($msg->sender_id != Auth::guard('staff')->id())
                                <div class="message-avatar" title="{{ $msg->sender->name }}">
                                    {{ substr($msg->sender->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="message-content">
                                @if($msg->sender_id != Auth::guard('staff')->id())
                                    <div class="sender-name">{{ $msg->sender->name }}</div>
                                @endif
                                <div class="bubble">
                                    {{ $msg->message }}
                                </div>
                                <div class="message-time">
                                    {{ $msg->created_at->format('H:i') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-chat">
                            <div class="icon">👋</div>
                            <h3>Welcome to General Chat</h3>
                            <p>This is the start of the team conversation.</p>
                        </div>
                    @endforelse
                </div>

                <div class="input-area">
                    <form action="{{ route('staff.chat.general.send') }}" method="POST" id="generalMessageForm">
                        @csrf
                        <div class="input-wrapper">
                            <input type="text" name="message" id="generalMessageInput" placeholder="Message #general..." required autocomplete="off">
                            <button type="submit">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Chat Styles */
    .chat-wrapper {
        width: 100%;
        height: 100vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        margin: -1rem; /* Counteract any parent padding */
        padding: 0;
    }

    .chat-container {
        flex: 1;
        display: grid;
        grid-template-columns: 350px 1fr;
        background: white;
        overflow: hidden;
        min-height: 0;
    }

    /* Sidebar */
    .chat-sidebar {
        border-right: 1px solid #f5f5f5;
        display: flex;
        flex-direction: column;
        background: #ffffff;
        overflow: hidden;
    }

    .sidebar-header {
        padding: 1.5rem;
        border-bottom: 1px solid #f5f5f5;
        flex-shrink: 0;
    }

    .sidebar-header h1 {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--primary-color);
        margin-bottom: 1rem;
        letter-spacing: -0.5px;
    }

    .search-box {
        position: relative;
    }

    .search-box input {
        width: 100%;
        padding: 0.875rem 1rem 0.875rem 2.75rem;
        border: 1px solid #f0f0f0;
        border-radius: 14px;
        background: #f8f9fa;
        font-size: 0.95rem;
        transition: all 0.2s;
    }

    .search-box input:focus {
        background: white;
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 4px rgba(41, 45, 80, 0.05);
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #a0aec0;
    }

    .conversations-list {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        padding: 1rem;
        min-height: 0;
        -webkit-overflow-scrolling: touch;
    }

    .section-label {
        padding: 1.5rem 0.75rem 0.75rem;
        font-size: 0.7rem;
        font-weight: 700;
        color: #cbd5e0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .conversation-item {
        display: flex;
        gap: 1rem;
        padding: 1rem;
        border-radius: 16px;
        text-decoration: none;
        color: inherit;
        transition: all 0.2s;
        margin-bottom: 0.25rem;
        border: 1px solid transparent;
    }

    .conversation-item:hover {
        background: #f8f9fa;
    }

    .conversation-item.active {
        background: #eef2ff;
        border-color: rgba(41, 45, 80, 0.05);
    }

    .avatar-wrapper {
        position: relative;
    }

    .avatar {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1.2rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        flex-shrink: 0;
    }

    .general-avatar {
        background: linear-gradient(135deg, var(--secondary-color), #4a6633);
        border-radius: 50%;
    }

    .status-dot {
        position: absolute;
        bottom: -2px;
        right: -2px;
        width: 16px;
        height: 16px;
        background: #2ecc71;
        border: 3px solid white;
        border-radius: 50%;
    }

    .conversation-info {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .conversation-top {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        margin-bottom: 0.35rem;
    }

    .conversation-top h3 {
        font-weight: 700;
        font-size: 1rem;
        color: var(--primary-color);
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .time {
        font-size: 0.75rem;
        color: #a0aec0;
        font-weight: 500;
        flex-shrink: 0;
    }

    .conversation-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .conversation-bottom p {
        font-size: 0.9rem;
        color: #718096;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 180px;
    }

    .unread-badge {
        background: var(--accent-color);
        color: white;
        border-radius: 12px;
        padding: 0.2rem 0.6rem;
        font-size: 0.75rem;
        font-weight: 700;
        min-width: 24px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(222, 129, 29, 0.25);
        flex-shrink: 0;
    }

    /* Main Chat Area */
    .chat-main {
        display: flex;
        flex-direction: column;
        background: #ffffff;
        height: 100%;
        overflow: hidden;
        min-height: 0;
    }

    .channel-view {
        display: flex;
        flex-direction: column;
        flex: 1;
        min-height: 0;
        height: 100%;
    }

    .chat-header {
        padding: 1.25rem 2rem;
        border-bottom: 1px solid #f5f5f5;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        flex-shrink: 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .back-button {
        display: none;
        background: none;
        border: none;
        padding: 0.5rem;
        margin-left: -0.5rem;
        cursor: pointer;
        color: var(--primary-color);
    }

    .header-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .header-info h2 {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--primary-color);
        margin: 0;
    }

    .header-info p {
        font-size: 0.85rem;
        color: #718096;
        margin: 0;
    }

    .avatar.small {
        width: 44px;
        height: 44px;
        font-size: 1.1rem;
        box-shadow: none;
    }

    .messages-container {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        padding: 2rem;
        background: #fcfcfc;
        background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
        background-size: 32px 32px;
        min-height: 0;
        -webkit-overflow-scrolling: touch;
    }

    .message-group {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        opacity: 0;
        animation: fadeIn 0.3s forwards;
    }

    .message-group.sent {
        flex-direction: row-reverse;
    }

    .message-avatar {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        background: var(--primary-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.9rem;
        flex-shrink: 0;
        margin-top: 4px;
        box-shadow: 0 2px 6px rgba(41, 45, 80, 0.1);
    }

    .message-content {
        max-width: 65%;
        display: flex;
        flex-direction: column;
    }

    .message-group.sent .message-content {
        align-items: flex-end;
    }

    .sender-name {
        font-size: 0.8rem;
        color: #718096;
        margin-bottom: 0.35rem;
        font-weight: 600;
        margin-left: 0.5rem;
    }

    .bubble {
        padding: 1rem 1.5rem;
        border-radius: 20px;
        font-size: 1rem;
        line-height: 1.6;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        position: relative;
        word-wrap: break-word;
    }

    .message-group.sent .bubble {
        background: linear-gradient(135deg, var(--primary-color), #3a4b7c);
        color: white;
        border-bottom-right-radius: 4px;
    }

    .message-group.received .bubble {
        background: white;
        color: #2d3748;
        border-bottom-left-radius: 4px;
        border: 1px solid rgba(0,0,0,0.02);
    }

    .message-time {
        font-size: 0.75rem;
        color: #a0aec0;
        margin-top: 0.5rem;
        margin-left: 0.5rem;
        font-weight: 500;
    }

    .message-group.sent .message-time {
        margin-right: 0.5rem;
        margin-left: 0;
    }

    .input-area {
        padding: 1.5rem 2rem;
        background: white;
        border-top: 1px solid #f5f5f5;
        flex-shrink: 0;
    }

    .input-wrapper {
        display: flex;
        gap: 1rem;
        background: #f8f9fa;
        padding: 0.75rem;
        border-radius: 20px;
        border: 1px solid #eef0f2;
        transition: all 0.2s;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }

    .input-wrapper:focus-within {
        background: white;
        border-color: var(--primary-color);
        box-shadow: 0 8px 20px rgba(41, 45, 80, 0.08);
    }

    .input-wrapper input {
        flex: 1;
        border: none;
        background: transparent;
        padding: 0.5rem 1rem;
        font-size: 1rem;
        outline: none;
        color: var(--primary-color);
    }

    .input-wrapper button {
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 14px;
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(41, 45, 80, 0.15);
        flex-shrink: 0;
    }

    .input-wrapper button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(41, 45, 80, 0.2);
    }

    .empty-chat {
        text-align: center;
        padding: 4rem 2rem;
        color: #a0aec0;
    }

    .empty-chat .icon {
        font-size: 4rem;
        margin-bottom: 1.5rem;
        opacity: 0.5;
    }

    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #a0aec0;
    }

    @keyframes fadeIn {
        to { opacity: 1; }
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .chat-wrapper {
            height: 100dvh;
            margin: 0;
        }

        .chat-container {
            grid-template-columns: 1fr;
            height: 100%;
        }

        .chat-sidebar {
            width: 100%;
            height: 100%;
            border-right: none;
        }

        .chat-main {
            position: fixed;
            top: 60px;
            left: 0;
            width: 100%;
            height: calc(100dvh - 60px);
            z-index: 30;
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
        }

        .chat-main.active {
            transform: translateX(0);
        }

        .back-button {
            display: block;
        }

        .chat-header {
            padding: 1rem;
        }

        .messages-container {
            padding: 1rem;
        }

        .input-area {
            padding: 1rem;
        }

        .message-content {
            max-width: 85%;
        }
    }
</style>

<script>
    let generalPollingInterval;
    let sidebarPollingInterval;
    let lastGeneralMessageId = {{ $generalMessages->last()->id ?? 0 }};

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        startGeneralPolling();
        startSidebarPolling();
        scrollToBottom();
        setupSearch();
        setupGeneralForm();
    });

    function scrollToBottom() {
        const container = document.getElementById('generalMessagesContainer');
        if (container) container.scrollTop = container.scrollHeight;
    }

    // Mobile Navigation Logic
    function showMobileChat() {
        if (window.innerWidth <= 768) {
            document.getElementById('chatMainArea').classList.add('active');
        }
    }

    function showMobileList() {
        if (window.innerWidth <= 768) {
            document.getElementById('chatMainArea').classList.remove('active');
            // Optional: Clear active selection
            // document.querySelectorAll('.conversation-item').forEach(el => el.classList.remove('active'));
        }
    }

    function loadGeneralChat() {
        // Update UI
        document.querySelectorAll('.conversation-item').forEach(el => el.classList.remove('active'));
        document.querySelector('.general-channel').classList.add('active');
        
        document.getElementById('generalChatArea').style.display = 'flex';
        document.getElementById('chatMainArea').innerHTML = ''; // Clear DM view
        document.getElementById('chatMainArea').appendChild(document.getElementById('generalChatArea'));
        
        // Mobile Transition
        showMobileChat();

        scrollToBottom();
        startGeneralPolling();
    }

    function loadChat(staffId) {
        stopGeneralPolling();

        // Update UI
        document.querySelectorAll('.conversation-item').forEach(el => el.classList.remove('active'));
        document.querySelector(`.conversation-item[data-id="${staffId}"]`).classList.add('active');

        // Show loading
        const mainArea = document.getElementById('chatMainArea');
        const generalArea = document.getElementById('generalChatArea');
        generalArea.style.display = 'none'; // Hide general, don't remove
        
        // If DM view already exists, remove it
        const existingDm = mainArea.querySelector('.dm-view');
        if (existingDm) existingDm.remove();

        mainArea.insertAdjacentHTML('beforeend', '<div class="loading-spinner" style="flex: 1; display: flex; align-items: center; justify-content: center;"><div style="width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid var(--primary-color); border-radius: 50%; animation: spin 1s linear infinite;"></div></div>');

        // Mobile Transition
        showMobileChat();

        fetch(`/staff/chat/${staffId}/view`)
            .then(response => response.text())
            .then(html => {
                const spinner = mainArea.querySelector('.loading-spinner');
                if (spinner) spinner.remove();
                
                // Create container for DM
                const dmContainer = document.createElement('div');
                dmContainer.className = 'dm-view channel-view';
                dmContainer.innerHTML = html;
                
                // Add Back Button to DM Header if missing
                if (!dmContainer.querySelector('.back-button')) {
                    const header = dmContainer.querySelector('.chat-header');
                    if (header) {
                        const backBtn = document.createElement('button');
                        backBtn.className = 'back-button';
                        backBtn.onclick = showMobileList;
                        backBtn.innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>';
                        header.insertBefore(backBtn, header.firstChild);
                    }
                }

                mainArea.appendChild(dmContainer);
                
                // Execute scripts
                dmContainer.querySelectorAll('script').forEach(script => {
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
                            if (msg.id > lastGeneralMessageId) {
                                const isSent = msg.sender_id == currentStaffId;
                                const html = `
                                    <div class="message-group ${isSent ? 'sent' : 'received'}" data-id="${msg.id}">
                                        ${!isSent ? `<div class="message-avatar">${msg.sender.name.charAt(0)}</div>` : ''}
                                        <div class="message-content">
                                            ${!isSent ? `<div class="sender-name">${msg.sender.name}</div>` : ''}
                                            <div class="bubble">${msg.message}</div>
                                            <div class="message-time">${new Date(msg.created_at).toLocaleTimeString('en-US', {hour:'2-digit', minute:'2-digit', hour12:false})}</div>
                                        </div>
                                    </div>
                                `;
                                container.insertAdjacentHTML('beforeend', html);
                                lastGeneralMessageId = msg.id;
                            }
                        });
                        
                        container.scrollTop = container.scrollHeight;
                    }
                });
        }, 1000);
    }

    function stopGeneralPolling() {
        if (generalPollingInterval) {
            clearInterval(generalPollingInterval);
            generalPollingInterval = null;
        }
    }

    function startSidebarPolling() {
        if (sidebarPollingInterval) return;
        
        sidebarPollingInterval = setInterval(() => {
            fetch('{{ route("staff.chat.check_new") }}')
                .then(response => response.json())
                .then(data => {
                    // Update unread badges
                    Object.keys(data.unread_counts).forEach(senderId => {
                        const badge = document.querySelector(`.conversation-item[data-id="${senderId}"] .unread-badge`);
                        if (badge) {
                            badge.textContent = data.unread_counts[senderId];
                            badge.style.display = 'flex';
                        }
                    });
                });
        }, 3000); // Check every 3 seconds
    }

    function setupSearch() {
        const input = document.getElementById('userSearch');
        input.addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('.conversation-item:not(.general-channel)').forEach(item => {
                const name = item.querySelector('h3').textContent.toLowerCase();
                item.style.display = name.includes(term) ? 'flex' : 'none';
            });
        });
    }

    function setupGeneralForm() {
        const form = document.getElementById('generalMessageForm');
        if (form) {
            form.addEventListener('submit', function(e) {
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
                });
            });
        }
    }
</script>

<style>
@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
</style>
@endsection
