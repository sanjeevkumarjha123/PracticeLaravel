<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>AdminLTE 3 | Dashboard</title>
      
        @include('layouts.partials.style')
      </head>

      <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            

            <!-- Preloader -->
            {{-- <div class="preloader flex-column justify-content-center align-items-center">
                <img class="animation__shake" src="assets/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
            </div> --}}

            @include('layouts.partials.nav')
            @yield('content')
            @include('layouts.partials.sidebar')
            @include('layouts.partials.footer')
            </div>

        </div>
            @include('layouts.partials.script')
      </body>
</html>