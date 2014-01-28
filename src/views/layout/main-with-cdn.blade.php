<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.0.3/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ asset('packages/netfizz/admin/css/style.css') }}">

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
            @if($menu_items)
            <ul class="nav navbar-nav">
                @foreach($menu_items as $url=>$item)
                @if( $item['top'] )
                <li class="{{ Request::is( "$urlSegment/$url*" ) ? 'active' : '' }}">
                <a href="{{ url( $urlSegment.'/'.$url ) }}">{{ $item['name'] }}</a>
                </li>
                @endif
                @endforeach
                <li><a href="{{ url( $urlSegment.'/logout' ) }}"><strong>Logout</strong></a></li>
            </ul>
            @endif
        </div><!-- /.nav-collapse -->

    </div><!-- /.container -->

</div><!-- /.navbar -->

<div class="container">
    <div class="row">
        @yield('content')
    </div>
</div><!-- /.container -->

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.0.3/js/bootstrap.min.js"></script>
</body>
</html>