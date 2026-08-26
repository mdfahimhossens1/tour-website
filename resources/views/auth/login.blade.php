<x-guest-layout>

<style>
    .auth-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        font-family: 'Inter', 'Segoe UI', sans-serif;
    }

    .auth-card {
        background: #ffffff;
        width: 100%;
        max-width: 420px;
        padding: 40px 36px;
        border-radius: 18px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.15);
    }

    .panel-head {
        text-align: center;
        margin-bottom: 28px;
    }

    .panel-head h2 {
        font-size: 24px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 6px;
    }

    .panel-head p {
        font-size: 14px;
        color: #64748b;
        margin: 0;
    }

    .field {
        margin-bottom: 18px;
    }

    .field label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 6px;
    }

    .input-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 11px 14px;
        background: #f8fafc;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .input-wrap:focus-within {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        background: #ffffff;
    }

    .input-wrap svg {
        flex-shrink: 0;
        color: #94a3b8;
    }

    .input-wrap:focus-within svg {
        color: #2563eb;
    }

    .input-wrap input {
        border: none;
        outline: none;
        background: transparent;
        width: 100%;
        font-size: 14px;
        color: #0f172a;
    }

    .input-wrap input::placeholder {
        color: #94a3b8;
    }

    .input-wrap input.has-error {
        color: #dc2626;
    }

    .field-error {
        color: #dc2626;
        font-size: 12.5px;
        margin-top: 5px;
    }

    .btn-submit {
        width: 100%;
        padding: 13px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff;
        font-size: 15px;
        font-weight: 600;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: transform 0.15s, box-shadow 0.2s, opacity 0.2s;
    }

    .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.35);
    }

    .btn-submit:active {
        transform: translateY(0);
        opacity: 0.9;
    }

    .form-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        font-size: 13px;
    }

    .checkbox-row {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #475569;
        cursor: pointer;
    }

    .form-meta a {
        color: #2563eb;
        text-decoration: none;
        font-weight: 500;
    }

    .form-meta a:hover {
        text-decoration: underline;
    }

    .switch-line {
        text-align: center;
        font-size: 13.5px;
        color: #64748b;
    }

    .switch-line a {
        color: #2563eb;
        font-weight: 600;
        text-decoration: none;
    }

    .switch-line a:hover {
        text-decoration: underline;
    }
</style>

<div class="auth-wrapper">
<div class="auth-card">

    <div class="panel-head">
        <h2>Welcome back</h2>
        <p>Sign in to your admin account to continue.</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="field">
            <label for="email">Email address</label>
            <div class="input-wrap">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6l8 7 8-7M4 6v12h16V6"/></svg>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="you@vromonseba.com"
                    class="{{ $errors->get('email') ? 'has-error' : '' }}"
                    required
                    autofocus
                    autocomplete="username"
                >
            </div>
            @error('email')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="field">
            <label for="password">Password</label>
            <div class="input-wrap">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="••••••••"
                    class="{{ $errors->get('password') ? 'has-error' : '' }}"
                    required
                    autocomplete="current-password"
                >
            </div>
            @error('password')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-meta">
            <label class="checkbox-row">
                <input type="checkbox" name="remember">
                Remember me
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Forgot password?</a>
            @endif
        </div>

        <button type="submit" class="btn-submit">Sign In to Dashboard</button>
    </form>

    <div class="switch-line">
        Don't have an account? <a href="{{ route('register') }}">Sign up</a>
    </div>

</div>
</div>

</x-guest-layout>