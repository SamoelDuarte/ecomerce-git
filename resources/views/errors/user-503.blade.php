<html>

<head>
  <title>{{ getParam() }} - {{ __('Maintainance Mode') }}</title>
  <!-- favicon -->
  <link rel="shortcut icon" href="{{ asset('assets/front/img/user/' . $userBs->favicon) }}" type="image/x-icon">
  <!-- bootstrap css -->
  <link rel="stylesheet" href="{{ asset('assets/front/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/user-front/css/503.css') }}">
</head>

<body>
  <div class="container">
    <div class="content">
      <div class="row mt-4">
        <div class="col-lg-4 offset-lg-4">
          <div class="maintanance-img-wrapper">
            <img src="{{ asset('assets/user-front/images/' . $userBs->maintenance_img) }}" alt="image">
          </div>
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-lg-8 offset-lg-2">
          <h3 class="maintanance-txt"> {!! nl2br($userBs->maintenance_msg) !!}</h3>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
