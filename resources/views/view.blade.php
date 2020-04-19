@extends('layout')
@section('content')

<!-- Main Wrapper -->
<div id="main-wrapper">

    <!-- Main -->
    <div id="page" class="container">

        <!-- Main Heading -->
        <div class="title-heading">
            <h2>{{ $event->title }}</h2>
            <p>{{ $event->start }}</p>
        </div>

        <!-- Main Content -->
        <div id="main">
            <div class="row">

                <!-- Content -->
                <div id="content" class="col-8 col-12-narrower">
                    <article>
                        <header>
                            <h2>{{ $data['event-descr'] }}</h2>
                        </header>
                        <p>{{ $event->descr }}</p>
                    </article>
                    <a href="https://{{ env('PROSODY_HOST') }}/{{ $event->url }}" class="button small alt2">{{ $data['event-signin'] }}</a>
                </div>

                <!-- Sidebar -->
                <div id="sidebar" class="col-4 col-12-narrower">
                    <section class="section-padding">
                        <header>
                            <h2>{{ $data['event-info'] }}</h2>
                        </header>
                        <ul class="links">
                            <li><b>{{ $data['event-url'] }}: {{ $event->url }}</b></li>
                            <li><b>{{ $data['event-server'] }}: {{ env('PROSODY_HOST') }}</b></li>
                            <li>{{ $data['event-start'] }}: {{ $event->start }}</li>
                            <li>{{ $data['event-end'] }}: {{ $event->end }}</li>
                            <li>{{ $data['event-cnt'] }}: {{ $event->cnt }}</li>

                            @if ($event->price)
                            <li>{{ $data['event-price'] }}: {{ $event->price }}</li>
                            @endif

                            @if (Auth::check() && $event->user_id == Auth::user()->id ||
                            !$event->price || $tran)
                            <li><b>{{ $data['event-pwd'] }}: {{ $event->password }}</b></li>
                            <li style="font-size: smaller;"><b style="color: red;">{{ $data['help-warning'] }}</b> {{ $data['event-pwd-help'] }}</li>
                            @endif
                        </ul>

                        @if ( $event->price || !$tran)
                        <footer>
                            <form method="post" action="/site/signup/{{ $event->id }}">
                                <input type="hidden" name="_token" value="{{ Session::token() }}" />
                                <div class="row gtr-50 gtr-uniform">
                                    <div class="col-12">
                                        <select class="form-control" name="pay_type" id="pay_type">
                                            <option value="any-card-payment-type" selected="selected">{{ $data['pay-card'] }}</option>
                                            <option value="yamoney-payment-type">{{ $data['pay-ya'] }}</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <ul class="actions">
                                            <li><button type="submit" class="button alt">{{ $data['event-signup'] }}</button></li>
                                        </ul>
                                    </div>
                                </div>
                            </form>
                        </footer>
                        @endif
                        <hr>
                    </section>

                    <section>
                        <header>
                            <h2>{{ $event->user->name }}</h2>
                        </header>
                        <p><img src="{{ $event->user->avatar }}" alt="{{ $event->user->name }}" /></p>
                    </section>

                    <section>
                        <p class="small">{{ $event->user->text }}</p>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
