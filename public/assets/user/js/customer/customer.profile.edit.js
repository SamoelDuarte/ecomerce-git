jQuery.noConflict();
jQuery(document).ready(function($) {
    // Agora o $ é seguro dentro dessa função, mesmo com noConflict ativo
    
    // Máscara para o CEP do billing
    $('#billing_zip').mask('00000-000');

    let lastBillingCep = "";

    $('#billing_zip').on('input', function () {
        const cep = $(this).val().replace(/\D/g, '');

        if (cep.length === 8 && cep !== lastBillingCep) {
            lastBillingCep = cep;

            $.getJSON(`/consulta-cep/${cep}`, function (data) {
                if (!("erro" in data)) {
                    $('#billing_street').val(data.logradouro);
                    $('#billing_neighborhood').val(data.bairro);
                    $('#billing_city').val(data.localidade);
                    $('#billing_state').val(data.uf);

                    // Foca no número da casa após preencher o endereço
                    $('#billing_number_home').focus();
                } else {
                    alert('CEP não encontrado!');
                }
            }).fail(function () {
                alert('Erro ao buscar o CEP.');
            });
        }
    });
});
