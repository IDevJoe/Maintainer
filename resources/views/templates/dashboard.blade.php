@extends('templates.base')

@section('base_header')
    @yield('header')
@endsection

@section('base_content')
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Maintainer</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @foreach(array_keys(\App\Providers\RouteServiceProvider::NAVBAR) as $name)
                        <li class="nav-item">
                            <a class="nav-link{{ \Illuminate\Support\Str::startsWith(\Request::route()->getName(), [\App\Providers\RouteServiceProvider::NAVBAR[$name]]) ? ' active' : '' }}" href="{{ route(\App\Providers\RouteServiceProvider::NAVBAR[$name]) }}">{{ $name }}</a>
                        </li>
                    @endforeach

                </ul>
            </div>
        </div>
    </nav>
    <div class="mt-5 mb-5 container">
        @yield('content')
    </div>
@endsection

@section('base_footer')
    @yield('footer')
@endsection
