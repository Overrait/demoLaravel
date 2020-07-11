@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('You short link list') }}</div>

                    <div class="card-body">
                        @if (count($errors) > 0)
                        <div class="col-md-12 alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        @if (Session::has('success'))
                        <div class="col-md-12 alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                        @endif
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <td>Email</td>
                                    <td>Currency roles</td>
                                    <td>Add/delete role Moderator</td>
                                </tr>
                            </thead>
                            <tbody>
                            @each('user.user', $users, 'user')
                            </tbody>
                        </table>
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection