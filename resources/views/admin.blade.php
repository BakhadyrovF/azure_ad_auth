@extends('layout')
@section('title', 'Home')

@section('content')
<h1>Welcome to Admin Panel {{$user->full_name}}</h1>
<h3>Your Mail: {{$user->email}}</h3>

@auth
    <a href="logout">Log Out</a>
@endauth

@endsection
