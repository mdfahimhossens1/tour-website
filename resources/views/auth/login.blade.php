

<x-guest-layout>

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

</x-guest-layout>