@extends('layout.default')
@section('additional-css')
        <!-- BEGIN PAGE LEVEL STYLES -->
<link href="{{ asset('css/pages/about-us.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('plugins/select2/select2_metro.css') }}" type="text/css" rel="stylesheet">
<link href="{{ asset('plugins/chosen-bootstrap/chosen/chosen.css') }}" type="text/css" rel="stylesheet">
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
                    <div class="block-transparent">
                        <div class="container">
                            <div class="span12">
                                <h3 class="page-title">
                                    STEP 1.2
                                    <small>Spotify.SVDW.Approve</small>
                                </h3>
                                <ul class="breadcrumb">
                                    <li>You approving, that agree with that we wil collect Discover Weekly playlist
                                        data using access that you gave us in previous step. Next step will be review of
                                        received data from Spotify and new functionality.
                                    </li>
                                </ul>
                                @include('spotify.block.first-form')
                            </div>
                        </div>
                    </div>
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
    <script src="{{ asset('scripts/app.js') }}" type="text/javascript"></script>
    <script src="{{ asset('scripts/form-wizard.js') }}" type="text/javascript"></script>
    <script>
        jQuery(document).ready(function () {
            // initiate layout and plugins
            App.init();
        });
    </script>
@stop