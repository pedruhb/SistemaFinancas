$("#login").submit(function (e) {

    $("#botaoLogin").prop("disabled", true);
    e.preventDefault();

    var form = $(this);

    if (!validateEmail(form[0].mail.value)) {
        toastr.error("O email é inválido!");
        $("#botaoLogin").prop("disabled", false);
        return;
    }

    if (String(form[0].password.value).length < 5) {
        toastr.error("A senha é inválida!");
        $("#botaoLogin").prop("disabled", false);
        return;
    }

    $.ajax({
        type: "POST",
        url: "/api/auth/login",
        data: form.serialize(),
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                $(location).attr('href', '/index');
            } else {
                toastr.error(data.message);
                $("#botaoLogin").prop("disabled", false);
            }
        }
    });

});