@extends('core::layouts.master')

@section('content')
<div style="padding: 2rem; max-width: 1400px; margin: 0 auto;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <a href="{{ route('staff.tickets.index') }}" style="color: var(--primary-color); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 500; margin-bottom: 0.5rem;">
                &larr; Back to Tickets
            </a>
            <h1 style="font-size: 1.75rem; font-weight: bold; color: var(--primary-color); margin: 0;">
                <span style="color: #999; font-weight: 400;">#{{ $ticket->ticket_number }}</span> {{ $ticket->subject }}
            </h1>
            <div style="display: flex; align-items: center; gap: 1rem; margin-top: 0.5rem; color: #666;">
                <span>Created by <a href="{{ route('staff.customers.show', $ticket->user_id) }}" style="color: var(--secondary-color); text-decoration: none; font-weight: 600;">{{ $ticket->user->first_name }} {{ $ticket->user->last_name }}</a></span>
                <span>•</span>
                <span>{{ $ticket->created_at->format('M d, Y H:i') }}</span>
            </div>
        </div>
        
        <div style="display: flex; gap: 1rem; align-items: center;">
            <span style="padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; 
                {{ $ticket->status === 'open' ? 'background: #d4edda; color: #155724;' : 
                   ($ticket->status === 'in_progress' ? 'background: #cce5ff; color: #004085;' : 
                   ($ticket->status === 'pending' ? 'background: #fff3cd; color: #856404;' : 
                   ($ticket->status === 'resolved' ? 'background: #d1ecf1; color: #0c5460;' : 'background: #e2e3e5; color: #383d41;'))) }}">
                {{ str_replace('_', ' ', $ticket->status) }}
            </span>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 350px; gap: 2rem;">
        <!-- Conversation Area -->
        <div style="display: flex; flex-direction: column; gap: 2rem;">
            <!-- Original Message -->
            <div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-left: 4px solid var(--primary-color);">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                    <div style="width: 48px; height: 48px; background: #eee; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #666;">
                        {{ substr($ticket->user->first_name, 0, 1) }}{{ substr($ticket->user->last_name, 0, 1) }}
                    </div>
                    <div>
                        <div style="font-weight: 600; color: var(--primary-color);">{{ $ticket->user->first_name }} {{ $ticket->user->last_name }}</div>
                        <div style="font-size: 0.875rem; color: #999;">Customer</div>
                    </div>
                </div>
                <div style="color: #333; line-height: 1.6; white-space: pre-wrap;">{{ $ticket->description }}</div>
            </div>

            <!-- Replies -->
            @foreach($ticket->replies as $reply)
                <div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05); 
                    {{ $reply->is_internal_note ? 'background: #fffbf0; border: 1px dashed #e0c090;' : '' }}
                    {{ $reply->staff_id ? 'margin-left: 2rem; border-left: 4px solid var(--secondary-color);' : 'margin-right: 2rem; border-left: 4px solid #eee;' }}">
                    
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            @if($reply->staff_id)
                                <div style="width: 40px; height: 40px; background: var(--secondary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">S</div>
                                <div>
                                    <div style="font-weight: 600; color: var(--primary-color);">Staff Member</div>
                                    <div style="font-size: 0.75rem; color: #666;">{{ $reply->is_internal_note ? 'Internal Note 🔒' : 'Staff Reply' }}</div>
                                </div>
                            @else
                                <div style="width: 40px; height: 40px; background: #eee; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #666;">
                                    {{ substr($reply->user->first_name, 0, 1) }}{{ substr($reply->user->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <div style="font-weight: 600; color: var(--primary-color);">{{ $reply->user->first_name }} {{ $reply->user->last_name }}</div>
                                    <div style="font-size: 0.75rem; color: #666;">Customer</div>
                                </div>
                            @endif
                        </div>
                        <div style="font-size: 0.875rem; color: #999;">{{ $reply->created_at->format('M d, H:i') }}</div>
                    </div>
                    <div style="color: #333; line-height: 1.6; white-space: pre-wrap;">{{ $reply->message }}</div>
                </div>
            @endforeach

            <!-- Reply Form -->
            <div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1.5rem;">Post a Reply</h3>
                <form action="{{ route('staff.tickets.reply', $ticket->id) }}" method="POST">
                    @csrf
                    <div style="margin-bottom: 1.5rem;">
                        <textarea name="message" rows="5" placeholder="Type your reply here..." required style="width: 100%; padding: 1rem; border: 2px solid #eee; border-radius: 8px; resize: vertical; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='var(--secondary-color)'" onblur="this.style.borderColor='#eee'"></textarea>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; user-select: none;">
                            <input type="checkbox" name="is_internal_note" value="1" style="width: 18px; height: 18px;">
                            <span style="color: #666; font-weight: 500;">Internal Note (Staff only)</span>
                        </label>
                        <button type="submit" style="padding: 0.75rem 2rem; background: var(--secondary-color); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#4a6633'" onmouseout="this.style.background='var(--secondary-color)'">
                            Send Reply
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- Ticket Info -->
            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1rem; border-bottom: 1px solid #eee; padding-bottom: 0.5rem;">Ticket Details</h3>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <div style="font-size: 0.75rem; color: #999; text-transform: uppercase; font-weight: 600;">Category</div>
                        <div style="font-weight: 500; color: var(--primary-color);">{{ ucfirst($ticket->category) }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.75rem; color: #999; text-transform: uppercase; font-weight: 600;">Priority</div>
                        <div style="font-weight: 500; color: {{ $ticket->priority === 'urgent' ? '#dc3545' : ($ticket->priority === 'high' ? '#fd7e14' : 'var(--primary-color)') }}; text-transform: capitalize;">
                            {{ $ticket->priority }}
                        </div>
                    </div>
                    <div>
                        <div style="font-size: 0.75rem; color: #999; text-transform: uppercase; font-weight: 600;">Assigned To</div>
                        <div style="font-weight: 500; color: var(--primary-color);">{{ $ticket->assignedStaff ? $ticket->assignedStaff->name : 'Unassigned' }}</div>
                    </div>
                </div>
            </div>

            <!-- Update Status -->
            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1rem; border-bottom: 1px solid #eee; padding-bottom: 0.5rem;">Update Status</h3>
                <form action="{{ route('staff.tickets.update', $ticket->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #666; margin-bottom: 0.5rem;">Status</label>
                        <select name="status" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px;">
                            <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #666; margin-bottom: 0.5rem;">Priority</label>
                        <select name="priority" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px;">
                            <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ $ticket->priority == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>
                    <button type="submit" style="width: 100%; padding: 0.75rem; background: var(--primary-color); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        Update Ticket
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
