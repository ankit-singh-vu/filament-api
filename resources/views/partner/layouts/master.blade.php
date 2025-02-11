<!DOCTYPE html>
<html lang="en">
  <head>
  <title>Convesio Partner</title>
  @include('partner/partials/header')
  @include('partner/partials/sidebar')
  @include('partner/partials/navbar')

<body>
  <style>
    .topright { position: absolute; top: 15px; right: 10px; text-align: right; }
    .bottomright { position: absolute; bottom: 5px; right: 5px; text-align: right; }
  </style>
@if(count($errors) > 0)
  @foreach( $errors->all() as $message )
    <div class="alert alert-danger display-hide topright">
    <!-- <button class="close" data-close="alert"></button> -->
    <span>{{ $message }}</span>
    </div>
  @endforeach
@endif
  @yield('content')
    
  <script>
      'use script'

      var skinMode = localStorage.getItem('skin-mode');
      if(skinMode) {
        $('html').attr('data-skin', 'dark');
      }

      setTimeout(function() {
        $('.alert').fadeOut('fast');
        }, 1000);
    </script>
@include('partner/partials/footer')
</body>
</html>