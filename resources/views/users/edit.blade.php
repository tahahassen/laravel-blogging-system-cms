@extends('layouts.app')
@section('content')

<div class="card">
    @include('partials.error')
    <div class="card-header">My Profil</div>
    <div class="card-body">
    <form action="{{route('users.update-profile')}}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name</label>
        <input type="text" class="form-control" name="name" value="{{$user->name}}">
        </div>

        <div class="form-group">
            <label for="about">About Me</label>
            <textarea name="about" id="about" cols="5" rows="5" class="form-control">{{$user->about}}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Update Profil</button>
    </form>
    </div>
</div>
    
@endsection