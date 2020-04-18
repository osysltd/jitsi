<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <meta name="author" content="{{ config('app.name') . ' '. date('Y')}}" />
    <link rel="canonical" href="{{ config('app.url') }}" />
    <meta name="copyright" content="Registered, Delegated, Verified" />
    <meta name="robots" content="index, follow" />
    <meta name="keywords" content="{{ $data['kwords'] }}" />
    <meta name="description" content="{{ $data['descr'] }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="/assets/css/main.css" />
    <!-- Calendar -->
    <link rel='stylesheet' href='/assets/calendar/core/main.min.css' />
    <link rel='stylesheet' href='/assets/calendar/daygrid/main.min.css' />
    <link rel='stylesheet' href='/assets/calendar/timegrid/main.min.css' />
    <link rel='stylesheet' href='/assets/calendar/list/main.min.css' />
    <link rel="stylesheet" href="/assets/calendar/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" href="/assets/calendar/flatpickr/ie.css">
    <link rel="stylesheet" href="/assets/calendar/popper.js/popper.css">

    <title>{{ config('app.name') }} - {{ $data['title'] }}</title>
</head>
<body class="{{ isset($bodyclass) ? $bodyclass : 'no-sidebar' }} is-preload">
    <div id="page-wrapper">

        <!-- Header Wrapper -->
        <div id="header-wrapper">

            <!-- Header -->
            <div id="header" class="container">

                <!-- Logo -->
                <h1><a href="/" id="logo">
                        <img itemprop="image" alt="{{ config('app.name') }}" src="/assets/images/logo.svg" width="80" style="margin-top: 0.5em;">
                    </a>
                </h1>

                <!-- Nav -->
                <nav id="nav">
                    <ul>
                        @if (Auth::check())
                        <li><a title="{{ $data['btn-create-descr'] }}" href="/site/event">{{ $data['btn-create'] }}</a></li>
                        <li><a title="{{ Auth::user()->name }}" href="/site/logout">{{ $data['btn-logout'] }}</a></li>
                        @else
                        <li><a title="{{ $data['btn-login-descr'] }}" href="/site/login">{{ $data['btn-login'] }}</a></li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>

        @yield('content')

        <!-- Footer Wrapper -->
        <div id="footer-wrapper">

            <!-- Footer -->
            <div id="footer" class="container">

                <div class="title"><span>{{$data['footer-title']}}</span></div>

                <div class="row">

                    <!-- Form -->
                    <section class="col-9 col-12-narrower">
                        <!-- Calendar -->
                        <div id='calendar'></div>
                    </section>

                    <!-- Other -->
                    <section class="col-3 col-12-narrower">
                        <header>
                            <h2>{{$data['footer-contact']}}</h2>
                        </header>
                        <ul class="icons">
                            <li class="icon solid fa-envelope">
                                <a href="mailto:{{$data['footer-email']}}">{{$data['footer-email']}}</a>
                            </li>
                            <li class="icon solid fa-phone">
                                <span>{{$data['footer-phone']}}</span>
                            </li>
                        </ul>
                        <p style='padding-top: inherit;'>{!! $data['footer-text'] !!}
                    </section>

                </div>

            </div>

        </div>


        <!-- Copyright -->
        <div id="copyright">&copy; {{ config('app.name') . ' '. date('Y')}}</div>
    </div>

    <!-- Scripts -->
    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/js/jquery.dropotron.min.js"></script>
    <script src="/assets/js/browser.min.js"></script>
    <script src="/assets/js/breakpoints.min.js"></script>
    <script src="/assets/js/util.js"></script>
    <script src="/assets/js/main.js"></script>
    <!-- Calendar -->
    <script src='/assets/calendar/core/main.js'></script>
    <script src='/assets/calendar/core/locales/ru.js'></script>
    <script src='/assets/calendar/interaction/main.min.js'></script>
    <script src='/assets/calendar/daygrid/main.min.js'></script>
    <script src='/assets/calendar/timegrid/main.min.js'></script>
    <script src='/assets/calendar/list/main.min.js'></script>
    <script src='/assets/calendar/popper.js/popper.min.js'></script>
    <script src='/assets/calendar/tooltip.js/tooltip.min.js'></script>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var SITEURL = "{{url('/') . '/site/list'}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ Session::token() }}"
            }
        });
        var initialLocaleCode = 'ru';

        var localeSelectorEl = document.getElementById('locale-selector');
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: ['interaction', 'dayGrid', 'timeGrid', 'list']
            , header: {
                left: 'prev,next today'
                , center: 'title'
                , right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            }
            , locale: initialLocaleCode
            , buttonIcons: false
            , weekNumbers: false
            , navLinks: true
            , editable: false
            , eventLimit: true
            , events: SITEURL
            , eventRender: function(info) {
                var tooltip = new Tooltip(info.el, {
                    title: info.event.extendedProps.description
                    , placement: 'top'
                    , trigger: 'hover'
                    , container: 'body'
                });
            }
        , });

        calendar.render();

    });

</script>
</html>
