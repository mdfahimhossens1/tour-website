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

    .row-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    @media (max-width: 480px) {
        .row-2 {
            grid-template-columns: 1fr;
        }
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
        <h2>Create your account</h2>
        <p>Fill in your details to get started.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Full name -->
        <div class="field">
            <label for="name">Full name</label>
            <div class="input-wrap">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 4-6 8-6s8 2 8 6"/></svg>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Full name"
                    class="{{ $errors->get('name') ? 'has-error' : '' }}"
                    required
                    autofocus
                    autocomplete="name"
                >
            </div>
            @error('name')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

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
                    autocomplete="username"
                >
            </div>
            @error('email')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        <!-- Phone -->
        <div class="field">
            <label for="phone">Phone number</label>
            <div class="input-wrap">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2C9.5 21 3 14.5 3 6a2 2 0 0 1 2-2z"/></svg>
                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    value="{{ old('phone') }}"
                    placeholder="+880 1XXX-XXXXXX"
                    class="{{ $errors->get('phone') ? 'has-error' : '' }}"
                >
            </div>
            @error('phone')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="row-2">
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
                        autocomplete="new-password"
                    >
                </div>
                @error('password')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="field">
                <label for="password_confirmation">Confirm password</label>
                <div class="input-wrap">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="••••••••"
                        required
                        autocomplete="new-password"
                    >
                </div>
            </div>
        </div>

        <button type="submit" class="btn-submit" style="margin-top:6px;">Create Account</button>
    </form>

    <div class="switch-line" style="margin-top:20px;">
        Already registered? <a href="{{ route('login') }}">Sign in</a>
    </div>

</div>
</div>

</x-guest-layout>