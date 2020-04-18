@extends('layout')
@section('content')
<!-- Main Wrapper -->
<div id="main-wrapper">

    <!-- Main -->
    <div id="page" class="container">

        <!-- Main Heading -->
        <div class="title-heading">
            <h2>{{ $data['pay-header'] }}</h2>
            <p>{{ $event->title }}</p>
        </div>

        <!-- Main Content -->
        <div id="main">
            <div class="row">
                <div id="content" class="col-12">
                    <header>
                        <h2>{{ $data['pay-cond-rec'] }} {{ $event->user->name }}</h2>
                    </header>
                    <p>Email: {{ $event->user->email }}</p>
                    <p>{{ $data['event-ywallet']}}: {{ $event->ywallet }}</p>
                    <p>{{ $data['event-price']}}: {{ $event->price }}</p>
                    <h3>{{ $data['pay-cond-header'] }}</h3>
                    <p>{!! $data['pay-cond'] !!}</p>
                    @php
                    echo App\Http\Controllers\HelperController::renderPayButton($event->id, $pay_type);
                    @endphp
                </div>
            </div>
        </div>
        <!-- Main Content -->

    </div>
    <!-- Main -->
</div>
