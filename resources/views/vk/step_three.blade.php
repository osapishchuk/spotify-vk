@extends('layout.default')
@section('additional-css')
        <!-- BEGIN PAGE LEVEL STYLES -->
<link href="{{ asset('css/pages/about-us.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('plugins/select2/select2_metro.css') }}" type="text/css" rel="stylesheet">
<link href="{{ asset('plugins/chosen-bootstrap/chosen/chosen.css') }}" type="text/css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
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
                                    STEP 2.3
                                    <small>VK.SVDW.Search/Import</small>
                                </h3>
                                <ul class="breadcrumb">
                                    <li>Let's magic begin. We will do the async ajax search for you in VK
                                        based on songs from Spotify without refreshing the page opened. You will see
                                        list of them and ability to import only those songs that you wish to import
                                        using same ajax technology. It's amazing!
                                    </li>
                                </ul>
                                @include('vk.block.third-form')
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
    <script src="{{ asset('scripts/vk.js') }}" type="text/javascript"></script>
    <script>
        jQuery(document).ready(function () {
            // initiate layout and plugins
            App.init();
            UIModals.init();
        });
    </script>
@stop