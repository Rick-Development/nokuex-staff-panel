@extends('core::layouts.app')

@section('title', 'Add New Customer')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="margin: 0;">Add New Customer</h2>
        <a href="{{ route('customercare.crm') }}" class="btn btn-warning">
            <i>←</i> Back to CRM
        </a>
    </div>

    <form id="create-customer-form">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label for="name" class="form-label">Full Name *</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="email" class="form-label">Email Address *</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" name="phone" id="phone" class="form-control">
            </div>
            
            <div class="form-group">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="suspended">Suspended</option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label for="address" class="form-label">Address</label>
            <textarea name="address" id="address" class="form-control" rows="3"></textarea>
        </div>
        
        <div style="display: flex; gap: 0.5rem; margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary">
                <i>➕</i> Create Customer
            </button>
            <a href="{{ route('customercare.crm') }}" class="btn btn-warning">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
document.getElementById('create-customer-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const btn = this.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<i>⏳</i> Creating...';
    btn.disabled = true;
    
    fetch('{{ route("customercare.crm.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect;
        } else {
            alert('Error creating customer: ' + (data.message || 'Unknown error'));
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    })
    .catch(error => {
        alert('Error creating customer. Please try again.');
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
});
</script>
@endsection