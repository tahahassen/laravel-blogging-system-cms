@extends('layouts.app')

@section('content')
    
    <div class="card card-default">
        <div class="card-header">
            users
        </div>
        <div class="card-body">
            @if ($users->count()> 0)
            
            <table class="table">
                <thead>
                    <th>Images</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th></th>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>
                            <img src="{{Gravatar::src($user->email)}}" width="40px" height="40px" style="border-radius: 50%" alt="">
                            </td>
                            <td>
                                {{$user->name}}
                            </td>
                            <td>
                                
                                {{$user->email}}
                            </td>
                            <td>
                                @if (! $user->isAdmin())
                                    <form action="{{route('users.make-admin',$user->id)}}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-success">Make-Admin</button>
                                    </form>         
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>    
            @else
                <h3 class="text-center">No users Yet</h3>
                
            @endif
        </div>
    </div>
@endsection