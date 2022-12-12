<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google-site-verification" content="LokbKwVb22t7bv_dmSpUQ_uVAoyjSdF4ODTFoJ_r0es" />
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">

        <title>ParSU Online Student Clearance System</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <!-- <script src="{{ asset('js/app.js') }}" defer></script>  -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
             <script src="{{ mix('js/app.js') }}" defer></script>
        <script src="{{ mix('js/manifest.js') }}" defer></script>
        <script src="{{ mix('js/vendor.js') }}" defer></script>
    </head>
   
    <body>
        <div id="app">
            <app/>
        </div>
    </body>
</html>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-173156254-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-173156254-1');
</script>