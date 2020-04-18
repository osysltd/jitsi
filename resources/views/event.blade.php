@extends('layout')
@section('content')

<!-- Main Wrapper -->
<div id="main-wrapper">

    <!-- Main -->
    <div id="page" class="container">

        <!-- Main Heading -->
        <div class="title-heading">
            <h2> {{ Auth::user()->name }}</h2>
            <p>
                @if(Session::has('message'))
                <p class="alert alert-info">{{ Session::get('message') }}</p>
                @else
                {{ $data['p-create-title'] }}
                @endif
            </p>
        </div>

        <!-- Main Content -->
        <div id="main">
            <div class="row">

                <!-- Sidebar -->
                <div id="sidebar" class="col-4 col-12-narrower">
                    <section class="section-padding">
                        <header>
                            <h2>{{ $data['p-create-profile'] }}</h2>
                        </header>
                        <p style="margin-bottom: 0.2em;">{{ $data['profile-login'] }}: {{ Auth::user()->login }}</p>
                        <p style="margin-bottom: 0.2em;">{{ $data['profile-pwd'] }}: {{ Auth::user()->password->value }}</p>
                        <p style="margin-bottom: 0.2em;">{{ $data['profile-pwd-upd'] }} {{ Auth::user()->password->updated_at }}</p>
                        <footer>
                            <a href="/site/password" class="button small alt2">{{ $data['profile-changepw'] }}</a>
                        </footer>
                    </section>

                    <section class="section-padding">
                        <form method="post" action="/site/profile">
                            <input type="hidden" name="_token" value="{{ Session::token() }}" />
                            <div class="row gtr-50 gtr-uniform">
                                <div class="col-12">
                                    <textarea maxlength="255" name="text" id="text" placeholder="{{ $data['profile-about'] }}" required>{{ Auth::user()->text }}</textarea>
                                </div>
                                <div class="col-12">
                                    <div class="actions">
                                        <button type="submit" class="button alt">{{ $data['profile-save'] }}</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </section>
                </div>

                <!-- Form -->
                <div id="content" class="col-8 col-12-narrower imp-narrower">
                    <form method="post" action="/site/event">
                        <input type="hidden" name="_token" value="{{ Session::token() }}" />
                        <div class="row gtr-50 gtr-uniform">
                            <div class="col-12">
                                <input maxlength="100" size="100" type="text" name="title" id="title" placeholder="{{ $data['event-title'] }}" required />
                            </div>
                            <div class="col-4 col-12-narrower">
                                <input maxlength="5" size="5" type="number" name="price" id="price" placeholder="{{ $data['event-price'] }}" required />
                            </div>
                            <div class="col-8 col-12-narrower">
                                <input maxlength="20" size="20" type="number" name="ywallet" id="ywallet" placeholder="{{ $data['event-ywallet'] }}" />
                            </div>
                            <div class="col-6 col-12-narrower">
                                <input type="date" name="start" id="start" class="date" placeholder="{{ $data['event-start'] }}" required />
                            </div>
                            <div class="col-6 col-12-narrower">
                                <input type="date" name="end" id="end" class="date" placeholder="{{ $data['event-end'] }}" required />
                            </div>
                            <div class="col-12">
                                <textarea maxlength="1500" name="descr" id="descr" placeholder="{{ $data['event-descr'] }}" required></textarea>
                            </div>
                            <div class="col-12">
                                <div class="actions">
                                    <button type="submit" class="button alt2">{{ $data['event-save'] }}</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
        <!-- Main Content -->
    </div>
    <!-- Main -->
</div>

<!-- classList polyfill for IE9 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/classlist/1.2.20171210/classList.min.js"></script>
<script src="/assets/calendar/flatpickr/flatpickr.min.js"></script>
<script src="/assets/calendar/flatpickr/l10n/ru.js"></script>

<script>
    var fp = flatpickr(".date", {
        "locale": "ru"
        , enableTime: true
        , altInput: true
        , altFormat: "F j, Y H:i"
        , dateFormat: "Y-m-d H:i"
    , })

</script>

@endsection
