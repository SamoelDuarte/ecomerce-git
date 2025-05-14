@extends('user-front.layout')

@section('breadcrumb_title', $keywords['Payment Success'] ?? __('Payment Success'))
@section('page-title', $keywords['Payment Success'] ?? __('Payment Success'))

@section('content')
  <div class="purchase-message pb-100 pt-200">
    <div class="container mx-auto">
      <div class="purchase-success text-center">
        <div class="success-icon-area">
          @includeIf('user-front.partials.success-svg')
        </div>
        <h3 class="mb-2 congratulation">
          {{ $keywords['success'] ?? __('Success') . '!' }}
        </h3>
        <p class="mt-2 description">
          {{ $keywords['your_transaction_was_successful'] ?? __('Your transaction was successful') . '.' }}
        </p>
        <p class="mb-3 description">
          {{ $keywords['We_have_sent_you_a_mail_with_an_invoice'] ?? __('We have sent you a mail with an invoice') . '.' }}
        </p>
        <a href="{{ route('front.user.shop', getParam()) }}"
          class="btn btn-md btn-primary radius-sm">{{ $keywords['Back to Shop'] ?? __('Back to Shop') }}</a>
      </div>
    </div>
  </div>

  <!--====== Purchase Success Section End ======-->
@endsection
