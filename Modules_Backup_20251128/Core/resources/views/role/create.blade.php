@extends('core::layouts.app')

@section('content')
<div class="card">
    <h2>Create Role</h2>
    
    <form action="{{ route('core.role.store') }}" method="POST">
        @csrf
        <div style="margin-bottom: 1rem;">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        
        <div style="margin-bottom: 1rem;">
            <label for="description">Description</label>
            <textarea name="description" id="description" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;"></textarea>
        </div>
        
        <div>
            <button type="submit" class="btn btn-primary">Create</button>
            <a href="{{ route('core.role.index') }}" class="btn btn-warning">Cancel</a>
        </div>
    </form>
</div>
@endsection