@extends('admin.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">
      {{ __('Registered Users') }}
    </h4>
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
        <a href="#">{{ __('Users Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Registered Users') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-6">
              <div class="card-title">
                {{ __('Registered Users') }}
              </div>
            </div>
            <div class="col-lg-6 mt-2 mt-lg-0 d-block d-lg-flex justify-content-end gap-3">
              <button class="btn btn-danger float-none btn-sm d-none bulk-delete"
                data-href="{{ route('register.user.bulk.delete') }}"><i class="flaticon-interface-5"></i>
                {{ __('Delete') }}</button>
              <form action="{{ url()->full() }}" class="float-none mt-2 mt-lg-0">
                <input type="text" name="term" class="form-control min-w-250" value="{{ request()->input('term') }}"
                  placeholder="{{ __('Search by Username / Email') }}">
              </form>
              <button class="btn btn-primary mt-2 mt-lg-0 float-none btn-sm gap-3" data-toggle="modal"
                data-target="#addUserModal"><i class="fas fa-plus"></i> {{ __('Add User') }}</button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($users) == 0)
                <h3 class="text-center">{{ __('NO USER FOUND') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Username') }}</th>
                        <th scope="col">{{ __('Email') }}</th>
                        <th scope="col">{{ __('Category') }}</th>
                        <th scope="col">{{ __('Featured') }}</th>
                        <th scope="col">{{ __('Preview Template') }}</th>
                        <th scope="col">{{ __('Email Status') }}</th>
                        <th scope="col">{{ __('Account') }}</th>
                        <td scope="col">{{ __('Action') }}</td>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($users as $key => $user)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $user->id }}">
                          </td>
                          <td>{{ $user->username }}</td>
                          <td>{{ $user->email }}</td>
                          <td>{{ $user->category_name ?? __('N/A') }}</td>

                          <td>
                            <form id="userFrom{{ $user->id }}" class="d-inline-block"
                              action="{{ route('register.user.featured') }}" method="post">
                              @csrf
                              <select
                                class="form-control form-control-sm {{ $user->featured == 1 ? 'bg-success' : 'bg-danger' }}"
                                name="featured"
                                onchange="document.getElementById('userFrom{{ $user->id }}').submit();">
                                <option value="1" {{ $user->featured == 1 ? 'selected' : '' }}>{{ __('Yes') }}
                                </option>
                                <option value="0" {{ $user->featured == 0 ? 'selected' : '' }}>{{ __('No') }}
                                </option>
                              </select>
                              <input type="hidden" name="user_id" value="{{ $user->id }}">
                            </form>
                          </td>

                          <td>
                            <div class="d-inline-block">
                              <select data-user_id="{{ $user->id }}"
                                class="template-select form-control form-control-sm {{ $user->preview_template == 1 ? 'bg-success' : 'bg-danger' }}"
                                name="preview_template">
                                <option value="1" {{ $user->preview_template == 1 ? 'selected' : '' }}>
                                  {{ __('Yes') }}</option>
                                <option value="0" {{ $user->preview_template == 0 ? 'selected' : '' }}>
                                  {{ __('No') }}</option>
                              </select>
                            </div>
                            @if ($user->preview_template == 1)
                              <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#templateImgModal{{ $user->id }}">{{ __('Edit') }}</button>
                            @endif
                          </td>

                          @includeIf('admin.register_user.template-modal')
                          @includeIf('admin.register_user.template-image-modal')

                          <td>
                            <form id="emailForm{{ $user->id }}" class="d-inline-block"
                              action="{{ route('register.user.email') }}" method="post">
                              @csrf
                              <select
                                class="form-control form-control-sm {{ strtolower($user->email_verified) == 1 ? 'bg-success' : 'bg-danger' }}"
                                name="email_verified"
                                onchange="document.getElementById('emailForm{{ $user->id }}').submit();">
                                <option value="1" {{ strtolower($user->email_verified) == 1 ? 'selected' : '' }}>
                                  {{ __('Verified') }}</option>
                                <option value="0" {{ strtolower($user->email_verified) == 0 ? 'selected' : '' }}>
                                  {{ __('Unverified') }}</option>
                              </select>
                              <input type="hidden" name="user_id" value="{{ $user->id }}">
                            </form>
                          </td>

                          <td>
                            <form id="statusForm{{ $user->id }}" class="d-inline-block"
                              action="{{ route('register.user.ban') }}" method="post">
                              @csrf
                              <select
                                class="form-control form-control-sm {{ $user->status == 1 ? 'bg-success' : 'bg-danger' }}"
                                name="status"
                                onchange="document.getElementById('statusForm{{ $user->id }}').submit();">
                                <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>{{ __('Active') }}
                                </option>
                                <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>{{ __('Deactive') }}
                                </option>
                              </select>
                              <input type="hidden" name="user_id" value="{{ $user->id }}">
                            </form>
                          </td>
                          <td>
                            <div class="dropdown  ">
                              <button class="btn btn-info btn-sm dropdown-toggle" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                {{ __('Actions') }}
                              </button>
                              <div class="dropdown-menu dropdown-style2" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item"
                                  href="{{ route('register.user.view', $user->id) }}">{{ __('Details') }}</a>
                                <a class="dropdown-item"
                                  href="{{ route('register.user.changePass', $user->id) }}">{{ __('Change Password') }}</a>
                                <button class="editbtn editBtn" style="display: none;" data-toggle="modal" data-target="#mailModal"
                                  data-email="{{ $user->email }}">{{ __('Mail') }}</button>

                                <form class="deleteform d-block" action="{{ route('register.user.delete') }}"
                                  method="post">
                                  @csrf
                                  <input type="hidden" name="user_id" value="{{ $user->id }}">
                                  <button type="submit" class="deletebtn">
                                    {{ __('Delete') }}
                                  </button>
                                </form>
                                <a target="_blank" class="dropdown-item"
                                  href="{{ route('register.user.secret_login', $user->id) }}">{{ __('Secret Login') }}</a>
                              </div>
                            </div>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="row">
            <div class="d-inline-block mx-auto">
              {{ $users->appends(['term' => request()->input('term')])->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- Send Mail Modal -->
  <div class="modal fade" id="mailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="exampleModalLongTitle">{{ __('Send Mail') }}</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="ajaxEditForm" class="" action="{{ route('admin.custom-domain.mail') }}" method="POST">
            @csrf
            <div class="form-group">
              <label for="">{{ __('Email Address') }} <span class="text-danger">**</span></label>
              <input id="inemail" type="email" class="form-control" name="email"
                placeholder="{{ __('Enter email') }}">
              <p id="eerremail" class="mb-0 text-danger em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Subject') }} <span class="text-danger">**</span></label>
              <input id="insubject" type="text" class="form-control" name="subject" value=""
                placeholder="{{ __('Enter subject') }}">
              <p id="eerrsubject" class="mb-0 text-danger em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Message') }} <span class="text-danger">**</span></label>
              <textarea id="inmessage" class="form-control summernote" name="message" placeholder="{{ __('Enter message') }}"
                data-height="150"></textarea>
              <p id="eerrmessage" class="mb-0 text-danger em"></p>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
          <button id="updateBtn" type="button" class="btn btn-primary">{{ __('Send Mail') }}</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add User') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
          <form action="{{ route('register.user.store') }}" method="POST" id="ajaxForm">
            @csrf
            <div class="form-group">
              <label for="">{{ __('Username') }} <span class="text-danger">**</span></label>
              <input class="form-control" type="text" name="username">
              <p id="errusername" class="text-danger mb-0 em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Shop Name') }} <span class="text-danger">**</span></label>
              <input class="form-control" type="text" name="shop_name">
              <p id="errshop_name" class="text-danger mb-0 em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Category') }}</label>
              <select name="category" class="form-control">
                <option value="" selected>{{ __('Select Category') }}</option>
                @foreach ($categories as $category)
                  <option value="{{ $category->unique_id }}">{{ $category->name }}</option>
                @endforeach
              </select>
              <p id="errcategory" class="text-danger mb-0 em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Email') }} <span class="text-danger">**</span></label>
              <input class="form-control" type="email" name="email">
              <p id="erremail" class="text-danger mb-0 em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Password') }} <span class="text-danger">**</span></label>
              <input class="form-control" type="password" name="password">
              <p id="errpassword" class="text-danger mb-0 em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Confirm Password') }} <span class="text-danger">**</span></label>
              <input class="form-control" type="password" name="password_confirmation">
            </div>
            <div class="form-group">
              <label for="">{{ __('Package / Plan') }} <span class="text-danger">**</span></label>
              <select name="package_id" class="form-control">
                @if (!empty($packages))
                  @foreach ($packages as $package)
                    <option value="{{ $package->id }}">{{ __($package->title) }} ({{ __($package->term) }})</option>
                  @endforeach
                @endif
              </select>
              <p id="errpackage_id" class="text-danger mb-0 em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Payment Gateway') }} <span class="text-danger">**</span></label>
              <select name="payment_gateway" class="form-control">
                @if (!empty($gateways))
                  @foreach ($gateways as $gateway)
                    <option value="{{ $gateway->name }}">{{ __(ucwords($gateway->name)) }}</option>
                  @endforeach
                @endif
              </select>
              <p id="errpayment_gateway" class="text-danger mb-0 em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Status Público') }} <span class="text-danger">**</span></label>
              <select name="online_status" class="form-control">
                <option value="1">{{ __('Não') }}</option>
                <option value="0">{{ __('Sim') }}</option>
              </select>
              <p id="erronline_status" class="text-danger mb-0 em"></p>
            </div>
            
            <!-- Address Fields -->
            <h6 class="mt-4 mb-3">{{ __('Informações de Endereço') }} <small class="text-muted">({{ __('Opcional') }})</small></h6>
            
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">{{ __('Token Frenet') }}</label>
                  <input class="form-control" type="text" name="token_frenet" id="token_frenet" placeholder="Token da API Frenet">
                  <p id="errtoken_frenet" class="text-danger mb-0 em"></p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">{{ __('CNPJ') }}</label>
                  <input class="form-control" type="text" name="cnpj" id="cnpj" maxlength="18" placeholder="00.000.000/0000-00">
                  <p id="errcnpj" class="text-danger mb-0 em"></p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">{{ __('CEP') }}</label>
                  <input class="form-control" type="text" name="cep" id="cep" maxlength="8" placeholder="Apenas números">
                  <p id="errcep" class="text-danger mb-0 em"></p>
                </div>
              </div>
            </div>
            
            <div class="form-group">
              <label for="">{{ __('Rua') }}</label>
              <input class="form-control" type="text" name="rua" id="rua">
              <p id="errrua" class="text-danger mb-0 em"></p>
            </div>
            
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="">{{ __('Número') }}</label>
                  <input class="form-control" type="text" name="numero" id="numero">
                  <p id="errnumero" class="text-danger mb-0 em"></p>
                </div>
              </div>
              <div class="col-md-8">
                <div class="form-group">
                  <label for="">{{ __('Complemento') }}</label>
                  <input class="form-control" type="text" name="complemento" id="complemento">
                  <p id="errcomplemento" class="text-danger mb-0 em"></p>
                </div>
              </div>
            </div>
            
            <div class="form-group">
              <label for="">{{ __('Bairro') }}</label>
              <input class="form-control" type="text" name="bairro" id="bairro">
              <p id="errbairro" class="text-danger mb-0 em"></p>
            </div>
            
            <div class="row">
              <div class="col-md-8">
                <div class="form-group">
                  <label for="">{{ __('Cidade') }}</label>
                  <input class="form-control" type="text" name="cidade" id="cidade">
                  <p id="errcidade" class="text-danger mb-0 em"></p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="">{{ __('Estado') }}</label>
                  <input class="form-control" type="text" name="estado" id="estado" maxlength="2" placeholder="Ex: SP">
                  <p id="errestado" class="text-danger mb-0 em"></p>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer text-center">
          <button id="submitBtn" type="button" class="btn btn-primary">{{ __('Add User') }}</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
<script>
// Aguardar jQuery estar disponível
if (typeof $ === 'undefined') {
    console.error('jQuery não está carregado!');
} else {
    $(document).ready(function() {
    // Máscara para CNPJ (igual ao shop_settings)
    $("#cnpj").on('input', function() {
        var cnpj = $(this).val().replace(/\D/g, '');
        if (cnpj.length > 14) {
            cnpj = cnpj.substr(0, 14);
        }
        if (cnpj.length >= 14) {
            cnpj = cnpj.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/, "$1.$2.$3/$4-$5");
        }
        $(this).val(cnpj);
    });
    
    // ViaCEP - usando a mesma rota que funciona no shop_settings
    $("#cep").on('blur', function() {
        var cep = $(this).val().replace(/\D/g, '');
        if (cep.length == 8) {
            $.get("{{ route('user.shipping.consulta-cep') }}?cep=" + cep, function(data) {
                $("#rua").val(data.rua);
                $("#bairro").val(data.bairro);
                $("#cidade").val(data.cidade);
                $("#estado").val(data.estado);
                $("#numero").focus();
            }).fail(function() {
                alert("CEP não encontrado");
            });
        }
    });
    
    // Limpar campos de endereço quando CEP for alterado
    $('#cep').on('input', function() {
        // Apenas permitir números
        var value = $(this).val().replace(/\D/g, '');
        $(this).val(value);
        
        // Limpar outros campos
        $('#rua, #bairro, #cidade, #estado').val('');
    });
    
    // Interceptar o submit para remover máscaras antes de enviar
    $('#submitBtn').on('click', function(e) {
        // Remover máscara do CNPJ antes de enviar
        var cnpjField = $('#cnpj');
        if (cnpjField.val()) {
            cnpjField.val(cnpjField.val().replace(/\D/g, ''));
        }
        
        // Remover máscara do CEP antes de enviar  
        var cepField = $('#cep');
        if (cepField.val()) {
            cepField.val(cepField.val().replace(/\D/g, ''));
        }
        
        // Permitir que o script do custom.js continue executando
        // Depois de um pequeno delay, restaurar as máscaras para o usuário
        setTimeout(function() {
            // Restaurar máscara do CNPJ
            if (cnpjField.val() && cnpjField.val().length >= 14) {
                var cnpj = cnpjField.val();
                cnpjField.val(cnpj.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/, "$1.$2.$3/$4-$5"));
            }
        }, 100);
    });
    }); // Fim do document ready
} // Fim do check jQuery
</script>
@endsection
