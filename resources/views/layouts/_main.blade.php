<!DOCTYPE doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8"/>
        <meta content="width=device-width, initial-scale=1" name="viewport"/>
        <title>
            {{ $__env->yieldContent('title', config('app.name', 'Laravel Project')) }}
        </title>

        {{--
        	# NON ESCAPED
        	<title>@yield('title', config('app.name', 'Laravel Project'))</title>
        --}}
        
        <meta content="{{ csrf_token() }}" name="csrf-token"/>

        <!-- Scripts -->
        <script defer="" src="{{ asset('js/app.js') }}"></script>
        @yield('scripts')
        
        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
        {{--  @yield('fonts') --}}
        
        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
        @yield('styles')
    </head>
    <body>
        @yield('body')
    </body>
</html>