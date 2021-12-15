$(document).ready(function () {
    $.ajax({
        url: "/api/bancos/listagem",
        dataType: 'json',
        success: function (bancos) {
            if (bancos.success) {
                $.ajax({
                    url: "/api/bancos/get",
                    dataType: 'json',
                    success: function (data) {
                        if (data.success) {
                            for (i = 0; i < data.data.length; i++) {
                                var banco = bancos.data.find(x => x.code == data.data[i].banco);
                                $("select[name='banco']").append("<option value=\"" + data.data[i].id + "\">" + banco.name + " | " + data.data[i].conta + "-" + data.data[i].digito + "</option>");
                            }
                        } else {
                            toastr.error(data.message);
                        }
                    }
                });
            } else {
                toastr.error(data.message);
            }
        }
    });
});

$("#form").submit(function (e) {
    $("#botao").prop("disabled", true);
    e.preventDefault();
    var form = $(this);

    if (String(form[0].nome.value).length < 2 || String(form[0].nome.value).length > 100) {
        toastr.error("O nome é inválido!");
        $("#botao").prop("disabled", false);
        return;
    }

    if (String(form[0].data.value).length != 10) {
        toastr.error("A data é inválida, use no formato DD/MM/YYYY!");
        $("#botao").prop("disabled", false);
        return;
    }

    $.ajax({
        type: "POST",
        url: "/api/saida/adicionar",
        data: form.serialize(),
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                bancosList = data.data;
                toastr.success(data.message);
                setTimeout(() => {
                    $("#botao").prop("disabled", false);
                }, 1000);
            } else {
                toastr.error(data.message);
                $("#botao").prop("disabled", false);
            }
        }
    });

});