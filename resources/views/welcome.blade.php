<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>ParSU Online Student Clearance System</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <script src="{{ asset('js/app.js') }}" defer></script> 
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <style>
            .v-navigation-drawer__content {
              overflow: hidden !important;
              scrollbar-width: none;
              -ms-overflow-style: none;
            }
            
            .v-navigation-drawer::-webkit-scrollbar {
              width: 0;
              height: 0;
            }
            </style>
    </head>
    <body>
        <div id="app">
            <app/>
        </div>
    </body>
</html>
