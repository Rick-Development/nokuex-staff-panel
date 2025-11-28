@extends('core::layouts.master')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Dispute Resolution</h1>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form action="{{ route('staff.disputes.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search dispute # or subject..." class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="w-40">
                <select name="status" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Statuses</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="investigating" {{ request('status') == 'investigating' ? 'selected' : '' }}>Investigating</option>
                    <option value="pending_user" {{ request('status') == 'pending_user' ? 'selected' : '' }}>Pending User</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <div class="w-40">
                <select name="priority" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Priorities</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Filter</button>
            @if(request()->hasAny(['search', 'status', 'priority']))
                <a href="{{ route('staff.disputes.index') }}" class="text-gray-600 px-4 py-2 hover:text-gray-800">Clear</a>
            @endif
        </form>
    </div>

    <!-- Disputes Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dispute</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($disputes as $dispute)
                <tr>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">#{{ $dispute->dispute_number }}</div>
                        <div class="text-sm text-gray-500">{{ Str::limit($dispute->subject, 30) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $dispute->user->first_name }} {{ $dispute->user->last_name }}</div>
                        <div class="text-sm text-gray-500">{{ $dispute->user->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ number_format($dispute->disputed_amount, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $dispute->status === 'open' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $dispute->status === 'investigating' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $dispute->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $dispute->status === 'closed' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $dispute->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $dispute->priority === 'urgent' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $dispute->priority === 'high' ? 'bg-orange-100 text-orange-800' : '' }}
                            {{ $dispute->priority === 'medium' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $dispute->priority === 'low' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ ucfirst($dispute->priority) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('staff.disputes.show', $dispute->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No disputes found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $disputes->links() }}
        </div>
    </div>
</div>
@endsection
