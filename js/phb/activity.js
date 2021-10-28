$("#activity").DataTable({
    "language": {
        "url": '/js/datatables_pt_br.json'
    },
    "ajax": {
        "url": "/api/get/activity",
        "dataType": "json",
    },
    "columns": [{
        data: null,
        render: function (d, t, r) {
            return htmlEncode(r.log);
        }
    },
    {
        data: "ip"
    },
    {
        data: null,
        render: function (data, type, row) {
            return moment.unix(parseInt(row.timestamp)).locale('pt-br').fromNow();
        }
    }
    ]
});