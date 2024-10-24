@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
<div class="card">
    <div class="container">
        <h1>Welcome to the Dashboard</h1>
        <p>Hello, {{ Auth::user()->name }}! You are logged in.</p>
        <a href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</div>
@endsection