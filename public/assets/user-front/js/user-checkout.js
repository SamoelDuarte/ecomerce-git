'use strict';

if (stripe_key) {
    // Set your Stripe public key
    var stripe = Stripe(stripe_key);
    // Create a Stripe Element for the card field
    var elements = stripe.elements();
    var cardElement = elements.create('card', {
        style: {
            base: {
                iconColor: '#454545',
                color: '#454545',
                fontWeight: '500',
                lineHeight: '50px',
                fontSmoothing: 'antialiased',
                backgroundColor: '#f2f2f2',
                ':-webkit-autofill': {
                    color: '#454545',
                },
                '::placeholder': {
                    color: '#454545',
                },
            }
        },
    });

    // Add an instance of the card Element into the `card-element` div
    cardElement.mount('#stripe-element');
}


// apply coupon functionality starts
function applyCoupon() {
    // salvar HTML dos métodos de entrega antes do reload
    const shippingOptionsHTML = $('#frenetShippingMethods').html();

    $.post(
        coupon_url, {
            coupon: $("input[name='coupon']").val(),
            _token: document.querySelector('meta[name=csrf-token]').getAttribute('content')
        },
        function (data) {
            if (data.status == 'success') {
                toastr["success"](data.message);
                $("input[name='coupon']").val('');

                $("#cartTotal").load(location.href + " #cartTotal", function () {
                    // restaurar os métodos de entrega
                    $('#frenetShippingMethods').html(shippingOptionsHTML);

                    // garantir que o total está certo com cupom + frete
                    recalculateTotal();
                });
            } else {
                toastr["error"](data.message);
            }
        }
    );
}

$("input[name='coupon']").on('keypress', function (e) {
    let code = e.which;
    if (code == 13) {
        e.preventDefault();
        applyCoupon();
    }
});

$('body').on('click', '.couponBtn', function (e) {
    e.preventDefault();
    applyCoupon();
})

// apply coupon functionality ends
$(document).on('click', '.shipping-charge', function () {
    let total = 0;
    let subtotal = 0;
    let grantotal = 0;
    let shipping = 0;
    subtotal = parseFloat($('.subtotal').attr('data'));
    grantotal = parseFloat($('.grandTotal').attr('data'));
    shipping = parseFloat($('.shipping').attr('data'));
    var new_grandtotal = grantotal - shipping;
    let shipCharge = parseFloat($(this).attr('data'));
    shipping = parseFloat(shipCharge);

    total = parseFloat(parseFloat(new_grandtotal) + shipping);

    $(".shipping").text(
        (ucurrency_position == 'left' ? ucurrency_symbol : '') +
        shipping +
        (ucurrency_position == 'right' ? ucurrency_symbol : '')
    );

    $(".grandTotal").text(
        (ucurrency_position == 'left' ? ucurrency_symbol : '') +
        total +
        (ucurrency_position == 'right' ? ucurrency_symbol : '')
    );
})


$(document).ready(function () {
    $('#zipcode').mask('00000-000'); // aplica a máscara

    let lastCep = "";

    $('#zipcode').on('input', function () {
        const cep = $(this).val().replace(/\D/g, '');

        if (cep.length === 8 && cep !== lastCep) {
            lastCep = cep;

            $.getJSON(`/consulta-cep/${cep}`, function (data) {
                if (!("erro" in data)) {
                    $('#billing_street').val(data.logradouro);
                    $('#billing_neighborhood').val(data.bairro);
                    $('#city').val(data.localidade);
                    $('#district').val(data.uf);
                    $('#billing_number_home').focus(); // foca no campo número

                } else {
                    alert('CEP não encontrado!');
                }
            }).fail(function () {
                alert('Erro ao buscar o CEP.');
            });
        }
    });
});

$(document).ready(function () {
    $('#shipping_zip').mask('00000-000');

    let lastShippingCep = "";

    $('#shipping_zip').on('input', function () {
        const cep = $(this).val().replace(/\D/g, '');

        if (cep.length === 8 && cep !== lastShippingCep) {
            lastShippingCep = cep;

            $.getJSON(`/consulta-cep/${cep}`, function (data) {
                if (!("erro" in data)) {
                    $('#shipping_street').val(data.logradouro);
                    $('#shipping_neighborhood').val(data.bairro);
                    $('#shipping_city').val(data.localidade);
                    $('#shipping_state').val(data.uf);

                    // Foca no número
                    $('#shipping_number_address').focus();
                } else {
                    alert('CEP não encontrado!');
                }
            }).fail(function () {
                alert('Erro ao buscar o CEP.');
            });
        }
    });
});

$(document).ready(function () {
    // Máscara para o CEP de cobrança
    $('#billing_zip').mask('00000-000');

    let lastBillingCep = "";

    $('#billing_zip').on('input', function () {
        const cep = $(this).val().replace(/\D/g, '');

        if (cep.length === 8 && cep !== lastBillingCep) {
            lastBillingCep = cep;

            $.getJSON(`/consulta-cep/${cep}`, function (data) {
                if (!("erro" in data)) {
                    // Aqui você precisa usar os campos correspondentes do endereço de cobrança
                    $('#billing_street').val(data.logradouro);
                    $('#billing_neighborhood').val(data.bairro);
                    $('#billing_city').val(data.localidade);
                    $('#billing_state').val(data.uf);

                    // Move o foco para o número
                    $('#billing_number_address').focus();
                } else {
                    alert('CEP de cobrança não encontrado!');
                }
            }).fail(function () {
                alert('Erro ao buscar o CEP de cobrança.');
            });
        }
    });
});


$('body').on('click', '#differentaddress', function () {
    if ($(this).is(':checked')) {
        $('#collapseAddress').addClass('show');
    } else {
        $('#collapseAddress').removeClass('show');
    }
});

$("#payment-gateway").on('change', function () {
    let offline = offline_gateways;
    let data = [];
    offline.map(({
        id,
        name
    }) => {
        data.push(name);
    });
    let paymentMethod = $("#payment-gateway").val();
    $("input[name='payment_method']").val(paymentMethod);

    $(".gateway-details").hide();
    $(".gateway-details input").attr('disabled', true);

    if (paymentMethod == 'Stripe') {
        $("#tab-stripe").show();
        $("#tab-stripe input").removeAttr('disabled');
    } else {
        $("#tab-stripe").hide();
    }

    if (paymentMethod == 'Authorize.net') {
        $("#tab-anet").show();
        $("#tab-anet input").removeAttr('disabled');
    } else {
        $("#tab-anet").hide();
    }
    if (paymentMethod == 'Iyzico') {
        $(".iyzico-element").removeClass('d-none');
    } else {
        $(".iyzico-element").addClass('d-none');
    }

    if (data.indexOf(paymentMethod) != -1) {
        let formData = new FormData();
        formData.append('name', paymentMethod);
        $.ajax({
            url: instruction_url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            contentType: false,
            processData: false,
            cache: false,
            data: formData,
            success: function (data) {
                let instruction = $("#instructions");
                let instructions =
                    `<div class="gateway-desc">${data.instructions}</div>`;
                if (data.description != null) {
                    var description =
                        `<div class="gateway-desc"><p>${data.description}</p></div>`;
                } else {
                    var description = `<div></div>`;
                }
                let receipt = `<div class="form-element mb-2">
                                      <label>Receipt<span>*</span></label><br>
                                      <input type="file" name="receipt" value="" class="file-input" required>
                                      <p class="mb-0 text-warning">** Receipt image must be .jpg / .jpeg / .png</p>
                                   </div>`;
                if (data.is_receipt == 1) {
                    $("#is_receipt").val(1);
                    let finalInstruction = instructions + description + receipt;
                    instruction.html(finalInstruction);
                } else {
                    $("#is_receipt").val(0);
                    let finalInstruction = instructions + description;
                    instruction.html(finalInstruction);
                }
                $('#instructions').fadeIn();
            },
            error: function (data) {}
        })
    } else {
        $('#instructions').fadeOut();
    }
});


$(document).ready(function () {
    $("#userOrderForm").on('submit', function (e) {
        e.preventDefault();
        $(this).find('button[type="submit"]').prop('disabled', true).text(processing_text);
        let val = $("#payment-gateway").val();
        if (val == 'Authorize.net') {
            sendPaymentDataToAnet();
        } else if (val == 'Stripe') {
            stripe.createToken(cardElement).then(function (result) {
                if (result.error) {
                    // Display errors to the customer
                    var errorElement = document.getElementById('stripe-errors');
                    errorElement.textContent = result.error.message;
                    $("#userOrderForm").find('button[type="submit"]').prop('disabled', false).text(place_order);
                } else {
                    // Send the token to your server
                    stripeTokenHandler(result.token);
                }
            });
        } else {
            $(this).unbind('submit').submit();
        }
    });
});


//stripe token handler
function stripeTokenHandler(token) {
    // Add the token to the form data before submitting to the server
    var form = document.getElementById('userOrderForm');
    var hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'stripeToken');
    hiddenInput.setAttribute('value', token.id);
    form.appendChild(hiddenInput);

    // Submit the form to your server
    form.submit();
}


function sendPaymentDataToAnet() {
    // Set up authorisation to access the gateway.
    var authData = {};
    authData.clientKey = anet_public_key;
    authData.apiLoginID = anet_login_id;

    var cardData = {};
    cardData.cardNumber = document.getElementById("anetCardNumber").value;
    cardData.month = document.getElementById("anetExpMonth").value;
    cardData.year = document.getElementById("anetExpYear").value;
    cardData.cardCode = document.getElementById("anetCardCode").value;

    // Now send the card data to the gateway for tokenisation.
    // The responseHandler function will handle the response.
    var secureData = {};
    secureData.authData = authData;
    secureData.cardData = cardData;
    Accept.dispatchData(secureData, responseHandler);
}

function responseHandler(response) {
    if (response.messages.resultCode == "Error") {
        var i = 0;
        let errorLists = ``;
        while (i < response.messages.message.length) {
            errorLists += `<li class="text-danger">${response.messages.message[i].text}</li>`;

            i = i + 1;
        }
        $("#anetErrors").show();
        $("#anetErrors").html(errorLists);
        $("#userOrderForm").find('button[type="submit"]').prop('disabled', false).text(place_order);
    } else {
        paymentFormUpdate(response.opaqueData);
    }
}

function paymentFormUpdate(opaqueData) {
    document.getElementById("opaqueDataDescriptor").value = opaqueData.dataDescriptor;
    document.getElementById("opaqueDataValue").value = opaqueData.dataValue;
    document.getElementById("userOrderForm").submit();
}


// Função para atualizar mensagem de dias de despacho
function updateDispatchMessage(dias) {
    let message = `<li class="d-flex justify-content-between dispatch-info">
        <h5 class="mb-0" style="color: #28a745;">
            <i class="fas fa-clock mr-2"></i>
            ${dias > 0 ? 'Seu pedido será despachado em ' + dias + ' dias úteis' : 'Despacho no mesmo dia'}
        </h5>
    </li>`;
    $('#frenetShippingDetails').html(message);
}

$(document).ready(function () {
    let carregandoCEP = false;

    function carregarEntregaPorCep() {
        // // Verificar se a seção de métodos de entrega existe (só para produtos físicos)
        // if ($('#frenetShippingMethods').length === 0) {
        //     console.log('Métodos de entrega não disponíveis - apenas produtos digitais');
        //     return;
        // }

        // Verificar se o frete está ativo
        if (typeof frenet_enable !== 'undefined' && frenet_enable === 0) {
            console.log('Frete Frenet desativado');
            $('#frenetShippingMethods').html('<div class="alert alert-info text-center"><h5>🚚 Frete não disponível neste momento</h5></div>');
            $('#shipping_service_price').val(0);
            $('#shipping_service_name').val('Frete não disponível');
            recalculateTotal();
            return;
        }

        const cepVal = $('#billing_zip').val();
        const cep = cepVal.replace(/\D/g, '');

        if (cep.length !== 8 || carregandoCEP) return;

        carregandoCEP = true;

        $.ajax({
            url: `/calcular-entrega/${cep}`,
            type: 'GET',
            success: function (res) {
                carregandoCEP = false;
                console.log('Resposta da API Frenet:', res);

                // Se não precisa de frete (só produtos digitais)
                if (res && Array.isArray(res.ShippingSevicesArray) && res.ShippingSevicesArray.length === 0) {
                    $('#frenetShippingMethods').html('<p class="text-info">Nenhum frete necessário (somente produtos digitais)</p>');
                    $('#frenetShippingDetails').html('');
                    $('#shipping_service_price').val(0);
                    $('#shipping_service_name').val('Sem frete necessário');
                    recalculateTotal();
                    return;
                }

                if (res && res.ShippingSevicesArray) {
                    console.log('Serviços encontrados:', res.ShippingSevicesArray.length);
                    let html = `<h5 class="mt-3 hide">Escolha a Entrega</h5>`;
                    let servicosValidos = 0;
                    let errosEncontrados = [];

                    res.ShippingSevicesArray.forEach((servico, index) => {
                        console.log(`Serviço ${index}:`, servico);
                        
                        // Verifica se o serviço tem erro (via Error ou Msg contendo "Erro:")
                        const temErro = servico.Error || (servico.Msg && servico.Msg.includes('Erro:'));
                        
                        if (!temErro && servico.ShippingPrice && servico.ShippingPrice > 0) {
                            servicosValidos++;
                            const checked = servicosValidos === 1 ? 'checked' : '';

                            html += `
                                <div class="form-check">
                                    <input class="form-check-input shipping-option" type="radio" name="shipping_service"
                                        id="shipping_${index}" 
                                        value="${servico.ShippingPrice}"
                                        data-service="${servico.ServiceDescription}"
                                        data-carrier="${servico.Carrier}"
                                        data-delivery="${servico.DeliveryTime}"
                                        ${checked} required>
                                    <label class="form-check-label" for="shipping_${index}">
                                        ${servico.ServiceDescription} - ${servico.Carrier} - R$ ${parseFloat(servico.ShippingPrice).toFixed(2)} - ${servico.DeliveryTime} dia(s)
                                    </label>
                                </div>
                            `;

                            if (servicosValidos === 1) {
                                $('#shipping_service_price').val(servico.ShippingPrice);
                                $('#shipping_service_name').val(`${servico.ServiceDescription} - ${servico.Carrier} - ${servico.DeliveryTime} dia(s)`);
                                updateDispatchMessage(servico.DeliveryTime);
                            }
                        } else {
                            // Coleta os erros para mostrar ao usuário
                            const erro = servico.Msg || servico.Error || 'Erro desconhecido';
                            errosEncontrados.push(`${servico.ServiceDescription}: ${erro}`);
                        }
                    });

                    if (servicosValidos === 0) {
                        // Se não há serviços válidos, mostra mensagem simples
                        html = `
                            <div class="alert alert-warning text-center">
                                <h5>📦 Sem opções de frete para sua região</h5>
                            </div>
                        `;
                        $('#shipping_service_price').val(0);
                        $('#shipping_service_name').val('Sem opções de frete');
                    }

                    $('#frenetShippingMethods').html(html);
                    recalculateTotal();
                } else {
                    console.log('Nenhum serviço de entrega disponível ou resposta inválida');
                    $('#frenetShippingMethods').html('<div class="alert alert-warning text-center"><h5>📦 Sem opções de frete para sua região</h5></div>');
                }
            },
            error: function (xhr) {
                carregandoCEP = false;
                console.error('Erro ao calcular taxa de entrega:', xhr);
                console.error('Status:', xhr.status);
                console.error('Response:', xhr.responseText);
                
                $('#frenetShippingMethods').html('<div class="alert alert-warning text-center"><h5>📦 Sem opções de frete para sua região</h5></div>');
            }
        });
    }

    $('#billing_zip').on('blur', carregarEntregaPorCep);
    $('#billing_zip').on('keyup', function () {
        if ($(this).val().replace(/\D/g, '').length === 8) {
            carregarEntregaPorCep();
        }
    });
    if ($('#billing_zip').val().replace(/\D/g, '').length === 8) {
        carregarEntregaPorCep();
    }

    $('body').on('change', '.shipping-option', function (e) {
        recalculateTotal();

        if (e.target.classList.contains('shipping-option')) {
            const price = e.target.value;
            const service = e.target.dataset.service;
            const carrier = e.target.dataset.carrier;
            const delivery = e.target.dataset.delivery;

            $('#shipping_service_price').val(price);
            $('#shipping_service_name').val(`${service} - ${carrier} - ${delivery} dia(s)`);
            updateDispatchMessage(delivery);
        }
    });

    function recalculateTotal() {
        let shippingPrice = parseFloat($("input[name='shipping_service']:checked").val()) || 0;
        let subtotal = parseFloat($("#subtotal").attr('data')) || 0;
        let tax = parseFloat($("#tax").attr('data-tax')) || 0;

        let total = subtotal + tax + shippingPrice;

        $(".grandTotal").attr('data', total.toFixed(2));
        $(".grandTotal").text(
            (ucurrency_position === 'left' ? ucurrency_symbol : '') +
            total.toFixed(2) +
            (ucurrency_position === 'right' ? ucurrency_symbol : '')
        );
    }
});


// apply coupon functionality ends
$(document).on('click', '.shipping-charge', function () {
    let total = 0;
    let subtotal = 0;
    let grantotal = 0;
    let shipping = 0;
    subtotal = parseFloat($('.subtotal').attr('data'));
    grantotal = parseFloat($('.grandTotal').attr('data'));
    shipping = parseFloat($('.shipping').attr('data'));
    var new_grandtotal = grantotal - shipping;
    let shipCharge = parseFloat($(this).attr('data'));
    shipping = parseFloat(shipCharge);

    total = parseFloat(parseFloat(new_grandtotal) + shipping);

    $(".shipping").text(
        (ucurrency_position == 'left' ? ucurrency_symbol : '') +
        shipping +
        (ucurrency_position == 'right' ? ucurrency_symbol : '')
    );

    $(".grandTotal").text(
        (ucurrency_position == 'left' ? ucurrency_symbol : '') +
        total +
        (ucurrency_position == 'right' ? ucurrency_symbol : '')
    );
})

$('body').on('click', '#differentaddress', function () {
    if ($(this).is(':checked')) {
        $('#collapseAddress').addClass('show');
    } else {
        $('#collapseAddress').removeClass('show');
    }
});

$("#payment-gateway").on('change', function () {
    let offline = offline_gateways;
    let data = [];
    offline.map(({
        id,
        name
    }) => {
        data.push(name);
    });
    let paymentMethod = $("#payment-gateway").val();
    $("input[name='payment_method']").val(paymentMethod);

    $(".gateway-details").hide();
    $(".gateway-details input").attr('disabled', true);

    if (paymentMethod == 'Stripe') {
        $("#tab-stripe").show();
        $("#tab-stripe input").removeAttr('disabled');
    } else {
        $("#tab-stripe").hide();
    }

    if (paymentMethod == 'Authorize.net') {
        $("#tab-anet").show();
        $("#tab-anet input").removeAttr('disabled');
    } else {
        $("#tab-anet").hide();
    }
    if (paymentMethod == 'Iyzico') {
        $(".iyzico-element").removeClass('d-none');
    } else {
        $(".iyzico-element").addClass('d-none');
    }

    if (data.indexOf(paymentMethod) != -1) {
        let formData = new FormData();
        formData.append('name', paymentMethod);
        $.ajax({
            url: instruction_url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            contentType: false,
            processData: false,
            cache: false,
            data: formData,
            success: function (data) {
                let instruction = $("#instructions");
                let instructions =
                    `<div class="gateway-desc">${data.instructions}</div>`;
                if (data.description != null) {
                    var description =
                        `<div class="gateway-desc"><p>${data.description}</p></div>`;
                } else {
                    var description = `<div></div>`;
                }
                let receipt = `<div class="form-element mb-2">
                                      <label>Receipt<span>*</span></label><br>
                                      <input type="file" name="receipt" value="" class="file-input" required>
                                      <p class="mb-0 text-warning">** Receipt image must be .jpg / .jpeg / .png</p>
                                   </div>`;
                if (data.is_receipt == 1) {
                    $("#is_receipt").val(1);
                    let finalInstruction = instructions + description + receipt;
                    instruction.html(finalInstruction);
                } else {
                    $("#is_receipt").val(0);
                    let finalInstruction = instructions + description;
                    instruction.html(finalInstruction);
                }
                $('#instructions').fadeIn();
            },
            error: function (data) {}
        })
    } else {
        $('#instructions').fadeOut();
    }
});


$(document).ready(function () {
    $("#userOrderForm").on('submit', function (e) {
        e.preventDefault();
        $(this).find('button[type="submit"]').prop('disabled', true).text(processing_text);
        let val = $("#payment-gateway").val();
        if (val == 'Authorize.net') {
            sendPaymentDataToAnet();
        } else if (val == 'Stripe') {
            stripe.createToken(cardElement).then(function (result) {
                if (result.error) {
                    // Display errors to the customer
                    var errorElement = document.getElementById('stripe-errors');
                    errorElement.textContent = result.error.message;
                    $("#userOrderForm").find('button[type="submit"]').prop('disabled', false).text(place_order);
                } else {
                    // Send the token to your server
                    stripeTokenHandler(result.token);
                }
            });
        } else {
            $(this).unbind('submit').submit();
        }
    });
});

//stripe token handler
function stripeTokenHandler(token) {
    // Add the token to the form data before submitting to the server
    var form = document.getElementById('userOrderForm');
    var hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'stripeToken');
    hiddenInput.setAttribute('value', token.id);
    form.appendChild(hiddenInput);

    // Submit the form to your server
    form.submit();
}

function sendPaymentDataToAnet() {
    // Set up authorisation to access the gateway.
    var authData = {};
    authData.clientKey = anet_public_key;
    authData.apiLoginID = anet_login_id;

    var cardData = {};
    cardData.cardNumber = document.getElementById("anetCardNumber").value;
    cardData.month = document.getElementById("anetExpMonth").value;
    cardData.year = document.getElementById("anetExpYear").value;
    cardData.cardCode = document.getElementById("anetCardCode").value;

    // Now send the card data to the gateway for tokenisation.
    // The responseHandler function will handle the response.
    var secureData = {};
    secureData.authData = authData;
    secureData.cardData = cardData;
    Accept.dispatchData(secureData, responseHandler);
}

function responseHandler(response) {
    if (response.messages.resultCode == "Error") {
        var i = 0;
        let errorLists = ``;
        while (i < response.messages.message.length) {
            errorLists += `<li class="text-danger">${response.messages.message[i].text}</li>`;

            i = i + 1;
        }
        $("#anetErrors").show();
        $("#anetErrors").html(errorLists);
        $("#userOrderForm").find('button[type="submit"]').prop('disabled', false).text(place_order);
    } else {
        paymentFormUpdate(response.opaqueData);
    }
}

function paymentFormUpdate(opaqueData) {
    document.getElementById("opaqueDataDescriptor").value = opaqueData.dataDescriptor;
    document.getElementById("opaqueDataValue").value = opaqueData.dataValue;
    document.getElementById("userOrderForm").submit();
}


$(document).ready(function () {
    $('#zipcode').mask('00000-000'); // aplica a máscara

    let lastCep = "";

    $('#zipcode').on('input', function () {
        const cep = $(this).val().replace(/\D/g, '');

        if (cep.length === 8 && cep !== lastCep) {
            lastCep = cep;

            $.getJSON(`/consulta-cep/${cep}`, function (data) {
                if (!("erro" in data)) {
                    $('#billing_street').val(data.logradouro);
                    $('#billing_neighborhood').val(data.bairro);
                    $('#city').val(data.localidade);
                    $('#district').val(data.uf);
                    $('#billing_number_home').focus(); // foca no campo número

                } else {
                    alert('CEP não encontrado!');
                }
            }).fail(function () {
                alert('Erro ao buscar o CEP.');
            });
        }
    });
});

$(document).ready(function () {
    $('#shipping_zip').mask('00000-000');

    let lastShippingCep = "";

    $('#shipping_zip').on('input', function () {
        const cep = $(this).val().replace(/\D/g, '');

        if (cep.length === 8 && cep !== lastShippingCep) {
            lastShippingCep = cep;

            $.getJSON(`/consulta-cep/${cep}`, function (data) {
                if (!("erro" in data)) {
                    $('#shipping_street').val(data.logradouro);
                    $('#shipping_neighborhood').val(data.bairro);
                    $('#shipping_city').val(data.localidade);
                    $('#shipping_state').val(data.uf);

                    // Foca no número
                    $('#shipping_number_address').focus();
                } else {
                    alert('CEP não encontrado!');
                }
            }).fail(function () {
                alert('Erro ao buscar o CEP.');
            });
        }
    });
});
