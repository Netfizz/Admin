<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="{{ asset('packages/netfizz/admin/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('packages/netfizz/admin/css/jquery.dataTables.css') }}" />
    <link rel="stylesheet" href="{{ asset('packages/netfizz/form-builder/css/collection.css') }}" />
    <link rel="stylesheet" href="{{ asset('packages/netfizz/admin/css/style.css') }}">
    <script src="{{ asset('packages/netfizz/admin/js/jquery-2.1.0.min.js') }}"></script>
    <script src="{{ asset('packages/netfizz/admin/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('packages/netfizz/admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('packages/netfizz/sortable/js/jquery-sortable.js') }}"></script>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
<body class="interface">
<div class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">

        <div class="navbar-header">

            {{-- The Responsive Menu Button --}}
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            {{-- The CMS Home Button --}}
            <a class="navbar-brand" href="{{ URL::action('Netfizz\Admin\Controllers\DashboardController@index'); }}">{{ $sitename }}</a>
        </div>

        {{-- The menu items at the top (collapses down when browser gets small) --}}
        <div class="collapse navbar-left navbar-collapse">
            {{ $main_menu }}
        </div><!-- /.nav-collapse -->

    </div><!-- /.container -->



</div><!-- /.navbar -->

<div class="container">
    {{ $breadcrumbs }}

    @if (Session::has('message'))
    <div class="flash alert alert-success">
        <p>{{ Session::get('message') }}</p>
    </div>
    @endif

    <div class="test">
        @yield('content')
    </div>
</div><!-- /.container -->


</body>
</html>