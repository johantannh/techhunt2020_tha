<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    @yield ('title')
	@include('layouts.head')
	@yield ('head')
	
</head>
<body>

	@include('layouts.header')

        <div class="container">
			<header class="row">
			</header>
            <div class="content">
               @yield('content')
            </div>
        </div>
    @include('layouts.scripts')
    @yield('scripts')
</body>
</html>
