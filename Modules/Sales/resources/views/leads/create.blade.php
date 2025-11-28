@extends('core::layouts.master')

@section('content')
<div style="padding: 2rem; max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('staff.sales.leads.index') }}" style="color: var(--primary-color); text-decoration: none;">&larr; Back to Leads</a>
        <h1 style="font-size: 2rem; font-weight: bold; color: var(--primary-color); margin: 0.5rem 0 0 0;">Create New Lead</h1>
    </div>

    <div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <form action="{{ route('staff.sales.leads.store') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div style="grid-column: span 2;">
                    <label style="display: block; font-weight: 600; color: var(--primary-color); margin-bottom: 0.5rem;">Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px; outline: none;" onfocus="this.style.borderColor='var(--primary-color)'" onblur="this.style.borderColor='#e0e0e0'">
                    @error('name')
                        <span style="color: #e74c3c; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label style="display: block; font-weight: 600; color: var(--primary-color); margin-bottom: 0.5rem;">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px; outline: none;" onfocus="this.style.borderColor='var(--primary-color)'" onblur="this.style.borderColor='#e0e0e0'">
                    @error('email')
                        <span style="color: #e74c3c; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label style="display: block; font-weight: 600; color: var(--primary-color); margin-bottom: 0.5rem;">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px; outline: none;" onfocus="this.style.borderColor='var(--primary-color)'" onblur="this.style.borderColor='#e0e0e0'">
                    @error('phone')
                        <span style="color: #e74c3c; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label style="display: block; font-weight: 600; color: var(--primary-color); margin-bottom: 0.5rem;">Source *</label>
                    <select name="source" required style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
                        <option value="">Select Source</option>
                        <option value="website" {{ old('source') == 'website' ? 'selected' : '' }}>Website</option>
                        <option value="referral" {{ old('source') == 'referral' ? 'selected' : '' }}>Referral</option>
                        <option value="social_media" {{ old('source') == 'social_media' ? 'selected' : '' }}>Social Media</option>
                        <option value="direct" {{ old('source') == 'direct' ? 'selected' : '' }}>Direct</option>
                        <option value="other" {{ old('source') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('source')
                        <span style="color: #e74c3c; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label style="display: block; font-weight: 600; color: var(--primary-color); margin-bottom: 0.5rem;">Assign To</label>
                    <select name="assigned_to" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
                        <option value="">Unassigned</option>
                        @foreach($staff as $member)
                            <option value="{{ $member->id }}" {{ old('assigned_to') == $member->id ? 'selected' : '' }}>{{ $member->name }}</option>
                        @endforeach
                    </select>
                    @error('assigned_to')
                        <span style="color: #e74c3c; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="grid-column: span 2;">
                    <label style="display: block; font-weight: 600; color: var(--primary-color); margin-bottom: 0.5rem;">Notes</label>
                    <textarea name="notes" rows="4" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px; resize: vertical; outline: none;" onfocus="this.style.borderColor='var(--primary-color)'" onblur="this.style.borderColor='#e0e0e0'">{{ old('notes') }}</textarea>
                    @error('notes')
                        <span style="color: #e74c3c; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div style="display: flex; gap: 1rem; justify-content: end;">
                <a href="{{ route('staff.sales.leads.index') }}" style="padding: 0.75rem 1.5rem; background: #eee; color: #666; text-decoration: none; border-radius: 8px; font-weight: 600;">Cancel</a>
                <button type="submit" style="padding: 0.75rem 1.5rem; background: var(--secondary-color); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">Create Lead</button>
            </div>
        </form>
    </div>
</div>
@endsection
