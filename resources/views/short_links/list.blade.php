@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Active short link list') }}</div>

                <div class="card-body">
                    @if (Session::has('success'))
                        <div class="col-md-12 alert alert-success">
                            {!! session()->get('success') !!}
                        </div>
                    @endif
                    @notModeratorAndPath
                    <div class="col-md-12">
                        <a class="float-right" href="{{ route('add_short_link') }}">{{ __('Add short link') }}</a>
                    </div>
                    @endModeratorAndPath
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <td>Short link</td>
                                <td>Link</td>
                                <td>status</td>
                                @if (Route::currentRouteName() === 'user_link')
                                <td></td>
                                <td></td>
                                @endif
                                @moderatorAndPath
                                <td></td>
                                @endModeratorAndPath
                            </tr>
                        </thead>
                        <tbody>
                        @each('short_links.link', $links, 'link')
                        </tbody>
                    </table>
                        {{$links->links()}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection