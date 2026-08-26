

<x-guest-layout>

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

</x-guest-layout>