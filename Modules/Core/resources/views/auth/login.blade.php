@extends('core::layouts.master')

@section('content')
<style>
    .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary-color), #1a1f38);
    }
    .login-card {
        max-width: 400px;
        width: 100%;
        background: white;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        padding: 2rem;
    }
    .login-input {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #e0e0e0;
        border-radius: 4px;
        margin-bottom: 1rem;
    }
    .login-input:focus {
        outline: none;
        border-color: var(--accent-color);
    }
    .login-btn {
        width: 100%;
        padding: 0.75rem;
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: 4px;
        font-weight: 600;
        cursor: pointer;
    }
    .login-btn:hover {
        background-color: #1a1f38;
    }
</style>

<div class="login-container">
    <div class="login-card">
        <div style="text-align: center; margin-bottom: 2rem;">
            <h2 style="font-size: 1.75rem; font-weight: bold; color: var(--primary-color); margin-bottom: 0.5rem;">Staff Login</h2>
            <p style="color: #666;">Access the Nokuex Staff Panel</p>
        </div>

        @if ($errors->any())
            <div style="background-color: #f8d7da; border: 1px solid var(--accent-color); color: var(--accent-color); padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('core.login.submit') }}">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label for="email" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--primary-color);">Email Address</label>
                <input type="email" name="email" id="email" class="login-input" value="{{ old('email') }}" required autofocus>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="password" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--primary-color);">Password</label>
                <input type="password" name="password" id="password" class="login-input" required>
            </div>

            <div style="display: flex; align-items: center; margin-bottom: 1.5rem;">
                <input type="checkbox" name="remember" id="remember" style="margin-right: 0.5rem;">
                <label for="remember" style="font-size: 0.875rem; color: #666;">Remember Me</label>
            </div>

            <button type="submit" class="login-btn">
                Sign In
            </button>
        </form>

        <!-- Test Credentials -->
        <div style="margin-top: 2rem; padding: 1.5rem; background-color: #f5f5f5; border-radius: 4px; border-left: 4px solid var(--accent-color);">
            <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--primary-color); margin-bottom: 1rem; text-transform: uppercase;">Test Credentials (All use password: password)</h3>
            <div style="font-size: 0.875rem; line-height: 1.8; color: #666;">
                <div style="margin-bottom: 0.5rem;">
                    <strong style="color: var(--primary-color);">Admin:</strong> admin@nokuex.com
                </div>
                <div style="margin-bottom: 0.5rem;">
                    <strong style="color: var(--secondary-color);">Customer Care:</strong> amaka.cc@nokuex.com
                </div>
                <div style="margin-bottom: 0.5rem;">
                    <strong style="color: var(--secondary-color);">Customer Care:</strong> david.cc@nokuex.com
                </div>
                <div style="margin-bottom: 0.5rem;">
                    <strong style="color: var(--accent-color);">Finance:</strong> sarah.finance@nokuex.com
                </div>
                <div>
                    <strong style="color: var(--accent-color);">Compliance:</strong> michael.compliance@nokuex.com
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
