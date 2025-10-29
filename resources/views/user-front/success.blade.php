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
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <h2 class="mb-4 text-success">Pedido Recebido!</h2>
                            <p class="lead mb-3">Seu pedido foi registrado com <strong>sucesso</strong>.</p>
                            <p class="mb-3">Você pode acompanhar o status do seu pedido no <strong>painel de pedidos</strong> do site.</p>
                            <p class="mb-3">Assim que o pagamento for aprovado, você receberá um e-mail com a confirmação e, se for produto digital, com os códigos de acesso.</p>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>

  <!--====== Purchase Success Section End ======-->
@endsection
