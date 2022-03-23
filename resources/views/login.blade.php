@extends('layout')
@section('title', 'Login')

@section('content')
<form class="mt-5">
    <div class="mb-3">
      <label for="exampleInputEmail1" class="form-label">Email address</label>
      <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
    </div>
    <div class="mb-3">
      <label for="exampleInputPassword1" class="form-label">Password</label>
      <input type="password" class="form-control" id="exampleInputPassword1">
    </div>
    <button type="submit" class="btn btn-secondary"><a href="{{route('azure_login')}}" style="color:white">Login with Azure AD</a></button>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
@endsection
