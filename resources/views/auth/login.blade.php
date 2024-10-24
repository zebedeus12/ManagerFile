@extends('layouts.master')

@section('title', 'Login')

@section('content')
<div class="card">
    <div class="card-header">
        Login to Your Account
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                    value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
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
</div>
@endsection