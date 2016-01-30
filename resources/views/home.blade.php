@extends('layout.default')
@section('additional-css')
<!-- BEGIN PAGE LEVEL STYLES -->
    <link href="{{ asset('css/pages/about-us.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/pages/timeline.css') }}" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL STYLES -->
@stop
@section('container')
        <!-- BEGIN CONTAINER -->
<div class="page-container row-fluid">
    <!-- BEGIN PAGE -->
    <div class="page-content no-min-height">
        <!-- BEGIN PAGE CONTAINER-->
        <div class="container-fluid promo-page">
            <!-- BEGIN PAGE CONTENT-->
            <div class="row-fluid">
                <div class="span12">
                    <div class="block-yellow">
                        <div class="container">
                            <div class="row-fluid">
                                <div class="span5 margin-bottom-20">
                                    <a href="index.html"><img src="{{ asset('img/pages/svdw2-sound.png') }}" alt=""></a>
                                </div>
                                <div class="span7">
                                    <h2>Spotify-VK Discover Weekly (SVDW)</h2>

                                    <p><strong>Spotify</strong> is free/pay online service for music without ability for
                                        user with free type of account listen to music in offline mode.</p>

                                    <p><strong>VK</strong> is free social service with ability listen to music for free
                                        in offline mode using mobile application.</p>

                                    <p>Using <strong>APIs</strong> from both services we can import playlist
                                        <strong><span class="text-success">(Discover Weekly)</span></strong> of <strong>Spotify</strong>
                                        to <strong>VK</strong> and then play music offline for <strong>FREE</strong></p>

                                    <p>You can just jump into <strong>SVDW</strong> clicking button below and your
                                        unforgiven trip wil <strong>START</strong> or you could go below and check
                                        steps that you will go through, while import <strong><span class="text-success">Discover Weekly</span></strong>
                                        playlist into you <strong>VK</strong> account. </p>

                                    <p><small>*<strong>SVDW</strong> will only use legal ways to get and import data. In this
                                        case we are using open <strong>API</strong> methods omitting any kind of illegal
                                        usage.</small></p>
                                    <div class="offset6">
                                        <a href="{{URL::to('/spotify/step_one')}}" class="btn blue big xlarge">
                                            START
                                            <i class="m-icon-big-swapright m-icon-white"></i>
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    @include('block.timeline')
                    @include('block.footer')
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT-->
    </div>
    <!-- END PAGE CONTAINER-->
</div>
<!-- END PAGE -->
<!-- END CONTAINER -->
@stop
@section('additional-js')
    <script>
        jQuery(document).ready(function() {
            // initiate layout and plugins
            App.init();
        });
    </script>
@stop