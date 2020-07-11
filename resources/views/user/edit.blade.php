@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Edit you profile') }}</div>

                    <div class="card-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{$error}}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (isset($message))
                            <div class="alert alert-success">
                                {{ $message }}<br />
                            </div>
                        @endif

                        <form method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">
                                    {{ __('Name') }}:
                                </label>
                                <div class="col-md-6">
                                    <input class="form-control" name="name" id="name" type="text" value="{{ $user->name }}"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="login" class="col-md-4 col-form-label text-md-right">
                                    {{ __('Login') }}:
                                </label>
                                <div class="col-md-6">
                                    <input class="form-control" name="login" id="login" type="text" value="{{ $user->login }}"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">
                                    {{ __('Email') }}:
                                </label>
                                <div class="col-md-6">
                                    <input class="form-control" name="email" id="email" type="email" value="{{ $user->email }}"/>
                                </div>
                            </div>
                            @if($src)
                            <div class="form-group row">
                                <label for="image" class="col-md-4 col-form-label text-md-right">
                                    {{ __('Current Image') }}
                                </label>
                                <div class="col-md-6">
                        {{--    <img src="{{ Storage::url($user->image) }}"><br />--}}
                                    <img src="{{ $src }}">
                                </div>
                            </div>
                            @endif
                            <div class="form-group row">
                                <label for="image" class="col-md-4 col-form-label text-md-right">
                                    {{ __('New Image') }}:
                                </label>
                                <div class="col-md-6">
                                    <input class="form-control-file" name="image" id="image" type="file" value="{{ $user->email }}"/>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection