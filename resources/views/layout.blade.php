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
    <meta name="keywords" content="{{ $data['site-kwords'] }}" />
    <meta name="description" content="{{ $data['site-descr'] }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="/assets/css/main.css" />
    <title>{{ config('app.name') }} - {{ $data['site-title'] }}</title>
</head>
<body class="homepage is-preload">
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
                        <li>
                            <a href="#">{{ $data['btn-cat'] }}</a>
                            <ul>
                                @foreach ($menu as $adata)
                                <li>
                                    <a title="{{ $adata['descr'] }}" href="{{ $adata['url'] }}">{{ $adata['title'] }}</a>
                                    <!-- <ul><li><a href="todo"></a></li></ul>-->
                                </li>
                                @endforeach
                            </ul>
                        </li>

                        @if (Auth::check())
                        <li><a title="{{ $data['btn-create-descr'] }}" href="/site/create">{{ $data['btn-create'] }}</a></li>
                        <li><a title="{{ Auth::user()->name }}" href="/site/logout">{{ $data['btn-logout'] }}</a></li>
                        @else
                        <li><a title="{{ $data['btn-login-descr'] }}" href="/site/auth">{{ $data['btn-login'] }}</a></li>

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

                <div class="title"><span>{{ $data['footer-title'] }}</span></div>

                <div class="row">

                    <!-- Form -->
                    <section class="col-8 col-12-narrower">
                        <header>
                            <h2>{{ $data['footer-header'] }}</h2>
                        </header>
                        <form method="post" action="#">
                            <div class="row gtr-50 gtr-uniform">
                                <div class="col-6 col-12-narrower">
                                    <input type="text" name="name" id="name" placeholder="Name" />
                                </div>
                                <div class="col-6 col-12-narrower">
                                    <input type="text" name="email" id="email" placeholder="Email" />
                                </div>
                                <div class="col-12">
                                    <input type="text" name="subject" id="subject" placeholder="Subject" />
                                </div>
                                <div class="col-12">
                                    <textarea name="message" id="message" placeholder="Message"></textarea>
                                </div>
                                <div class="col-12">
                                    <ul class="actions">
                                        <li><a href="#" class="button alt2">Send Message</a></li>
                                        <li><a href="#" class="button alt">Clear Form</a></li>
                                    </ul>
                                </div>
                            </div>
                        </form>
                    </section>

                    <!-- Other -->
                    <section class="col-4 col-12-narrower">
                        <header>
                            <h2>Adipiscing rhoncus</h2>
                        </header>
                        <ul class="icons">
                            <li class="icon brands fa-twitter">
                                <a href="http://twitter.com/n33co">@untitled-corp</a>
                            </li>
                            <li class="icon brands fa-facebook-f">
                                <a href="#">facebook.com/untitled-corp</a>
                            </li>
                            <li class="icon brands fa-linkedin-in">
                                <a href="#">linkedin.com/untitled-corp</a>
                            </li>
                            <li class="icon solid fa-envelope">
                                <a href="#">info@untitled.tld</a>
                            </li>
                            <li class="icon solid fa-phone">
                                <span>(000) 000-0000</span>
                            </li>
                            <li class="icon solid fa-home">
                                <span>
                                    1234 Fictional Road #789<br />
                                    Nashville, TN 00000<br />
                                    United States
                                </span>
                            </li>
                        </ul>
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

</body>
</html>
