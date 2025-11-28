@extends('core::layouts.app')

@section('title', 'Edit Customer')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="margin: 0;">Edit Customer</h2>
        <a href="{{ route('customercare.crm') }}" class="btn btn-warning">
            <i>←</i> Back to CRM
        </a>
    </div>

    <form id="edit-customer-form">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label for="name" class="form-label">Full Name *</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $customer->name }}" required>
            </div>
            
            <div class="form-group">
                <label for="email" class="form-label">Email Address *</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ $customer->email }}" required>
            </div>
            
            <div class="form-group">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ $customer->phone }}">
            </div>
            
            <div class="form-group">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="active" {{ $customer->status === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $customer->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="suspended" {{ $customer->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label for="address" class="form-label">Address</label>
            <textarea name="address" id="address" class="form-control" rows="3">{{ $customer->address }}</textarea>
        </div>
        
        <div style="display: flex; gap: 0.5rem; margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary">
                <i>💾</i> Update Customer
            </button>
            <a href="{{ route('customercare.crm') }}" class="btn btn-warning">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
document.getElementById('edit-customer-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const btn = this.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<i>⏳</i> Updating...';
    btn.disabled = true;
    
    fetch('{{ route("customercare.crm.update", $customer->id) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect;
        } else {
            alert('Error updating customer: ' + (data.message || 'Unknown error'));
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    })
    .catch(error => {
        alert('Error updating customer. Please try again.');
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
});
</script>
@endsection