<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    {!! Html::script(asset('frontend/js/webfontloader.min.js')) !!}
    <script>
        WebFont.load({
            google: {
                families: ['Roboto:300,400,500,700:latin']
            }
        });
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
            'locale' => config('app.locale'),
            'fallbackLocale' => config('app.fallback_locale'),
            'url' => url('/'),
            'languages' => config('settings.locale'),
        ]) !!};
        @if (session('access_token'))
            localStorage.setItem('access_token', '{{ session('access_token') }}')
        @endif
    </script>
    {!! Html::style(asset('frontend/css/bootstrap.css')) !!}
    {!! Html::style(mix('frontend/css/theme.css')) !!}
    {!! Html::style(mix('frontend/css/blocks.css')) !!}
    {!! Html::style(mix('frontend/css/font.css')) !!}
    {!! Html::style(asset('frontend/css/daterangepicker.css')) !!}
    {!! Html::style(asset('frontend/css/bootstrap-select.css')) !!}
    {!! Html::style(mix('frontend/css/app.css')) !!}
</head>

<body>
    <div id="app">
        <router-view></router-view>
    </div>
    {!! Html::script(asset('frontend/js/bootstrap.js')) !!}
    {!! Html::script(asset('frontend/js/material.min.js')) !!}
    {!! Html::script(asset('frontend/js/theme-plugins.js')) !!}
    {!! Html::script(asset('frontend/js/main.js')) !!}
    {!! Html::script(asset('frontend/js/selectize.min.js')) !!}
    {!! Html::script(asset('frontend/js/daterangepicker.min.js')) !!}
    {!! Html::script(mix('frontend/js/app.js')) !!}
</body>

</html>
