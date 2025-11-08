@extends('core::layouts.app')

@section('title', 'Dispute Resolution')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="margin: 0;">Dispute Resolution</h2>
        <button class="btn btn-primary" disabled>
            <i>➕</i> Create Dispute (Coming Soon)
        </button>
    </div>

    <div class="card" style="margin-bottom: 1.5rem;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div style="text-align: center;">
                <div style="font-size: 2rem; font-weight: bold; color: var(--primary-color);">0</div>
                <div style="color: #666;">Open Disputes</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 2rem; font-weight: bold; color: var(--secondary-color);">0</div>
                <div style="color: #666;">In Progress</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 2rem; font-weight: bold; color: var(--accent-color);">0</div>
                <div style="color: #666;">Resolved</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 2rem; font-weight: bold; color: #17a2b8;">0</div>
                <div style="color: #666;">Total Disputes</div>
            </div>
        </div>
    </div>

    <div style="text-align: center; padding: 3rem;">
        <div style="font-size: 4rem; margin-bottom: 1rem;">⚖️</div>
        <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Dispute Resolution System</h3>
        <p style="color: #666; margin-bottom: 2rem; max-width: 500px; margin-left: auto; margin-right: auto;">
            The dispute resolution system is currently under development. This module will allow you to manage customer disputes, track resolution progress, and maintain audit trails for all dispute-related activities.
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <div class="card" style="max-width: 300px;">
                <h4>🕒 Coming Soon</h4>
                <p>Dispute creation and assignment</p>
            </div>
            <div class="card" style="max-width: 300px;">
                <h4>📋 Planned Features</h4>
                <p>Dispute tracking and resolution workflow</p>
            </div>
            <div class="card" style="max-width: 300px;">
                <h4>📊 Reporting</h4>
                <p>Dispute analytics and reporting</p>
            </div>
        </div>
    </div>
</div>
@endsection