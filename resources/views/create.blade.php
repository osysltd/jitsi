@extends('layout')
@section('content')

<!-- Main Wrapper -->
<div id="main-wrapper">

    <!-- Main -->
    <div id="page" class="container">

        <!-- Main Heading -->
        <div class="title-heading">
            <h2> {{ Auth::user()->name }}</h2>
            <p>{{ $data['p-create-title'] }}</p>
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
                        <p style="margin-bottom: 0.2em;">{{ $data['profile-login'] }} {{ Auth::user()->login }}</p>
                        <p style="margin-bottom: 0.2em;">{{ $data['profile-pwd'] }} {{ Auth::user()->password->value }}</p>
                        <p style="margin-bottom: 0.2em;">{{ $data['profile-pwd-upd'] }} {{ Auth::user()->password->updated_at }}</p>
                        <footer>
                            <a href="/site/changepw" class="button small alt2">{{ $data['profile-changepw'] }}</a>
                        </footer>
                    </section>
                    <section class="section-padding">
                        <form method="post" action="/site/savedetails">
                            <input type="hidden" name="_token" value="{{ Session::token() }}" />
                            <div class="row gtr-50 gtr-uniform">
                                <div class="col-12">
                                    <textarea maxlength="255" name="text" id="text" placeholder="{{ $data['profile-about'] }}">{{ Auth::user()->text }}</textarea>
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

                <!-- Content -->
                <div id="content" class="col-8 col-12-narrower imp-narrower">
                    <article>
                        <header>
                            <h2>Integer sit amet pede vel arcu aliquet pretium</h2>
                        </header>
                        <a href="#" class="image featured"><img src="images/pic04.jpg" alt=""></a>
                        <h3>Hendrerit eu mollis imperdiet nibh ac hendrerit neque sed scelerisque bitur. Ligula consequat turpis feugiat penatibus ultrices ipsum.</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ac purus ut ligula ultrices viverra. Donec non odio lectus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vivamus posuere pulvinar cursus. Nullam id ultricies neque. Curabitur id tellus a metus consectetur. Nullam id sapien mi, iaculis imperdiet magna. Fusce lacus ipsum, rhoncus at placerat eu, condimentum eu arcu. Nullam id sapien mi, iaculis imperdiet magna. Fusce lacus ipsum.</p>
                        <h3>Feugiat auctor non consequat</h3>
                        <p>Nullam id sapien mi, iaculis imperdiet magna. Fusce lacus ipsum, rhoncus at placerat eu, condimentum eu arcu. Ut viverra velit dictum est tempor eget rhoncus nulla pretium. In pretium purus nec est convallis ornare non ut tellus. Donec aliquam gravida enim interdum ultricies. Duis fringilla nisl non mi laoreet placerat. Nullam id sapien mi, iaculis imperdiet magna. Fusce lacus ipsum, rhoncus at placerat eu, condimentum eu arcu. Nam ac velit ut mauris mollis dapibus eget quis dolor. Donec porta adipiscing ultrices. Fusce bibendum pulvinar libero quis rutrum. </p>
                    </article>
                </div>

            </div>
        </div>
        <!-- Main Content -->
    </div>
    <!-- Main -->
</div>
@endsection
