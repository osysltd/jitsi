@extends('layout', ['bodyclass' => 'homepage'])
@section('content')

<!-- Main Wrapper -->
<div id="main-wrapper">

    <!-- Main -->
    <div id="banner-wrapper">
        <section id="banner" class="container">
            <div class="row">
                <div id="content" class="col-12">
                    <header class="major">
                        <h2>{{ $data['title'] }}</h2>
                        <p>{{ $data['descr'] }}</p>
                    </header>
                    <ul class="pennants">
                        <li>
                            <a href="#" class="pennant"><span class="icon solid fa-ruble-sign"></span></a>
                            <header>
                                <h3>{{ $data['fa-ruble-sign-header'] }}</h3>
                            </header>
                            <p>{!! $data['fa-ruble-sign'] !!}</p>
                        </li>
                        <li>
                            <a href="#" class="pennant"><span class="icon solid fa-mobile"></span></a>
                            <header>
                                <h3>{{ $data['fa-mobile-header'] }}</h3>
                            </header>
                            <p>{!! $data['fa-mobile'] !!}</p>
                        </li>
                        <li>
                            <a href="#" class="pennant"><span class="icon solid fa-laptop"></span></a>
                            <header>
                                <h3>{{ $data['fa-laptop-header'] }}</h3>
                            </header>
                            <p>{!! $data['fa-laptop'] !!}</p>
                        </li>
                        <li>
                            <a href="#" class="pennant"><span class="icon solid fa-photo-video"></span></a>
                            <header>
                                <h3>{{ $data['fa-photo-video-header'] }}</h3>
                            </header>
                            <p>{!! $data['fa-photo-video'] !!}</p>
                        </li>
                    </ul>
                    <footer>
                        @if (Auth::check())
                        <a href="/site/event" class="button large">{{ $data['btn-create-descr'] }}</a>
                        @else
                        <a href="/site/login" class="button large">{{ $data['btn-login'] }}</a>
                        @endif
                    </footer>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Featured Wrapper -->
<div id="featured-wrapper">

    <!-- Featured -->
    <section id="featured" class="container">
        <div class="title"><span>{{ $data['featured-title'] }}</span></div>
        <div class="row">
            <div class="col-4 col-12-narrower">
                <header>
                    <h2>{{ $data['featured-header'] }}</h2>
                    <p>{{ $data['featured-comment'] }}</p>
                </header>
                <p>{{ $data['featured-text'] }}</p>
            </div>
            <div class="col-8 col-12-narrower">
                <ul class="profiles first">
                    @foreach($users as $user)
                    <li>
                        <div class="row">
                            <div class="col-2 col-12-narrower">
                                <a href="#" class="image fit"><img src="{{ $user->avatar }}" alt="{{ $user->name }}" /></a>
                            </div>
                            <div class="col-10 col-12-narrower">
                                <h3>{{ $user->name }}</h3>
                                <p>{{ $user->text }}</p>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>
</div>
@endsection
