<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en"> <!--<![endif]-->
@include('layout.head')
<!-- BEGIN BODY -->
<body class="page-header-fixed page-full-width">
@include('layout.header')
@yield('container')
@include('layout.footer')
@include('layout.javascript')
</body>
<!-- END BODY -->
</html>