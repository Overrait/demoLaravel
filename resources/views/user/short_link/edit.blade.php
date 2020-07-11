@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Edit you link') }} {{ $short_link ?? '' }}</div>

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
                        <form method="post">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <label for="link" class="col-md-4 col-form-label text-md-right">
                                    {{ __('Link') }}:
                                </label>
                                <div class="col-md-6">
                                    <input class="form-control" name="link" id="link" type="text" value="{{ $link }}"/>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">submit</button>
                                </div>
                            </div>
                        </form>
                        <div class="col-md-12">
                            <a class="float-right" href="{{ route('short_links') }}">{{ __('Back to list') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection