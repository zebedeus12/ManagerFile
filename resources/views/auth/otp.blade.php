@extends('layouts.master')

@section('title', 'Verifikasi OTP')

@section('content')
<div>
    <h3 class="form-title">Masukkan Kode OTP</h3>
    <p>Kode OTP telah dikirim ke WhatsApp Anda. Silakan masukkan kode di bawah ini.</p>

    <form method="POST" action="{{ route('verify.otp') }}">
        @csrf

        <input type="hidden" name="login" value="{{ session('login') }}">

        <div class="form-group">
            <label for="otp" class="form-label">Kode OTP</label>
            <input id="otp" type="text" class="form-control @error('otp') is-invalid @enderror"
                name="otp" required placeholder="Masukkan OTP">

            @error('otp')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">
            Verifikasi OTP
        </button>
    </form>
</div>
@endsection
