var cartoesList = {};

$("#cartoes").DataTable({
    "language": {
        "url": '/js/datatables_pt_br.json'
    },
    "ajax": {
        "url": "/api/cartoes/get",
        "dataType": "json",
    },
    "initComplete": function (settings, json) {
        cartoesList = json.data;
    },
    "columns": [{
        data: null,
        render: function (d, t, r) {
            return htmlEncode(r.nome);
        }
    },
    {
        data: null,
        render: function (d, t, r) {
            return htmlEncode(r.emissor);
        }
    },
    {
        data: null,
        render: function (d, t, r) {
            return htmlEncode(r.ultimos_digitos);
        }
    },
    {
        data: null,
        render: function (d, t, r) {
            return htmlEncode(r.bandeira);
        }
    },
    {
        data: "lancamentos"
    },
    {
        data: null,
        render: function (data, type, row) {
            return '<button type="button" class="btn btn-primary" title="Verificar lançamentos" onclick="lancamentos(' + row.id + ')"><i class="fas fa-eye"></i></button> <button type="button" class="btn btn-danger" title="Apagar" onclick="apagar(' + row.id + ')"><i class="fas fa-trash"></i></button> <button type="button" class="btn btn-secondary" title="Editar" onclick="editar(' + row.id + ')"><i class="fas fa-edit"></i></button>';
        }
    }
    ]
});

function editar(id) {
    var cartao = cartoesList.find(x => x.id == id);
    $("#editar select[name='bandeira']").val(cartao.bandeira);
    $("#editar input[name='nome']").val(cartao.nome);
    $("#editar input[name='emissor']").val(cartao.emissor);
    $("#editar input[name='ultimos']").val(cartao.ultimos_digitos);
    $("#editar textarea[name='observacoes']").val(cartao.observacoes);
    $("#editar input[name='id']").val(id);
    $("#editarModal").modal("show");
}

function lancamentos(id) {
    alert("Em breve...");
}

function apagar(id) {
    $("#apagarModal").modal("show");
    $("#apagar input[name='id']").val(id);
}

function addCartao() {
    $("#adicionarModal").modal("show");
}

$("#editar").submit(function (e) {
    $("#botaoEditar").prop("disabled", true);
    e.preventDefault();
    var form = $(this);

    if (String(form[0].nome.value).length < 2) {
        toastr.error("O nome é inválido!");
        $("#botaoEditar").prop("disabled", false);
        return;
    }

    if (String(form[0].ultimos.value).length != 4) {
        toastr.error("Os últimos 4 dígitos é inválido!");
        $("#botaoEditar").prop("disabled", false);
        return;
    }

    if (String(form[0].emissor.value).length < 2) {
        toastr.error("O emissor é inválido!");
        $("#botaoEditar").prop("disabled", false);
        return;
    }

    $.ajax({
        type: "POST",
        url: "/api/cartoes/salvar",
        data: form.serialize(),
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                cartoesList = data.data;
                $("#cartoes").DataTable().clear();
                $("#cartoes").DataTable().rows.add(data.data).draw();
                $("#editarModal").modal("hide");
                toastr.success(data.message);
                $("#botaoEditar").prop("disabled", false);
            } else {
                toastr.error(data.message);
                $("#botaoEditar").prop("disabled", false);
            }
        }
    });
});

$("#adicionar").submit(function (e) {
    $("#botaoAdicionar").prop("disabled", true);
    e.preventDefault();
    var form = $(this);

    if (String(form[0].nome.value).length < 2) {
        toastr.error("O nome é inválido!");
        $("#botaoAdicionar").prop("disabled", false);
        return;
    }

    if (String(form[0].ultimos.value).length != 4) {
        toastr.error("Os últimos 4 dígitos é inválido!");
        $("#botaoAdicionar").prop("disabled", false);
        return;
    }

    if (String(form[0].emissor.value).length < 2) {
        toastr.error("O emissor é inválido!");
        $("#botaoAdicionar").prop("disabled", false);
        return;
    }

    $.ajax({
        type: "POST",
        url: "/api/cartoes/adicionar",
        data: form.serialize(),
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                cartoesList = data.data;
                $("#cartoes").DataTable().clear();
                $("#cartoes").DataTable().rows.add(data.data).draw();
                toastr.success(data.message);
                $("#adicionarModal").modal("hide");
                $("#botaoAdicionar").prop("disabled", false);
                $("#adicionar select[name='nome']").val("");
                $("#adicionar input[name='bandeira']").val("");
                $("#adicionar input[name='emissor']").val("");
                $("#adicionar input[name='ultimos']").val("");
                $("#adicionar textarea[name='observacoes']").val("");
            } else {
                toastr.error(data.message);
                $("#botaoAdicionar").prop("disabled", false);
            }
        }
    });
});

$("#apagar").submit(function (e) {

    $("#botaoApagar").prop("disabled", true);
    e.preventDefault();

    var form = $(this);

    $.ajax({
        type: "POST",
        url: "/api/cartoes/apagar",
        data: form.serialize(),
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                cartoesList = data.data;
                $("#cartoes").DataTable().clear();
                $("#cartoes").DataTable().rows.add(data.data).draw();
                $("#apagarModal").modal("hide");
                toastr.success(data.message);
                $("#botaoApagar").prop("disabled", false);
            } else {
                toastr.error(data.message);
                $("#botaoApagar").prop("disabled", false);
            }
        }
    });

});

$(document).ready(function () {
    $.ajax({
        url: "/api/cartoes/bandeiras",
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                for (i = 0; i < data.data.length; i++) {
                    $("select[name='bandeira']").append("<option>" + data.data[i] + "</option>");
                }
            } else {
                toastr.error(data.message);
            }
        }
    });
});