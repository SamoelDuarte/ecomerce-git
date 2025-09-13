@extends('user.layout')

@section('content')
<div class="page-header">
    <h4 class="page-title">{{ __('Endereço de Envio') }}</h4>
    <ul class="breadcrumbs">
        <li class="nav-home">
            <a href="{{route('user-dashboard')}}">
                <i class="flaticon-home"></i>
            </a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">{{ __('Configurações da Loja') }}</a>
        </li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">{{ __('Endereço para Cálculo de Frete') }}</div>
            </div>
            <div class="card-body">
                <form id="ajaxForm" class="modal-form" action="{{ route('user.shipping.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ __('Token Frenet') }}</label>
                                <input type="text" class="form-control" name="token_frenet" value="{{ $address->token_frenet ?? '' }}" placeholder="Token da API Frenet">
                                <p id="errtoken_frenet" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ __('CNPJ') }}</label>
                                <input type="text" class="form-control" name="cnpj" value="{{ $address->cnpj ?? '' }}" placeholder="Apenas números" maxlength="18">
                                <p id="errcnpj" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ __('CEP') }} *</label>
                                <input type="text" class="form-control" name="cep" id="cep" value="{{ $address->cep ?? '' }}" placeholder="Apenas números" maxlength="8">
                                <p id="errcep" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ __('Número') }} *</label>
                                <input type="text" class="form-control" name="numero" id="numero" value="{{ $address->numero ?? '' }}">
                                <p id="errnumero" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ __('Rua') }} *</label>
                                <input type="text" class="form-control" name="rua" id="rua" value="{{ $address->rua ?? '' }}">
                                <p id="errrua" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ __('Complemento') }}</label>
                                <input type="text" class="form-control" name="complemento" id="complemento" value="{{ $address->complemento ?? '' }}">
                                <p id="errcomplemento" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ __('Bairro') }} *</label>
                                <input type="text" class="form-control" name="bairro" id="bairro" value="{{ $address->bairro ?? '' }}">
                                <p id="errbairro" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="">{{ __('Cidade') }} *</label>
                                <input type="text" class="form-control" name="cidade" id="cidade" value="{{ $address->cidade ?? '' }}">
                                <p id="errcidade" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="">{{ __('Estado') }} *</label>
                                <input type="text" class="form-control" name="estado" id="estado" value="{{ $address->estado ?? '' }}" maxlength="2">
                                <p id="errestado" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="">{{ __('Dias para Despacho') }} *</label>
                                <input type="number" class="form-control" name="dias_despacho" id="dias_despacho" value="{{ $address->dias_despacho ?? 0 }}" min="0">
                                <p id="errdias_despacho" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <div class="form">
                    <div class="form-group from-show-notify row">
                        <div class="col-12 text-center">
                            <button id="submitBtn" type="button" class="btn btn-success">{{ __('Salvar') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $("input[name='cnpj']").on('input', function() {
        var cnpj = $(this).val().replace(/\D/g, '');
        if (cnpj.length > 14) {
            cnpj = cnpj.substr(0, 14);
        }
        if (cnpj.length >= 14) {
            cnpj = cnpj.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/, "$1.$2.$3/$4-$5");
        }
        $(this).val(cnpj);
    });

    $("#submitBtn").on('click', function(e) {
        e.preventDefault();
        $(this).attr('disabled', true);
        $('.request-loader').removeClass('show');
        $('.main-loader').addClass('show');
        let form = document.getElementById('ajaxForm');
        let fd = new FormData(form);
        let url = $("#ajaxForm").attr('action');
        
        $.ajax({
            url: url,
            method: 'POST',
            data: fd,
            contentType: false,
            processData: false,
            success: function(data) {
                $(e.target).attr('disabled', false);
                $('.request-loader').addClass('show');
                $('.main-loader').removeClass('show');
                
                if (data == "success") {
                    location.reload();
                }
            },
            error: function(error) {
                $('.em').each(function() {
                    $(this).html('');
                });
                
                let errors = error.responseJSON.errors;
                $.each(errors, function(key, value) {
                    $("#err"+key).html(value[0]);
                });
                
                $(e.target).attr('disabled', false);
                $('.request-loader').addClass('show');
                $('.main-loader').removeClass('show');
            }
        })
    });
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
});
</script>
@endsection
