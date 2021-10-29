var bancosList = {};

$("#bancos").DataTable({
    "language": {
        "url": '/js/datatables_pt_br.json'
    },
    "ajax": {
        "url": "/api/bancos/get",
        "dataType": "json",
    },
    "initComplete": function (settings, json) {
        bancosList = json.data;
    },
    "columns": [{
        data: null,
        render: function (d, t, r) {
            return htmlEncode(r.banco);
        }
    },
    {
        data: null,
        render: function (d, t, r) {
            return htmlEncode(r.agencia);
        }
    },
    {
        data: null,
        render: function (d, t, r) {
            return String(r.conta).concat("-", r.digito);
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
    var banco = bancosList.find(x => x.id == id);
    $("#editar select[name='banco']").val(banco.banco);
    $("#editar input[name='agencia']").val(banco.agencia);
    $("#editar input[name='conta']").val(banco.conta);
    $("#editar input[name='digito']").val(banco.digito);
    $("#editar textarea[name='observacoes']").val(banco.observacoes);
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

function addBanco() {
    $("#adicionarModal").modal("show");
}

$("#editar").submit(function (e) {
    $("#botaoEditar").prop("disabled", true);
    e.preventDefault();
    var form = $(this);

    if (String(form[0].agencia.value).length < 2) {
        toastr.error("A agência é inválida!");
        $("#botaoEditar").prop("disabled", false);
        return;
    }

    if (String(form[0].conta.value).length < 2) {
        toastr.error("A conta é inválida!");
        $("#botaoEditar").prop("disabled", false);
        return;
    }

    $.ajax({
        type: "POST",
        url: "/api/bancos/salvar",
        data: form.serialize(),
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                bancosList = data.data;
                $("#bancos").DataTable().clear();
                $("#bancos").DataTable().rows.add(data.data).draw();
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

    if (String(form[0].agencia.value).length < 2) {
        toastr.error("A agência é inválida!");
        $("#botaoAdicionar").prop("disabled", false);
        return;
    }

    if (String(form[0].conta.value).length < 2) {
        toastr.error("A conta é inválida!");
        $("#botaoAdicionar").prop("disabled", false);
        return;
    }

    $.ajax({
        type: "POST",
        url: "/api/bancos/adicionar",
        data: form.serialize(),
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                bancosList = data.data;
                $("#bancos").DataTable().clear();
                $("#bancos").DataTable().rows.add(data.data).draw();
                toastr.success(data.message);
                $("#adicionarModal").modal("hide");
                $("#botaoAdicionar").prop("disabled", false);
                $("#adicionar select[name='banco']").val("");
                $("#adicionar input[name='agencia']").val("");
                $("#adicionar input[name='conta']").val("");
                $("#adicionar input[name='digito']").val("");
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
        url: "/api/bancos/apagar",
        data: form.serialize(),
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                bancosList = data.data;
                $("#bancos").DataTable().clear();
                $("#bancos").DataTable().rows.add(data.data).draw();
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
        url: "/api/bancos/listagem",
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                for (i = 0; i < data.data.length; i++) {
                    if (data.data[i].code) $("select[name='banco']").append("<option value=\"" + data.data[i].code + "\">" + data.data[i].name + " - " + data.data[i].code + "</option>");
                }
            } else {
                toastr.error(data.message);
            }
        }
    });
});