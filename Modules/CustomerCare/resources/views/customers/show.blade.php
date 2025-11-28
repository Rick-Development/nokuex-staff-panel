@extends('core::layouts.master')

@section('content')
<div style="padding: 2rem; max-width: 1600px; margin: 0 auto;">
    <!-- Header -->
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('staff.customers.index') }}" style="color: var(--primary-color); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 500; margin-bottom: 1rem;">
            &larr; Back to Customer List
        </a>
        <div style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 1.5rem;">
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--primary-color), #3a4b7c); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; font-weight: bold;">
                    {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                </div>
                <div>
                    <h1 style="font-size: 1.75rem; font-weight: bold; color: var(--primary-color); margin: 0 0 0.25rem 0;">{{ $user->first_name }} {{ $user->last_name }}</h1>
                    <div style="display: flex; align-items: center; gap: 1rem; color: #666;">
                        <span>{{ $user->email }}</span>
                        <span>•</span>
                        <span>{{ $user->phone ?? 'No Phone' }}</span>
                    </div>
                </div>
            </div>
            <div style="display: flex; gap: 1rem; align-items: center;">
                <span style="padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; {{ $user->account_status === 'active' ? 'background: #d4edda; color: #155724;' : ($user->account_status === 'frozen' ? 'background: #f8d7da; color: #721c24;' : 'background: #fff3cd; color: #856404;') }}">
                    {{ $user->account_status ?? 'Unknown' }}
                </span>
                @if($user->account_status === 'active')
                    <button onclick="openModal('freezeModal')" style="padding: 0.75rem 1.5rem; background: #dc3545; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        Freeze Account
                    </button>
                @elseif(in_array($user->account_status, ['frozen', 'suspended']))
                    <button onclick="openModal('unfreezeModal')" style="padding: 0.75rem 1.5rem; background: #28a745; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        Activate Account
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <div style="color: #666; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.5rem;">TOTAL VOLUME</div>
            <div style="font-size: 1.75rem; font-weight: bold; color: var(--primary-color);">₦{{ number_format($totalVolume, 2) }}</div>
        </div>
        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <div style="color: #666; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.5rem;">TRANSACTIONS</div>
            <div style="font-size: 1.75rem; font-weight: bold; color: var(--secondary-color);">{{ number_format($totalTransactions) }}</div>
        </div>
        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <div style="color: #666; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.5rem;">ACTIVE TICKETS</div>
            <div style="font-size: 1.75rem; font-weight: bold; color: var(--accent-color);">{{ $activeTickets }}</div>
        </div>
        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <div style="color: #666; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.5rem;">DISPUTES</div>
            <div style="font-size: 1.75rem; font-weight: bold; color: #dc3545;">{{ $disputesCount }}</div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
        <!-- Left Column -->
        <div style="display: flex; flex-direction: column; gap: 2rem;">
            <!-- Profile Details -->
            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1.5rem; border-bottom: 1px solid #eee; padding-bottom: 0.75rem;">Profile Details</h3>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <div style="font-size: 0.75rem; color: #999; text-transform: uppercase; font-weight: 600;">Joined Date</div>
                        <div style="color: var(--primary-color); font-weight: 500;">{{ $user->created_at->format('M d, Y') }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.75rem; color: #999; text-transform: uppercase; font-weight: 600;">Last Login</div>
                        <div style="color: var(--primary-color); font-weight: 500;">{{ $user->last_login ? \Carbon\Carbon::parse($user->last_login)->diffForHumans() : 'Never' }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.75rem; color: #999; text-transform: uppercase; font-weight: 600;">KYC Status</div>
                        <div style="color: var(--secondary-color); font-weight: 600;">Verified</div>
                    </div>
                </div>
            </div>

            <!-- Wallet Balances -->
            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1.5rem; border-bottom: 1px solid #eee; padding-bottom: 0.75rem;">Wallet Balances</h3>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    @forelse($wallets as $wallet)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: #f8f9fa; border-radius: 8px;">
                            <div style="font-weight: 600; color: var(--primary-color);">{{ $wallet->currency }}</div>
                            <div style="font-weight: 700; color: var(--secondary-color);">{{ number_format($wallet->balance, 2) }}</div>
                        </div>
                    @empty
                        <div style="text-align: center; color: #999; font-style: italic;">No wallets found</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div style="display: flex; flex-direction: column; gap: 2rem;">
            <!-- Chart -->
            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1.5rem;">Transaction Volume (Last 6 Months)</h3>
                <canvas id="volumeChart" style="max-height: 300px; width: 100%;"></canvas>
            </div>

            <!-- Recent Transactions -->
            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #eee; padding-bottom: 0.75rem;">
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--primary-color); margin: 0;">Recent Transactions</h3>
                    <a href="#" style="color: var(--secondary-color); text-decoration: none; font-size: 0.875rem; font-weight: 600;">View All</a>
                </div>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    @forelse($recentTransactions as $txn)
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; border: 1px solid #eee; border-radius: 8px; transition: all 0.2s;" onmouseover="this.style.borderColor='var(--primary-color)'" onmouseout="this.style.borderColor='#eee'">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <div style="width: 40px; height: 40px; border-radius: 8px; background: {{ $txn->trx_type === '+' ? '#d4edda' : '#f8d7da' }}; color: {{ $txn->trx_type === '+' ? '#155724' : '#721c24' }}; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                                    {{ $txn->trx_type === '+' ? '↓' : '↑' }}
                                </div>
                                <div>
                                    <div style="font-weight: 600; color: var(--primary-color);">{{ ucfirst(str_replace('_', ' ', $txn->transactional_type)) }}</div>
                                    <div style="font-size: 0.75rem; color: #999;">{{ \Carbon\Carbon::parse($txn->created_at)->format('M d, H:i') }}</div>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-weight: 700; color: {{ $txn->trx_type === '+' ? 'var(--secondary-color)' : 'var(--accent-color)' }};">
                                    {{ $txn->trx_type }}{{ $txn->currency == 'NGN' ? '₦' : '' }}{{ number_format($txn->amount, 2) }} <span style="font-size: 0.75rem; color: #999;">{{ $txn->currency }}</span>
                                </div>
                                <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: {{ $txn->status === 'success' ? '#28a745' : '#dc3545' }};">
                                    {{ $txn->status }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 2rem; color: #999;">No recent transactions</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<div id="freezeModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 2rem; max-width: 400px; width: 90%;">
        <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;">Freeze Account</h3>
        <p style="color: #666; margin-bottom: 1.5rem;">Are you sure? The user will not be able to login or transact.</p>
        <form action="{{ route('staff.customers.update-status', $user->id) }}" method="POST">
            @csrf
            <input type="hidden" name="action" value="freeze">
            <textarea name="reason" placeholder="Reason for freezing..." required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 1.5rem;"></textarea>
            <div style="display: flex; gap: 1rem;">
                <button type="button" onclick="closeModal('freezeModal')" style="flex: 1; padding: 0.75rem; background: #eee; border: none; border-radius: 8px; cursor: pointer;">Cancel</button>
                <button type="submit" style="flex: 1; padding: 0.75rem; background: #dc3545; color: white; border: none; border-radius: 8px; cursor: pointer;">Freeze</button>
            </div>
        </form>
    </div>
</div>

<div id="unfreezeModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 2rem; max-width: 400px; width: 90%;">
        <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;">Activate Account</h3>
        <p style="color: #666; margin-bottom: 1.5rem;">Restore full access to this user?</p>
        <form action="{{ route('staff.customers.update-status', $user->id) }}" method="POST">
            @csrf
            <input type="hidden" name="action" value="unfreeze">
            <textarea name="reason" placeholder="Reason for activation..." required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 1.5rem;"></textarea>
            <div style="display: flex; gap: 1rem;">
                <button type="button" onclick="closeModal('unfreezeModal')" style="flex: 1; padding: 0.75rem; background: #eee; border: none; border-radius: 8px; cursor: pointer;">Cancel</button>
                <button type="submit" style="flex: 1; padding: 0.75rem; background: #28a745; color: white; border: none; border-radius: 8px; cursor: pointer;">Activate</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function openModal(id) { document.getElementById(id).style.display = 'flex'; }
    function closeModal(id) { document.getElementById(id).style.display = 'none'; }

    // Volume Chart
    const ctx = document.getElementById('volumeChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyVolume->pluck('month')) !!},
            datasets: [{
                label: 'Volume',
                data: {!! json_encode($monthlyVolume->pluck('total')) !!},
                borderColor: '#5B8040',
                backgroundColor: 'rgba(91, 128, 64, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { callback: val => '₦' + val.toLocaleString() } }
            }
        }
    });
</script>
@endsection
