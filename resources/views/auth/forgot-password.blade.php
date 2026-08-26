

<x-guest-layout>

    <div class="panel-head">
        <h2>Reset your password</h2>
        <p>{{ __('No problem. Enter your email address below and we will send you a link to reset your password.') }}</p>
    </div>

    <form method="POST" action="{{ route('password.email') }}">
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

        <button type="submit" class="btn-submit">{{ __('Email Password Reset Link') }}</button>
    </form>

    <div class="switch-line">
        Remembered your password? <a href="{{ route('login') }}">Sign in</a>
    </div>

</x-guest-layout>