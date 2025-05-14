<div class="col-lg-5">
  <table class="table table-striped border-0000005a">
    <thead>
      <tr>
        <th scope="col">{{ __('Short Code') }}</th>
        <th scope="col">{{ __('Meaning') }}</th>
      </tr>
    </thead>
    <tbody>

      <tr>
        <td>{customer_name}</td>
        <td scope="row">{{ __('Name of The Customer') }}</td>
      </tr>

      @if ($templateInfo->email_type == 'email_verification')
        <tr>
          <td>{verification_link}</td>
          <td scope="row">{{ __('Email Verification Link') }}</td>
        </tr>
      @endif


      @if ($templateInfo->email_type == 'reset_password')
        <tr>
          <td>{password_reset_link}</td>
          <td scope="row">{{ __('Password Reset Link') }}</td>
        </tr>
      @endif

      @if ($templateInfo->email_type == 'product_order')
        <tr>
          <td>{order_number}</td>
          <td scope="row">{{ __('Order Number') }}</td>
        </tr>
        <tr>
          <td>{shipping_fname}</td>
          <td scope="row">{{ __('Customer Shipping First Name') }}</td>
        </tr>
        <tr>
          <td>{shipping_lname}</td>
          <td scope="row">{{ __('Customer Shipping Last Name') }}</td>
        </tr>
        <tr>
          <td>{shipping_address}</td>
          <td scope="row">{{ __('Customer Shipping Address') }}</td>
        </tr>
        <tr>
          <td>{shipping_city}</td>
          <td scope="row">{{ __('Customer Shipping City') }}</td>
        </tr>
        <tr>
          <td>{shipping_country}</td>
          <td scope="row">{{ __('Customer Shipping Country') }}</td>
        </tr>
        <tr>
          <td>{shipping_number}</td>
          <td scope="row">{{ __('Customer Shipping Phone Number') }}
          </td>
        </tr>

        <tr>
          <td>{billing_fname}</td>
          <td scope="row">{{ __('Customer Billing First Name') }}</td>
        </tr>
        <tr>
          <td>{billing_lname}</td>
          <td scope="row">{{ __('Customer Billing Last Name') }}</td>
        </tr>
        <tr>
          <td>{billing_address}</td>
          <td scope="row">{{ __('Customer Billing Address') }}</td>
        </tr>
        <tr>
          <td>{billing_city}</td>
          <td scope="row">{{ __('Customer Billing City') }}</td>
        </tr>
        <tr>
          <td>{billing_country}</td>
          <td scope="row">{{ __('Customer Billing Country') }}</td>
        </tr>
        <tr>
          <td>{billing_number}</td>
          <td scope="row">{{ __('Customer Billing Phone Number') }}
          </td>
        </tr>
        <tr>
          <td>{order_link}</td>
          <td scope="row">{{ __('Order Details Link') }}</td>
        </tr>
      @endif
      @if ($templateInfo->email_type == 'product_order_status')
        <tr>
          <td>{order_status}</td>
          <td scope="row">{{ __('Status of order') }}</td>
        </tr>
      @endif

      <tr>
        <td>{website_title}</td>
        <td scope="row">{{ __('Website Title') }}</td>
      </tr>
    </tbody>
  </table>
</div>
