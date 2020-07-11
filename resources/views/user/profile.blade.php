@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('You profile') }}</div>

                    <div class="card-body">
                        <div class="col-md-12">
                            <a class="float-right" href="{{ route('edit_profile') }}">{{ __('Edit') }}</a>
                        </div>
                        <div class="col-md-4">
                        </div>
                        <div class="coll-md-6">
                            <table class="table table-striped table-hover">
                                @if($user->hasMedia('avatar'))
                                <tr>
                                    <td>Avatar</td>
                                    <td>
                                        <img src="{{$user->getFirstMediaUrl('avatar', 'thumb')}}"><br />
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td>Name</td>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <td>Login</td>
                                    <td>{{ $user->login }}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection