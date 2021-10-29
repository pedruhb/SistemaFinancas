var categoriasList = {};

$("#categorias").DataTable({
    "language": {
        "url": '/js/datatables_pt_br.json'
    },
    "ajax": {
        "url": "/api/categorias/gastos",
        "dataType": "json",
    },
    "initComplete": function (settings, json) {
        categoriasList = json.data;
    },
    "columns": [{
        data: null,
        render: function (d, t, r) {
            return htmlEncode(r.nome);
        }
    },
    {
        data: null,
        render: function (data, type, row) {
            return '<a class="btn btn-danger" style="background-color:' + row.cor_hex + ';border-color:' + row.cor_hex + ';cursor: default;">' + htmlEncode(row.cor_hex) + '</a>';
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
    var categoria = categoriasList.find(x => x.id == id);
    $("#editar input[name='nome']").val(categoria.nome);
    $("#editar input[name='cor']").val(categoria.cor_hex);
    $("#editar input[name='id']").val(categoria.id);
    $("#editarModal").modal("show");
}

function lancamentos(id) {
    alert("Em breve...");
}

function apagar(id) {
    $("#apagarModal").modal("show");
    $("#apagar input[name='id']").val(id);
}

function addCategoria() {
    $("#adicionarModal").modal("show");
}

$("#editar").submit(function (e) {

    $("#botaoEditar").prop("disabled", true);
    e.preventDefault();

    var form = $(this);

    if (String(form[0].nome.value).length < 3 || String(form[0].nome.value).length > 50) {
        toastr.error("O nome é inválido!");
        $("#botaoEditar").prop("disabled", false);
        return;
    }

    if (!String(form[0].cor.value).startsWith("#")) {
        toastr.error("Cor inválida!");
        $("#botaoEditar").prop("disabled", false);
        return;
    }

    $.ajax({
        type: "POST",
        url: "/api/categorias/editar_gastos",
        data: form.serialize(),
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                categoriasList = data.data;
                $("#categorias").DataTable().clear();
                $("#categorias").DataTable().rows.add(data.data).draw();
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

    if (String(form[0].nome.value).length < 3 || String(form[0].nome.value).length > 50) {
        toastr.error("O nome é inválido!");
        $("#botaoAdicionar").prop("disabled", false);
        return;
    }

    if (!String(form[0].cor.value).startsWith("#")) {
        toastr.error("Cor inválida!");
        $("#botaoAdicionar").prop("disabled", false);
        return;
    }

    $.ajax({
        type: "POST",
        url: "/api/categorias/add_gastos",
        data: form.serialize(),
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                categoriasList = data.data;
                $("#categorias").DataTable().clear();
                $("#categorias").DataTable().rows.add(data.data).draw();
                toastr.success(data.message);
                $("#adicionarModal").modal("hide");
                $("#botaoAdicionar").prop("disabled", false);
                $("#adicionar input[name='nome']").val("");
                $("#adicionar input[name='cor']").val("");
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
        url: "/api/categorias/apagar_gastos",
        data: form.serialize(),
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                categoriasList = data.data;
                $("#categorias").DataTable().clear();
                $("#categorias").DataTable().rows.add(data.data).draw();
                $("#apagarModal").modal("hide");
                toastr.success("A categoria foi removida com sucesso!");
                $("#botaoApagar").prop("disabled", false);
            } else {
                toastr.error(data.message);
                $("#botaoApagar").prop("disabled", false);
            }
        }
    });

});