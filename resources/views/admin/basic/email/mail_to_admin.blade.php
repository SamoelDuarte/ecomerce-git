@extends('admin.layout')

@section('content')
<div class="page-header">
  <h4 class="page-title">{{ __('Mail To Admin') }}</h4>
  <ul class="breadcrumbs">
    <li class="nav-home">
      <a href="{{ route('admin.dashboard') }}">
        <i class="flaticon-home"></i>
      </a>
    </li>
    <li class="separator">
      <i class="flaticon-right-arrow"></i>
    </li>
    <li class="nav-item">
      <a href="#">{{ __('Settings') }}</a>
    </li>
    <li class="separator">
      <i class="flaticon-right-arrow"></i>
    </li>
    <li class="nav-item">
      <a href="#">{{ __('Email Settings') }}</a>
    </li>
    <li class="separator">
      <i class="flaticon-right-arrow"></i>
    </li>
    <li class="nav-item">
      <a href="#">{{ __('Mail To Admin') }}</a>
    </li>
  </ul>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <form action="{{ route('admin.mailtoadmin.update') }}" method="post">
        @csrf
        <div class="card-header">
          <div class="row">
            <div class="col-lg-12">
              <div class="card-title">Notificação de E-mail & Telefone</div>
            </div>
          </div>
        </div>
        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-6 m-auto">
              <div class="alert alert-warning text-left" role="alert">
                <strong>Este endereço de e-mail e telefone serão utilizados para receber todos os e-mails de clientes e notificações de sistema</strong>
              </div>
              @csrf
              <div class="form-group">
                <label>{{ __('Email Address') }} <span class="text-danger">**</span></label>
                <input class="form-control" type="email" name="to_mail" value="{{ $abe->to_mail }}">
                @if ($errors->has('to_mail'))
                <p class="mb-0 text-danger">{{ $errors->first('to_mail') }}</p>
                @endif
              </div>

              <div class="form-group">
                <label>{{ __('WhatsApp Number') }} <span class="text-danger">**</span></label>
                <input class="form-control" type="text" name="whatsapp_to" id="whatsapp_to" value="{{ $abe->whatsapp_to ?? '' }}" placeholder="+55 (00) 00000-0000">
                @if ($errors->has('whatsapp_to'))
                <p class="mb-0 text-danger">{{ $errors->first('whatsapp_to') }}</p>
                @endif
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="form">
            <div class="form-group from-show-notify row">
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-success">{{ __('Update') }}</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
  $(document).ready(function() {
    // Máscara para telefone brasileiro com DDD e 9 dígitos
    var SPMaskBehavior = function(val) {
        return val.replace(/\D/g, '').length === 11 ? '+00 (00) 00000-0000' : '+00 (00) 0000-00009';
      },
      spOptions = {
        onKeyPress: function(val, e, field, options) {
          field.mask(SPMaskBehavior.apply({}, arguments), options);
        }
      };

    $('#whatsapp_to').mask(SPMaskBehavior, spOptions);
  });
</script>
@endsection