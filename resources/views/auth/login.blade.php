@extends('layouts.master')

@section('title', 'Login')

@section('content')
<div>
    <h3 class="form-title">Login to Your Account</h3>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Username/Login -->
        <div class="form-group">
            <label for="login" class="form-label">Username</label>
            <input id="login" type="text" class="form-control @error('login') is-invalid @enderror" name="login"
                value="{{ old('login') }}" required autocomplete="login" autofocus>

            @error('login')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                name="password" required autocomplete="current-password">

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="form-group text-center">
            <button type="submit" class="btn btn-primary btn-block">
                Login
            </button>
        </div>

    </form>
</div>
@endsection