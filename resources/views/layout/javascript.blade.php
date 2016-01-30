<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<script src="{{ asset('plugins/jquery-1.10.1.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('plugins/jquery-migrate-1.2.1.min.js') }}" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="{{ asset('plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js') }}"
        type="text/javascript"></script>
<!--[if lt IE 9]>
<script src="{{ asset('plugins/excanvas.min.js') }}"></script>
<script src="{{ asset('plugins/respond.min.js') }}"></script>
<![endif]-->
<script src="{{ asset('plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('plugins/jquery.cookie.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('plugins/uniform/jquery.uniform.min.js') }}" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<script src="{{ asset('scripts/app.js') }}"></script>
<script>
    jQuery(document).ready(function () {
        App.init();
        jQuery('#promo_carousel').carousel({
            interval: 10000,
            pause: 'hover'
        });
    });
</script>
@yield('additional-js')
<!-- END JAVASCRIPTS -->