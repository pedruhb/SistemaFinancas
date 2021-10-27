$("#register").submit(function (e) {

    $("#botaoRegistrar").prop("disabled", true);
    e.preventDefault();

    var form = $(this);

    if (String(form[0].firstname.value).length == 0) {
        toastr.error("Primeiro nome inválido!");
        $("#botaoRegistrar").prop("disabled", false);
        return;
    }

    if (String(form[0].lastname.value).length == 0) {
        toastr.error("Último nome inválido!");
        $("#botaoRegistrar").prop("disabled", false);
        return;
    }

    if (!validateEmail(form[0].mail.value)) {
        toastr.error("O email é inválido!");
        $("#botaoRegistrar").prop("disabled", false);
        return;
    }

    if (String(form[0].currency.value).length != 3) {
        toastr.error("Selecione uma moeda!");
        $("#botaoRegistrar").prop("disabled", false);
        return;
    }

    if (String(form[0].password.value).length < 5 || String(form[0].repeat_password.value).length < 5) {
        toastr.error("A senha é inválida!");
        $("#botaoRegistrar").prop("disabled", false);
        return;
    }

    if (form[0].password.value != form[0].repeat_password.value) {
        toastr.error("As senhas não coincidem!");
        $("#botaoRegistrar").prop("disabled", false);
        return;
    }

    $.ajax({
        type: "POST",
        url: "/api/auth/registration",
        data: form.serialize(),
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                toastr.success("Você foi registrado! A página irá recarregar em instantes.");
                setInterval(() => {
                    $(location).attr('href', '/index');
                }, 1000);
            } else {
                toastr.error(data.message);
                $("#botaoRegistrar").prop("disabled", false);
            }
        }
    });

});